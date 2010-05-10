<?php

// 	Name:		JS-Kit Ratings plugin for TikiWiki CMS/Groupware. 
// 	Description:	Embed the Ratings service from JS-Kit (http://js-kit.com) into a wiki page. 
//	Author:		Rick Sapir, TikiWiki for Smarties (http://twbasics.keycontent.org)
//	License:		LGPL
//
//	Syntax:
// 	{JSKITRATING(view=XXXX,  star=XXXX,  user=XXXX, page=XXXX) /}
//
//	Where:
//		view = Type of rating:combo, split,  or score.  Default = combo
//		star = Color of the rating star.
//		user = Color of the user's rating star. Valid only when view = "combo" or "split".
//		page = Unique name to identify the page. If used, the page will have its own ratings. If omitted, the rating will be used on the entire site.
//
// 	Please see http://js-kit.com/ratings/ for examples of each option.
//


function wikiplugin_jskitrating_help() {
        return tra("Embed the Ratings service from JS-Kit (http://js-kit.com) into a wiki page").":<br />~np~{JSKITRATING(view=combo|split|score,star=color,user=color,page=unique_name) /}~/np~";
}

function wikiplugin_jskitrating($data, $params) {
	
	extract ($params,EXTR_SKIP);

// set defaults
	if (empty($view)) {
		$view = "combo";
	} 

	if (($view != "combo") && ($view !="split") && ($view != "score")){
	$view = "combo";
	}

	if ($star){
		$starcolor = " starColor=\"".$star."\"";
		} 

	if ($view == "split") {
		if ($user){
			$usercolor = " userColor=\"".$user."\"";
			} 

		}

	if (!empty($page)) {
		$permalink = " permalink=\"http://".$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]."\"";
		$path = " path=\"/".$page."\"";
		}

	$asetup = "<div class=\"js-kit-rating\" view=\"".$view."\"".$starcolor.$usercolor.$permalink.$path."></div><script src=\"http://js-kit.com/ratings.js\"></script>";
		
	return $asetup;
}
