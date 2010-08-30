<?php
// Parses R code (r-project.org) and shows the output in a wiki page.
// Corresponding author: Xavier de Pedro. <xavier.depedro (a) ub.edu> 
// Contributors: Rodrigo Sampaio, Lukáš Mašek, Louis-Philippe Huberdau, Sylvie Greverend
// Usage:
// {R(attId=>attId,type=>csv|xml)}R code{R} 
//
/* 

From the R Plugin for Mediawiki

(C) 2006- Sigbert Klinke (sigbert@wiwi.hu-berlin.de), Markus Cozowicz, Michael Cassin

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110, USA 

*/



function wikiplugin_r_info() {
	return array(
		'name' => tra('R syntax'),
		'documentation' => 'PluginR',
		'description' => tra('Parses R syntax and shows the output either from the code introduced between the plugin tags or from the file attached to a tracker item sent through PluginTracker. It can also be combined with Pretty Trackers to edit params from the script through web forms.'),
		'prefs' => array( 'wikiplugin_r' ),
		'validate' => 'all',
		'params' => array(
			'attId' => array(
				'required' => false,
				'safe' => true,
				'name' => tra('attId'),
				'description' => tra('AttId from a tracker Item attachment. ex: 1. (Optional)'),
				'filter' => 'int',
			),
			'type' => array(
				'required' => false,
				'safe' => true,
				'name' => tra('type'),
				'description' => tra('Choose the source file type in the appropriate mimetype syntax (Optional). Options: text/csv|text/xml. ex: text/csv. (default). For text/xml, you need to have installed library ("R4X") in R at the server. See documentation for more details'),
				'filter' => 'striptags',
			),
			'wikisyntax' => array(
				'required' => false,
				'safe' => true,
				'name' => tra('wikisyntax'),
				'description' => tra('Choose whether the output should be parsed as wiki syntax (Optional). Options: 0 (no parsing, default), 1 (parsing)'),
				'filter' => 'int',
			),
			'width' => array(
				'required' => false,
				'safe' => true,
				'name' => tra('width'),
				'description' => tra('Width of the graph (Optional). Options: an integer number in pixels (default) or in units specified. If ommitted but height is set, width will be proportional to keep aspect ratio'),
				'filter' => 'int',
			),
			'height' => array(
				'required' => false,
				'safe' => true,
				'name' => tra('height'),
				'description' => tra('Height of the graph (Optional). Options: an integer number in inches (default) or in units specified. If ommitted but width is set, height will be proportional to keep aspect ratio'),
				'filter' => 'int',
			),
			'units' => array(
				'required' => false,
				'safe' => true,
				'name' => tra('units'),
				'description' => tra('Choose units for the width and/or height parameters (Optional). Options: px (default) for pixels, in (inches), cm or mm'),
				'filter' => 'alpha',
			),
			'pointsize' => array(
				'required' => false,
				'safe' => true,
				'name' => tra('pointsize'),
				'description' => tra('The default pointsize of plotted text, interpreted as big points (1/72 inch) at res dpi (optional). Options: interger number such as 12 or bigger'),
				'filter' => 'int',
			),
			'bg' => array(
				'required' => false,
				'safe' => true,
				'name' => tra('bg'),
				'description' => tra('The initial background colour (optional). Options: white, yellow, grey, ... and transparent'),
				'filter' => 'striptags',
			),
			'res' => array(
				'required' => false,
				'safe' => true,
				'name' => tra('res'),
				'description' => tra('The nominal resolution in dpi which will be recorded in the bitmap file (if any). Also used for units other than the default, and to convert points to pixels (Optional). Options: a positive integer (default: 72 dpi). Values higher than 150 usually seem to be too much'),
				'filter' => 'int',
			),
			'x11' => array(
				'required' => false,
				'safe' => true,
				'name' => tra('x11'),
				'description' => tra('Choose whether the server can use X11 to produce graphs in R, or alternatively use dev2bitmap instead (Optional). Options: 1 (R has support for X11, default), 0 (no support for X11 thus using dev2bitmap). These capabilities can be checked in the server with the command in the R console: capabilities()'),
				'filter' => 'int',
			),
			'wrap' => array(
				'required' => false,
				'name' => tra('Word Wrap'),
				'description' => tra('Enable word wrapping on the code to avoid breaking the layout.'),
				'options' => array(
					array('text' => tra('No'), 'value' => '0'),
					array('text' => tra('Yes'), 'value' => '1'),
				),
			),
/*			'iframe' => array(
				'required' => false,
				'name' => tra('iframe'),
				'description' => tra('Show output on an html page inside the wiki page. ex: 1. (default)'),
			),
*/			'security' => array(
				'required' => false,
				'safe' => false,
				'name' => tra('security'),
				'description' => tra('Set the security level for the R commands allowed by the plugin. ex: 1. (default), 0 for no security checking.'),
				'filter' => 'int',
			),
		),
	);
}

