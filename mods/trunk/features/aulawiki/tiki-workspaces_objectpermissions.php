<?php
/*
* @author: Javier Reyes Gomez (jreyes@escire.com)
* @date: 27/01/2006
* @copyright (C) 2006 Javier Reyes Gomez (eScire.com)
* @license http://www.gnu.org/copyleft/lgpl.html GNU/LGPL
* Based on:
* */
// $Header: /cvsroot/tikiwiki/_mods/features/aulawiki/tiki-workspaces_objectpermissions.php,v 1.3 2007-03-03 08:44:56 jreyesg Exp $
// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
include_once ("tiki-setup.php");
require_once ('lib/workspaces/workspacelib.php');

$workspacesLib = new WorkspaceLib($dbTiki);
$workspace = $workspacesLib->get_current_workspace();
$WorkspaceUserLib = new WorkspaceUserLib($dbTiki);
$can_admin_workspace =false;
$can_admin_all_workspaces =false;
$can_admin_resources= false;

if ( $tiki_p_admin == 'y' 
   || $tiki_p_admin_workspace =='y' ) {
   	$can_admin_all_workspaces = true;
}elseif($workspacesLib->user_can_admin_workspace_or_upper($user,$workspace)) {
	$can_admin_workspace = true;
}
if ($userlib->object_has_permission($user, $workspace["workspaceId"], 'workspace', "tiki_p_create_workspace_resour")) {
	$can_admin_resources =true;
}
   
if (!isset($_REQUEST["referer"])) {
	if (isset($_SERVER['HTTP_REFERER'])) {
		$_REQUEST["referer"] = $_SERVER['HTTP_REFERER'];
	}
}

if (isset($_REQUEST["referer"])) {
	$smarty->assign('referer', $_REQUEST["referer"]);
}

if (!isset(
	$_REQUEST["objectName"]) || !isset($_REQUEST["objectType"]) || !isset($_REQUEST["resourceIdName"]) || !isset($_REQUEST["permType"])) {
	$smarty->assign('msg', tra("Not enough information to display this page"));

	$smarty->display("error.tpl");
	die;
}

$_REQUEST["resourceIdName"] = urldecode($_REQUEST["resourceIdName"]);
$_REQUEST["objectType"] = urldecode($_REQUEST["objectType"]);
$_REQUEST["permType"] = urldecode($_REQUEST["permType"]);

$smarty->assign('objectName', $_REQUEST["objectName"]);
$smarty->assign('resourceIdName', $_REQUEST["resourceIdName"]);
$smarty->assign('objectType', $_REQUEST["objectType"]);
$smarty->assign('permType', $_REQUEST["permType"]);

global $userlib;
$admin_perm_name = "tiki_p_";
if($_REQUEST["objectType"]=="blog"){
	$admin_perm_name = "tiki_p_blog_admin";
}elseif($_REQUEST["objectType"]=="image gallery"){
	$admin_perm_name = "tiki_p_admin_galleries";
}elseif($_REQUEST["objectType"]=="file gallery"){
	$admin_perm_name = "tiki_p_admin_file_galleries";
}elseif($_REQUEST["objectType"]=="assignments"){
	$admin_perm_name = "aulawiki_p_admin_assignments";
}elseif($_REQUEST["objectType"]=="forum" ){
	$admin_perm_name = "tiki_p_admin_forum";
}elseif($_REQUEST["objectType"]=="calendar" ){
	$admin_perm_name = "tiki_p_admin_calendar";
}elseif($_REQUEST["objectType"]=="quiz"){
	$admin_perm_name = "tiki_p_admin_quizzes";
}elseif($_REQUEST["objectType"]=="wiki page"){
	$admin_perm_name = "tiki_p_admin_wiki";
}elseif($_REQUEST["objectType"]=="sheet"){
	$admin_perm_name = "tiki_p_admin_sheet";
##### pingus start  
//    'tiki_p_admin_workspace' has no ending "s" too
}elseif($_REQUEST["objectType"]=="workspace"){
	$admin_perm_name = "tiki_p_admin_workspace";
##### pingus end
}else{
	$admin_perm_name = "tiki_p_admin_".$_REQUEST["objectType"]."s";
} 

global $user;
##### pingus start: 
if (!$can_admin_workspace && !$can_admin_all_workspaces 
    && ($_REQUEST["objectType"]=="workspace")) {
    	$smarty->assign('msg', tra("Permission denied you cannot admin the workspace"));
    	$smarty->display("error.tpl");
        die;
	}
##### pingus end

// Process the form to assign a new permission to this page
if (isset($_REQUEST["assign"])) {
	check_ticket('object-perms');
	$userlib->assign_object_permission($_REQUEST["group"], $_REQUEST["resourceIdName"], $_REQUEST["objectType"], $_REQUEST["perm"]);
	$smarty->assign('groupName', $_REQUEST["group"]);
}

// Process the form to remove a permission from the page
if (isset($_REQUEST["action"])) {
	check_ticket('object-perms');
	if ($_REQUEST["action"] == 'remove') {
		$userlib->remove_object_permission($_REQUEST["group"], $_REQUEST["resourceIdName"], $_REQUEST["objectType"], $_REQUEST["perm"]);
	}
}

// Now we have to get the individual page permissions if any
$page_perms = $userlib->get_object_permissions($_REQUEST["resourceIdName"], $_REQUEST["objectType"]);
$smarty->assign_by_ref('page_perms', $page_perms);

// Get a list of groups
if ($can_admin_all_workspaces) {  # he can choose among all site groups
	$groups = $workspacesLib->get_child_workspaces_groups("0", $includeParent = FALSE);
}elseif ($can_admin_workspace) {  # he can choose among all groups from topmost-he_admins to bottom
	if ($topmost_workspace_Iadmin=$workspacesLib->get_topmost_workspace_Iadmin($user,$workspace)){
               if($workspace["workspaceId"]=$topmost_workspace_Iadmin) {
                    $topmost_workspace_Iadmin=$workspace["parentId"];
                }          
		$groups = $workspacesLib->get_child_workspaces_groups($topmost_workspace_Iadmin, $includeParent = TRUE);
	}
}elseif ($can_admin_resources) {  
        # he can only choose among this ws group + included
	$groups = $workspacesLib->get_included_groups("WSGRP".$workspace["code"], TRUE);
        # he can only choose among those from-this-ws-level-to-bottom (this+brothers-to-bottom: father excluded)
//	$groups = $workspacesLib->get_child_workspaces_groups($workspace["parentId"], $includeParent = FALSE);
	# OR he can only choose among those from-this-ws-level-to-bottom (this-to-bottom, this included)
//	$groups = $workspacesLib->get_child_workspaces_groups($workspace["workspaceId"], $includeParent = TRUE);
	# OR he can only choose among those from-father-level-to-bottom ( father-to-bottom, father+brothers included)
//	$groups = $workspacesLib->get_child_workspaces_groups($workspace["parentId"], $includeParent = TRUE);
	}

$groups[]="Anonymous";
$groups[]="Registered";
$smarty->assign_by_ref('groups', $groups);
                                                                                
// Get a list of permissions
$perms = $userlib->get_permissions(0, -1, 'permName_desc', '', $_REQUEST["permType"]);
$smarty->assign_by_ref('perms', $perms["data"]);

ask_ticket('object-perms');

$smarty->assign('mid', 'tiki-workspaces_objectpermissions.tpl');
$smarty->display("tiki.tpl");
