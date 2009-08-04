<?php
// Parses R code (r-project.org) and shows the output in a wiki page.
// Author: Xavier de Pedro. <xavier.depedro (a) ub.edu>
// Usage:
// {R(rfile=>fileId)}R code{R} 
//
/* 

From the R Plugin for Mediawiki

(C) 2006- Sigbert Klinke (sigbert@wiwi.hu-berlin.de), Markus Cozowicz, Michael Cassin

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110, USA 

*/

defined('r_dir') || define('r_dir', getcwd() . DIRECTORY_SEPARATOR . 'temp' );
defined('r_ext') || define('r_ext', getcwd() . DIRECTORY_SEPARATOR . 'lib/r' );
defined('security')  || define('security',  0);
defined('sudouser')  || define('sudouser', 'rd');

defined('convert')   || define('convert',   getCmd('', 'convert', ''));
defined('sudo')      || define('sudo',      getCmd('', 'sudo', ' -u ' . sudouser . ' '));
defined('chmod')     || define('chmod',     getCmd('', 'chmod', ' 664 '));
defined('r_cmd')     || define('r_cmd',     getCmd('', 'R', ' --vanilla --quiet'));

function wikiplugin_r_help() {
	return tra("~np~{~/np~R(rfile=>fileId)}R code{R} Parses R code (r-project.org) from between the plugin tags and shows the output in the wiki page");
}

function wikiplugin_r_info() {
	return array(
		'name' => tra('R syntax'),
		'documentation' => 'PluginR',
		'description' => tra('Parses R syntax and shows the output either from the code introduced between the plugin tags or from the file Id from an r script in a Tiki file gallery'),
		'prefs' => array( 'wikiplugin_r' ),
		'params' => array(
			'rfile' => array(
				'required' => false,
				'name' => tra('fileId'),
				'description' => tra('File Id from a file gallery. ex: 1. (Optional)'),
			),
		),
	);
}

function wikiplugin_r($data, $params) {
	global $smarty;

	$output = 'text';
	$style = '';
	$ws = '';
	$sha1 = md5($data . $output . $style);

	// security checks
	if (security>0) {
		$chkres = checkCommands($data);
		#    error ('R', $chkres, $data);
		if (strlen($chkres) != 0) {
			$msg = 'R security check failed: used banned command or parameter "' . $chkres . '"';
			$smarty->assign('msg', $msg);
			$smarty->display('error.tpl');
		}
		$data = $data . "\n#" . $chkres;
	}
	(preg_match('/\W+/', $ws)==0) or error ('R', 'security check failed: invalid workspace name "' . $ws . '"', $data);
	// check if iframe is given, if not then assume direct output
	$iframe = 'width:100%;height:250px;';

	/* TODO: confirm if the code below it is not necessary
	if (array_key_exists('name', $params)) {
		// we may reuse the program ..., thus save all infos
		$sav = r_dir . DIRECTORY_SEPARATOR . $params['name'] . '.sav';
		$fd = fopen ($sav, 'w') or error ('R', 'can not open file: ' . $sav, $data);
		fwrite ($fd, '@output  ' . $output . "\n");
		fwrite ($fd, '@convert ' . $convert . "\n");
		fwrite ($fd, '@sha '     . $sha1 . "\n");
		fwrite ($fd, '@direct '  . $direct . "\n");
		fwrite ($fd, '@echo '    . $echo . "\n");
		fwrite ($fd, '@workspace '. $ws . "\n");
		fwrite ($fd, $data);
		fclose ($fd);
	}*/

	// execute R program
	$fn   = runR ($output, $convert, $sha1, $data, $echo, $ws);

	$ret = file_get_contents ($fn);

	return '~np~'.$ret.'~/np~';
}

function runR ($output, $convert, $sha1, $input, $echo, $ws) {
	// Generate a graphics
	$prg = '';
	$err = "\n";
	$rws = r_dir . DIRECTORY_SEPARATOR;
	$rst  = r_dir . DIRECTORY_SEPARATOR . $sha1 . '.html';
	if (!file_exists($rst) or onsave) {
		$content = '';
		$content .= 'rfiles<-"' . r_dir . '"' . "\n";
		$content .= 'source("' . r_ext . DIRECTORY_SEPARATOR . 'StatWiki.r")' . "\n";
		$content .= $input . "\n";
		$content .= 'q()';
		$fn = r_dir . '/' . $sha1 . '.R';
		$fd = fopen ($fn, 'w') or error('R', 'Can not open file: ' . $fn, $input . $err);
		fwrite ($fd, $content);
		fclose ($fd);
		$cmd = renderFilename(r_cmd . ' --slave 2>&1 < ' . $fn . ' > ' . $rst);
		$r_exitcode = 0;
		$err = $err . runRinShell ($cmd, $rst, $r_exitcode);
	}
	file_exists($rst) or error ('R', 'Text file does not exist: ' . $rst, $input . $err);
	$cont = file_get_contents ($rst);
	if (strpos ($cont, '<html>') === false) {
		$fd = fopen ($rst, 'w') or error ('R', 'can not open file: ' . $rst, $input . $err);
		if ($r_exitcode == 0) {
			fwrite ($fd, $prg . '<pre>' . $cont . '</pre>');
	 	} else {
			fwrite ($fd, $prg . '<pre>' . $cont . '<span style="color:red">' . $err . '</span>' . '</pre>');
	 	}
		fclose ($fd);
	}
	return $rst;
}