function wikiplugin_r($data, $params) {
	global $smarty, $trklib, $tikilib;

	if (isset($params["security"]) && $params["security"]==0) {
		/* do nothing: i.e. don't check for security in the command sent to R*/
	}else{ 		/* default: check for security in the commands sent to R*/
		$rejected = checkCommands( $data );
		if( count($rejected) > 0 ) {
			$str = tra('Blocked commands found: ') . implode(', ', $rejected);
			return "^$str^";
		}
	}

	$output = 'text';
	$style = '';
	$ws = '';
	$sha1 = md5($data . $params . $output . $style);

	if (isset($_REQUEST['itemId'])) {
		global $trklib; require_once('lib/trackers/trackerlib.php');
		$atts = $trklib->list_item_attachments($_REQUEST['itemId'], 0, -1, 'comment_asc', '');
		if (!empty($atts['data'][0]['attId'])) {
			$params['attId'] = $atts['data'][0]['attId'];
		}
	}

	if(isset($params["attId"])) {
		global $trklib; require_once('lib/trackers/trackerlib.php');

		$info = $trklib->get_item_attachment($params["attId"]);

		if( $info['data'] ) {
			$filepath = tempnam( '/tmp', 'r' );
			file_put_contents( $filepath, $info['data'] );
		} else {
			$filepath = $info['path'];
		}

		if ( empty($info['filetype']) || $info['filetype'] == 'application/x-octetstream' || $info['filetype'] == 'application/octet-stream' ) {
			include_once('lib/mime/mimelib.php');
			$info['filetype'] = tiki_get_mime($info['filename'], 'application/octet-stream');
		}

		$type = $info["filetype"];			
		$file = $info["filename"];
	}

	if (isset($params["type"])) {
		$type = $params["type"];
	}

	defined('r_dir') || define('r_dir', getcwd() . DIRECTORY_SEPARATOR . 'temp/cache' );
	defined('r_ext') || define('r_ext', getcwd() . DIRECTORY_SEPARATOR . 'lib/r' );
	defined('security')  || define('security',  1);
	defined('sudouser')  || define('sudouser', 'rd');

	defined('convert')   || define('convert',   getCmd('', 'convert', ''));
	defined('sudo')      || define('sudo',      getCmd('', 'sudo', ' -u ' . sudouser . ' '));
	defined('chmod')     || define('chmod',     getCmd('', 'chmod', ' 664 '));
	defined('r_cmd')     || define('r_cmd',     getCmd('', 'R', ' --vanilla --quiet'));

	defined('graph_dir') || define('graph_dir', '.' . DIRECTORY_SEPARATOR . 'temp/cache' );
	defined('graph_file_name')  || define('graph_file_name', $sha1 . '.png');

	if ($type == "text/csv") {
		$path = $_SERVER["SCRIPT_NAME"];
		$data = "data <- read.csv(\"$filepath\")\n$data";
	}

	if ($type == "text/xml") {
		$path = $_SERVER["SCRIPT_NAME"];
		$data = "library(XML)\ndata_file <- xml(\"$filepath\")\ndata <- xmlTreeParse(data_file,  getDTD = F )\n$data";
	}
	// execute R program
	$fn   = runR ($output, $convert, $sha1, $data, $echo, $ws, $params);

	$ret = file_get_contents ($fn);

	if (isset($params["wikisyntax"]) && $params["wikisyntax"]==1) {
		return $ret;
	}else{ 		// if wikisyntax != 1 : no parsing of any wiki syntax
		return '~np~'.$ret.'~/np~';
	}
}

