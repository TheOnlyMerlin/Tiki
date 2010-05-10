<?php
/*
 * $Header: /cvsroot/tikiwiki/_mods/wiki-plugins/dopplr/wiki-plugins/wikiplugin_dopplr.php,v 1.2 2008/06/09 01:06:24 franck Exp $
 *
 * Dopplr plugin. Add a Dopplr badge 
 * 
 * Syntax:
 * 
 *  {DOPPLR(badgeid=>id)}
 *   some content
 *  {DOPPLR}
 * 
 */
function wikiplugin_dopplr_help() {
	return tra("Insert a dopplr.com badge on wiki page").":<br />~np~{DOPPLR(badgeid=>ID)}".tra("text")."{DOPPLR}~/np~";
}

function wikiplugin_dopplr($data, $params) {

	extract ($params,EXTR_SKIP);
	$badgeid    = (isset($badgeid) && ctype_xdigit($badgeid)) ? "$badgeid"  : "";

	$begin  = '<div id="dopplr-blog-badge-for-'.$badgeid.'"><script src="http://www.dopplr.com/blogbadge/script/'.$badgeid.'?div-id=dopplr-blog-badge-for-'.$badgeid.'"></script></div>';
	$data = "";
	$end = "";
	return $begin . $data . $end;
}
