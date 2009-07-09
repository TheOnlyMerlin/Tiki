<?php

// $Id$

// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

//this script may only be included - so its better to err & die if called directly.
//smarty is not there - we need setup
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}
require_once('tiki-setup.php');  
global $prefs, $userlib;

$smarty->assign('mandatory_category', '-1');
if ($prefs['feature_categories'] == 'y' && isset($cat_type) && isset($cat_objid)) {
	global $categlib, $user; include_once ('lib/categories/categlib.php');
	$smarty->assign('cat_categorize', 'n');

	if (isset($_REQUEST["cat_categorize"]) && $_REQUEST["cat_categorize"] == 'on') {
		$smarty->assign('cat_categorize', 'y');
	}

	$cats = $categlib->get_object_categories($cat_type, $cat_objid);
	
	if ($prefs['feature_wikiapproval'] == 'y' && $prefs['wikiapproval_sync_categories'] == 'y' && !$cats
	 && $cat_type == 'wiki page' && substr($cat_objid, 0, strlen($prefs['wikiapproval_prefix'])) == $prefs['wikiapproval_prefix']
	 && !$tikilib->page_exists($cat_objid) ) {
	 	// to pre-populate categories of original page if this is the first creation of a staging page
		$approvedPageName = substr($cat_objid, strlen($prefs['wikiapproval_prefix']));
		$cats = $categlib->get_object_categories($cat_type, $approvedPageName);
		$cats = array_diff($cats,Array($prefs['wikiapproval_approved_category']));		
	}
	
	if ($cat_type == 'wiki page' || $cat_type == 'blog' || $cat_type == 'image gallery' || $cat_type == 'mypage') {
		$ext = ($cat_type == 'wiki page')? 'wiki':str_replace(' ', '_', $cat_type);
		$pref = 'feature_'.$ext.'_mandatory_category';
		if ($prefs[$pref] > 0) {
			$all_categories = $categlib->list_categs($prefs[$pref]);
		} else {
			$all_categories = $categlib->list_categs();
		}
		$smarty->assign('mandatory_category', $prefs[$pref]);
	} else
		$all_categories = $categlib->list_categs();
	$categories = array();
	for ($i = 0; $i < count($all_categories); $i++) {
		if ( $tikilib->user_has_perm_on_object($user,$all_categories[$i]['categId'],'category','tiki_p_view_categories')
			|| $tikilib->user_has_perm_on_object($user,$all_categories[$i]['categId'],'category','tiki_p_admin_categories')
		) {
			$categories[] = $all_categories[$i];
		}
	}

if (isset ($categories)) {
	$num_categories = count($categories);
}
else {
	$num_categories = 0;
};

	for ($i = 0; $i < $num_categories; $i++) {
		if (!empty($cats) && in_array($categories[$i]["categId"], $cats)) {
			$categories[$i]["incat"] = 'y';
		} else {
			$categories[$i]["incat"] = 'n';
		}
		if (isset($_REQUEST["cat_categories"]) && isset($_REQUEST["cat_categorize"]) && $_REQUEST["cat_categorize"] == 'on') {
			if (in_array($categories[$i]["categId"], $_REQUEST["cat_categories"])) {
				$categories[$i]["incat"] = 'y';
				// allow to preselect categories when creating a new article
				// like this: /tiki-edit_article.php?cat_categories[]=1&cat_categorize=on
				$smarty->assign('categ_checked', 'y');
			} else {
				$categories[$i]["incat"] = 'n';
			}
		}
	}

	include_once ('lib/tree/categ_picker_tree.php');
	$tree_nodes = array();
	foreach ($categories as $c) {
		if (isset($c['name']) || $c['parentId'] != 0) {
			$tree_nodes[] = array(
				'id' => $c['categId'],
				'parent' => $c['parentId'],
				'data' => '<span class="tips" title="'.$c['description'].'"><input type="checkbox" name="cat_categories[]" value="' . $c['categId'] . ($c['incat'] == 'y' ? '" checked="checked"' : '" ') . '/> ' . $c['name'] . '</span>'
			);
			if ($c['parentId'] == 0) {
				$tree_nodes[count($tree_nodes) - 1]['data'] = '<strong>'.$tree_nodes[count($tree_nodes) - 1]['data'].'</strong>';
			}
		}
	}
	$tm = new CatPickerTreeMaker("categorize");
	$res = $tm->make_tree(0, $tree_nodes);
	$smarty->assign('cat_tree', $res);
	
	if (!empty($cats))
		$smarty->assign('catsdump', implode(',',$cats));
	$smarty->assign_by_ref('categories', $categories);

	// check if this page is categorized
	if ($categlib->is_categorized($cat_type, $cat_objid)) {
		$cat_categorize = 'y';
	} else {
		$cat_categorize = 'n';
	}
	$smarty->assign('cat_categorize', $cat_categorize);
}