function runR ($output, $convert, $sha1, $input, $echo, $ws, $params) {
	// Generate a graphics
	$prg = '';
	$err = "\n";
	$rws = r_dir . DIRECTORY_SEPARATOR;
	$rst  = r_dir . DIRECTORY_SEPARATOR . $sha1 . '.html';
	$rgo  = r_dir . DIRECTORY_SEPARATOR . $sha1 . '.png';
	$rgo_rel  = graph_dir . DIRECTORY_SEPARATOR . $sha1 . '.png';

	if (isset($params["width"])) {
		$width = $params["width"];
	}else{ 	
		$width = "";
	}

	if (isset($params["height"])) {
		$height = $params["height"];
	}else{ 	
		$height = "";
	}

	if (isset($params["units"])) {
		$units = $params["units"];
	}else{
		$units = "px";
	}

	if (isset($params["pointsize"])) {
		$pointsize = $params["pointsize"];
	}else{ 	
		$pointsize = "";
	}

	if (isset($params["bg"])) {
		$bg = $params["bg"];
	}else{ 	
		$bg = "transparent";
	}

	if (isset($params["res"])) {
		$res = $params["res"];
	}else{ 		// if not specified, use 72 dpi, optimized for screen
		$res = 72;
	}

	if (isset($params["wrap"])) {
		$wrap = $params["wrap"];
	}else{ 		// if not specified, use wrapping to avoid breaking layout
		$wrap = 1;
	}
	if ( isset($wrap) && $wrap == 1 ) {
		// Force wrapping in <pre> tag through a CSS hack
		$pre_style = 'white-space:pre-wrap;'
			.' white-space:-moz-pre-wrap !important;'
			.' white-space:-pre-wrap;'
			.' white-space:-o-pre-wrap;'
			.' word-wrap:break-word;';
	} else {
		// If there is no wrapping, display a scrollbar (only if needed) to avoid truncating the text
		$pre_style = 'overflow:auto;';
	}
	

	if (!file_exists($rst) or onsave) {
		$content = '';
		$content .= 'rfiles<-"' . r_dir . '"' . "\n";
		// TODO: check R capabilities on this server and save result on "r_cap" file on disk
		// if file r_cap doesn't exist, check capabilities and save on disk
		// if capabilities()[[2]] == FALSE //use dev2bitmap
		// else //use png()
		//
		// Alternatively, request the user to use extra param x11=0 if no X11 on server.
		if ( (isset($params["X11"]) || isset($params["x11"])) && ($params["X11"]==0 || $params["x11"]==0) ) {
			$content .= $input . "\n";
//			$content = 'dev2bitmap("' . $rgo . '", type = "png16", res = 72, height = 7, width = 7)' . "\n";
			$content = 'dev2bitmap("' . $rgo . '" , width = ' . $width . ', height = ' . $height . ', units = "' . $units . '", pointsize = ' . $pointsize . ', res = ' . $res . ')' . "\n";
			$content .= 'dev.off()' . "\n";
		}else{	// png can be used because R was compiled with support for X11
			//	$content = 'png(filename = "' . $rgo . '", width = 600, height = 600, bg = "transparent", res = 72)' . "\n";
			$content = 'png(filename = "' . $rgo . '", width = ' . $width . ', height = ' . $height . ', units = "' . $units . '", pointsize = ' . $pointsize . ', bg = "' . $bg . '" , res = ' . $res . ')' . "\n";
			$content .= $input . "\n";
		}
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
			fwrite ($fd, $prg . '<pre style="'.$pre_style.'">' . $cont . '</pre>');
			if (file_exists($rgo)) {
				fwrite ($fd, $prg . '<img src="' . $rgo_rel . '" alt="' . $rgo_rel . '">');
		 	}
	 	} else {
			fwrite ($fd, $prg . '<pre>' . $cont . '<span style="color:red">' . $err . '</span>' . '</pre>');
	 	}
		fclose ($fd);
	}
	return $rst;
}

function runRinShell ($cmd, $chmf, &$r_exitcode) {
   $stdout = "";
/*   if (security>1) {
     $msg = shell_exec (sudo . $cmd);
     if ($chmf!='') {
       $cmd = sudo . chmod . $chmf;
       exec ($cmd, $stdout, $r_exitcode);
#      error ('R', $cmd, $msg);
     }
   } else { */
     exec ($cmd, $stdout, $r_exitcode);
//   }

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
        	'gzfile', 'INSTALL', 'install.packages', 'library.dynam',
        	'list.files','loadhistory', 'locator', 'lookup.xport', 'make.packages.html',
        	'make.socket', 'menu', 'open', 'parent.frame', 'path.expand',
        	'pico', 'pictex', 'pipe',
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
        	'readdataSK',
        	'png', 'jpeg',
        	'call', 'eval' );     # added by suggestion of M. Cassin 
	# 'pdf',
	$found = array();

	foreach( $banned as $b ) {
		if (false !== strpos($input, $b)) {
			// okay, we found something forbidden, now we need a regular expression to check if it is a function call like 'name  (', 'name =' or 'name.' !
			$pattern = '/\b' . preg_quote($b,'/') . '[\W]*[\(\=\.]+/';
			if (preg_match ($pattern, $input, $match)) {
				$found[] = $b;
			}
		}
	}

	return $found;
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
