<?php

// $Header: /cvsroot/tikiwiki/tiki/freetag_list.php,v 1.5.2.4 2007-12-20 00:21:48 nkoth Exp $

// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

//this script may only be included - so its better to err & die if called directly.
//smarty is not there - we need setup
require_once('tiki-setup.php');  
$access->check_script($_SERVER["SCRIPT_NAME"],basename(__FILE__));

global $prefs;
global $tiki_p_view_freetags;

if ($prefs['feature_freetags'] == 'y' and $tiki_p_view_freetags == 'y') {

    global $freetaglib;
    if (!is_object($freetaglib)) {
	include_once('lib/freetag/freetaglib.php');
    }

    if (isset($cat_objid)) {

	$tags = $freetaglib->get_tags_on_object($cat_objid, $cat_type);	
	 
	$taglist = '';
	for ($i=0; $i<sizeof($tags['data']); $i++) {
	    $taglist .= $tags['data'][$i]['tag'] . ' ';
	}

    if ($prefs['feature_wikiapproval'] == 'y' && $prefs['wikiapproval_combine_freetags'] == 'y'
	 && $cat_type == 'wiki page' && substr($cat_objid, 0, strlen($prefs['wikiapproval_prefix'])) == $prefs['wikiapproval_prefix']) {
	 	// to combine tags from approved page 
		$approvedPageName = substr($cat_objid, strlen($prefs['wikiapproval_prefix']));
		$approvedTags = $freetaglib->get_tags_on_object($approvedPageName, $cat_type);
	 	for ($i=0; $i<sizeof($approvedTags['data']); $i++) {
	    	$taglist .= $approvedTags['data'][$i]['tag'] . ' ';
		}		
	}
	
	$smarty->assign('taglist',$taglist);
    } else {
	$taglist = '';
    }

    $suggestion = $freetaglib->get_tag_suggestion($taglist,10);

    $smarty->assign('tag_suggestion',$suggestion);
}

?>
