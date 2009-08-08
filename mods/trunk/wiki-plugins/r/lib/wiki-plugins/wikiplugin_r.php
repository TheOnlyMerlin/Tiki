<?php
// Parses R code (r-project.org) and shows the output in a wiki page.
// Author: Xavier de Pedro. <xavier.depedro (a) ub.edu> 
// 	Rodrigo Sampaio <rodrigosprimo (a) gmail.com>
// Usage:
// {R(fileId=>fileId,attId=>attId,iframe=>1|0)}R code{R} 
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
	return tra("~np~{~/np~R(fileId=>fileId,attId=>attId,iframe=>1|0,security=>2|1|0)}R code{R} Parses R code (r-project.org) from between the plugin tags and shows the output in the wiki page. Data to analyse can be taken from file galleries by providing the fileId, or from tracker item attachments by the attId. Both fileId and attId are optional. iframe param show the output inside an iframe (default value, 1) or within the wiki page (0). Security levels are: 0 - all commands from R are possible; security is handled only by the validation step of the Tiki profile calls; 1 - a big list of comamnds are allowed, but not all; 2 - just a few commands are allowed. See the documentation. )");
}

function wikiplugin_r_info() {
	return array(
		'name' => tra('R syntax'),
		'documentation' => 'PluginR',
		'description' => tra('Parses R syntax and shows the output either from the code introduced between the plugin tags or from the file Id from an r script in a Tiki file gallery'),
		'prefs' => array( 'wikiplugin_r' ),
		'validate' => 'all',
		'params' => array(
			'fileId' => array(
				'required' => false,
				'name' => tra('fileId'),
				'description' => tra('File Id from a file gallery. Ex: 1. (Optional)'),
			),
			'attId' => array(
				'required' => false,
				'name' => tra('attId'),
				'description' => tra('AttId from a tracker Item attachment. ex: 1. (Optional)'),
			),
			'iframe' => array(
				'required' => false,
				'name' => tra('iframe'),
				'description' => tra('Show output on an html page inside the wiki page. ex: 1. (default)'),
			),
			'security' => array(
				'required' => yes,
				'name' => tra('security'),
				'description' => tra('Set the secutiry level for the R commands allowed by the plugin. Show output on an html page inside the wiki page. ex: 1. (default)'),
			),
		),
	);
}

function wikiplugin_r($data, $params) {
	global $smarty, $trklib, $tikilib;

	$output = 'text';
	$style = '';
	$ws = '';
	$sha1 = md5($data . $output . $style);

	extract($params);

	if (isset($fileId)) {
		$data = file_get_contents("tiki-download_file.php?fileId=$fileId&display");
	} else if (isset($attId)) {
		require_once("lib/trackers/trackerlib.php");
/* *** Mostly copy from tiki-download_item_attachment.php and modified *** */

		$info = $trklib->get_item_attachment($attId);
		$itemInfo = $trklib->get_tracker_item($info["itemId"]);
		$itemUser = $trklib->get_item_creator($itemInfo['trackerId'], $itemInfo['itemId']);

		if (isset($info['user']) && $info['user'] == $user) {
		} elseif (!empty($itemUser) && $user == $itemUser) {
		} elseif ((isset($itemInfo['status']) and $itemInfo['status'] == 'p' && !$tikilib->user_has_perm_on_object($user, $itemInfo['trackerId'], 'tracker', 'tiki_p_view_trackers_pending')) 
			||  (isset($itemInfo['status']) and $itemInfo['status'] == 'c' && !$tikilib->user_has_perm_on_object($user, $itemInfo['trackerId'], 'tracker', 'tiki_p_view_trackers_closed'))
			||  ($tiki_p_admin_trackers != 'y' && !$tikilib->user_has_perm_on_object($user, $itemInfo['trackerId'], 'tracker', 'tiki_p_view_trackers') )
		    ) {
				$data = tra('Permission denied');
		}

		if ( empty($info['filetype']) || $info['filetype'] == 'application/x-octetstream' || $info['filetype'] == 'application/octet-stream' ) {
			include_once('lib/mime/mimelib.php');
			$info['filetype'] = tiki_get_mime($info['filename'], 'application/octet-stream');
		}

		$type = &$info["filetype"];			
		$file = &$info["filename"];
		$content = &$info["data"];

		if ($info["path"]) {
			if (!file_exists($prefs['t_use_dir'].$info["path"])) {
				$str = sprintf(tra("Error : The file %s doesn't exist."), $_REQUEST["attId"]). tra("Please contact the website administrator.");
				$data = $str;
			} else {
				$data = readfile ($prefs['t_use_dir'] . $info["path"]);
			}
		} else {
			$data = $content;
		}
/* *** END of Mostly copy from tiki-download_item_attachment.php and modified *** */
	}




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

	if ($type == "text/csv") {
		$path = $_SERVER["SCRIPT_NAME"];
		$data = "read.csv(\"$path/tiki-download_item_attachment.php?attId=$attId&display\")";
	}

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