function runRinShell ($cmd, $chmf, &$r_exitcode) {
   $stdout = "";
   if (security>1) {
     $msg = shell_exec (sudo . $cmd);
     if ($chmf!='') {
       $cmd = sudo . chmod . $chmf;
       exec ($cmd, $stdout, $r_exitcode);
#      error ('R', $cmd, $msg);
     }
   } else {
     exec ($cmd, $stdout, $r_exitcode);
   }

// Alex, got error message here if no output
   if (is_array($stdout)) { 
     foreach($stdout as $row) $msg .= $row . "\n";
   }
   if (is_string($stdout)) {
     $msg .= $row . "\n";
   }

#   error ('R', $cmd, $msg);
   return ($msg);
}


function checkCommands ($input) {
	// Thanks to the R-php people  :) 
	$banned = array('.C', '.Call', '.Call.graphics', '.External', '.External.graphics',
        	'.Fortran', '.readRDS', '.saveRDS', '.Script', '.Tcl',
        	'.Tcl.args', '.Tcl.callback', '.Tk.ID', '.Tk.newwin', '.Tk.subwin',
        	'.Tkroot', '.Tkwin', 'basename', 'browseURL', 'bzfile',
        	'capture.output', 'close', 'close.screen', 'closeAllConnection', 'data.entry',
        	'data.restore', 'dataentry', 'de', 'dev.control', 'dev.copy2eps',
        	'dev.cur', 'dev.list', 'dev.next', 'dev.prev', 'dev.print',
        	'dev.set', 'dev2bitmap', 'dget', 'dir', 'dir.create',
        	'dirname', 'do.call', 'download.file', 'dput', 'dump',
        	'dyn.load', 'edit', 'edit.data.frame', 'emacs', 'erase.screen',
        	'example', 'fifo', 'file', 'file.access', 'file.append',
        	'file.choose', 'file.copy', 'file.create', 'file.exists', 'file.info',
        	'file.path', 'file.remove', 'file.rename', 'file.show', 'file.symlink',
        	'fix', 'getConnection', 'getwd', 'graphics.off', 'gzcon',
        	'gzfile', 'INSTALL', 'install.packages', 'jpeg', 'library.dynam',
        	'list.files','loadhistory', 'locator', 'lookup.xport', 'make.packages.html',
        	'make.socket', 'menu', 'open', 'parent.frame', 'path.expand',
        	'pico', 'pictex', 'pipe', 'png',
        	'postscript', 'print.socket', 'prompt', 'promptData', 'quartz',
        	'R.home', 'R.version', 'read.00Index', 'read.dta', 'read.epiinfo',
        	'read.fwf', 'read.mtp', 'read.socket', 'read.spss', 'read.ssd',
        	'read.xport', 'readBin', 'readline', 'readLines', 'remove.packages',
        	'Rprof', 'save', 'savehistory', 'scan', 'screen',
        	'seek', 'setwd', 'showConnection', 'sink', 'sink.number',
        	'socketConnection', 'source', 'split.screen', 'stderr', 'stdin',
        	'stdout', 'sys.call', 'sys.calls', 'sys.frame', 'sys.frames',
        	'sys.function', 'Sys.getenv', 'Sys.getlocale', 'Sys.info', 'sys.nframe',
        	'sys.on.exit', 'sys.parent', 'sys.parents', 'Sys.putenv', 'Sys.sleep',
        	'Sys.source', 'sys.source', 'sys.status', 'Sys.time', 'system',
        	'system.file', 'tempfile', 'textConnection', 'tkpager', 'tkStartGUI',
        	'unlink', 'unz', 'update.packages', 'url', 'url.show',
        	'vi', 'write', 'write.dta', 'write.ftable', 'write.socket',
        	'write.table', 'writeBin', 'writeLines', 'x11', 
        	'xedit', 'xemacs', 'xfig', 'zip.file.extract',
        	'call', 'eval' );     # added by suggestion of M. Cassin 
	if (security==2) { # if you use security==2 then you should not allow to read data from foreigners 
		$banned[] = 'readdataSK';
	}
	# 'pdf',
	$n = count($banned);
	for ($i=0; $i<$n; $i++) {
		if (substr_count($input, $banned[$i])>0) {
			// okay, we found something forbidden, now we need a regular expression to check if it is a function call like 'name  (', 'name =' or 'name.' !
			$pattern = '/\b' . str_replace ('.', '\.', $banned[$i]) . '[\W]*[\(\=\.]+/';
			if (preg_match ($pattern, $input, $match) > 0) { return $banned[$i]; }
			#      preg_match ($pattern, $input, $match);
			#      error('Check', '', print_r($match, true));
		}
	}
	return '';
}

function error ($cmd, $msg, $input) {
  $txt = '<pre>ERROR: &lt;' . $cmd . '...&gt; ' . $msg . ' <em>in</em></pre><p>';
  $txt = $txt . '<pre>' . $input . '</pre>';
  die ($txt);
  return 0;
}

# The callback function for converting the input text to HTML output
function renderFilename ($input) {
  if (strncmp(PHP_OS, 'WIN', 3)==0) {
    return str_replace('/', '\\', $input);
  } 
  return ($input);
}

/* need some rework for windows, SK 9 Jul 06 */
function getCmd ($pre, $cmd, $post) {
  $path = array('/usr/bin/', '/usr/local/bin/', '/bin/');
  $n    = count(path);  
  for ($i = 0; $i < $n; $i++) {
    $cmdf = $path[$i] . $cmd;
    if (file_exists($cmdf)) { return ($pre . $cmdf . $post); }
  }
  $cmdf = `which $cmd`;
  if ($cmdf!='') {
    $cmdf = $pre . trim($cmdf) . $post;
    return $cmdf;
  }
  error ($cmd, 'command not found', '');
}


?>
