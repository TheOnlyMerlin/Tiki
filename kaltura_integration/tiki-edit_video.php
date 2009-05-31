<?php

// $Id: /cvsroot/tikiwiki/tiki/tiki-edit_video.php,v 1.22.2.1 2007-12-07 05:56:38 mose Exp $

// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once ('tiki-setup.php');

include_once ("lib/videogals/videogallib.php");

if ($prefs['feature_categories'] == 'y') {
	global $categlib;
	if (!is_object($categlib)) {
		include_once('lib/categories/categlib.php');
	}
}

if ($prefs['feature_galleries'] != 'y') {
	$smarty->assign('msg', tra("This feature is disabled").": feature_galleries");

	$smarty->display("error.tpl");
	die;
}

// Sanity anyone?
if (!$_REQUEST['edit'] or !$_REQUEST['galleryId']) {
	$smarty->assign('msg', tra("Invalid request to edit an video"));

	$smarty->display("error.tpl");
	die;
}

// Now check permissions to access this page
if ($tiki_p_upload_images != 'y') {
	$smarty->assign('errortype', 401);
	$smarty->assign('msg', tra("Permission denied you cannot edit videos"));

	$smarty->display("error.tpl");
	die;
}

$videoId=$_REQUEST['edit'];
$foo = parse_url($_SERVER["REQUEST_URI"]);
$foo1 = str_replace("tiki-edit_video", "tiki-browse_video", $foo["path"]);
$foo2 = str_replace("tiki-edit_video", "show_video", $foo["path"]);
$smarty->assign('url_browse', $tikilib->httpPrefix(). $foo1);
$smarty->assign('url_show', $tikilib->httpPrefix(). $foo2);

$gal_info = $videogallib->get_gallery($_REQUEST["galleryId"]);

if (!isset($_REQUEST['sort_mode'])) {
	$sort_mode = $gal_info['sortorder'].'_'.$gal_info['sortdirection'];
} else $sort_mode = $_REQUEST['sort_mode'];
$smarty->assign('sort_mode', $sort_mode);


if (isset($_REQUEST["editvideo"])) {
	check_ticket('edit-video');
	$smarty->assign('individual', 'n');

	if ($userlib->object_has_one_permission($_REQUEST["galleryId"], 'video gallery')) {
		$smarty->assign('individual', 'y');

		if ($tiki_p_admin != 'y') {
			// Now get all the permissions that are set for this type of permissions 'video gallery'
			$perms = $userlib->get_permissions(0, -1, 'permName_desc', '', 'video galleries');

			foreach ($perms["data"] as $perm) {
				$permName = $perm["permName"];

				if ($userlib->object_has_permission($user, $_REQUEST["galleryId"], 'video gallery', $permName)) {
					$$permName = 'y';

					$smarty->assign("$permName", 'y');
				} else {
					$$permName = 'n';

					$smarty->assign("$permName", 'n');
				}
			}
		}
	} elseif ($tiki_p_admin != 'y' && $prefs['feature_categories'] == 'y') {
		$perms_array = $categlib->get_object_categories_perms($user, 'video gallery', $_REQUEST['galleryId']);
   		if ($perms_array) {
   			$is_categorized = TRUE;
    			foreach ($perms_array as $perm => $value) {
    				$$perm = $value;
    			}
   		} else {
   			$is_categorized = FALSE;
   		}
		if ($is_categorized && isset($tiki_p_view_categorized) && $tiki_p_view_categorized != 'y') {
			$smarty->assign('errortype', 401);
			$smarty->assign('msg',tra("Permission denied you cannot view this page"));
			$smarty->display("error.tpl");
			die;
		}
	}

	if ($tiki_p_admin_galleries == 'y') {
		$tiki_p_view_image_gallery = 'y';

		$tiki_p_upload_images = 'y';
		$tiki_p_create_galleries = 'y';
	}

	if ($tiki_p_upload_images != 'y') {
		$smarty->assign('errortype', 401);
		$smarty->assign('msg', tra("Permission denied you cannot edit videos"));

		$smarty->display("error.tpl");
		die;
	}

	if ($gal_info["thumbSizeX"] == 0)
		$gal_info["thumbSizeX"] = 80;

	if ($gal_info["thumbSizeY"] == 0)
		$gal_info["thumbSizeY"] = 80;

	// Check the user to be admin or owner or the gallery is public
	if ($tiki_p_admin_galleries != 'y' && (!$user || $user != $gal_info["user"]) && $gal_info["public"] != 'y') {
		$smarty->assign('errortype', 401);
		$smarty->assign('msg', tra("Permission denied you can edit videos but not in this gallery"));

		$smarty->display("error.tpl");
		die;
	}

	$error_msg = '';

	if ($videogallib->edit_video($videoId, $_REQUEST['name'], $_REQUEST['description'],$_REQUEST['tags'])) {
		$smarty->assign('show', 'y');
		$cat_type = 'video';
		$cat_objid = $videoId;
		$cat_desc = $_REQUEST['description'];
		$cat_name = $_REQUEST['name'];
		$cat_href = "tiki-browse_video.php?videoId=".$cat_objid;
		include_once("categorize.php");

	} else {
		$smarty->assign('msg', tra("Failed to edit the video"));

		$smarty->display("error.tpl");
		die;
	}
}

$info = $videogallib->get_video_info($videoId);

//print_r($info);
$smarty->assign_by_ref('videoId', $videoId);
$smarty->assign_by_ref('entryId', $info['entryId']);
$smarty->assign_by_ref('galleryId', $info['galleryId']);
$smarty->assign_by_ref('name', $info['name']);
$smarty->assign_by_ref('description', $info['description']);
$smarty->assign_by_ref('tags', $info['tags']);
$smarty->assign_by_ref('gal_info', $gal_info);

$cat_type = 'video';
$cat_objid = $videoId;
include_once ("categorize_list.php");

ask_ticket('edit-video');

// disallow robots to index page:
$smarty->assign('metatag_robots', 'NOINDEX, NOFOLLOW');

// Display the template
$smarty->assign('mid', 'tiki-edit_video.tpl');
$smarty->display("tiki.tpl");

?>
