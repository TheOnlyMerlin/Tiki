<?php
/*
* @author: Javier Reyes Gomez (jreyes@escire.com)
* @date: 27/01/2006
* @copyright (C) 2006 Javier Reyes Gomez (eScire.com)
* @license http://www.gnu.org/copyleft/lgpl.html GNU/LGPL
*/

if (strpos($_SERVER["SCRIPT_NAME"], basename(__FILE__)) !== false) {
	header("location: index.php");
	exit;
}

include_once ('lib/workspaces/workspacelib.php');
include_once ('lib/workspaces/userlib.php');

global $dbTiki;
global $userlib;
global $tiki_p_admin;
$wsUserLib = new WorkspaceUserLib($dbTiki);
$workspacesLib = new WorkspaceLib($dbTiki);

$workspace = $workspacesLib->get_current_workspace();
$exit_module = false;
$can_add_groups=false;
$can_create_groups=false;
$can_add_users =false;
$can_admin_all_workspaces =false;

if ($userlib->object_has_permission($user, $workspace["workspaceId"], 'workspace', "tiki_p_create_workspace_resour")
	&& !$userlib->object_has_permission($user, $workspace["workspaceId"], 'workspace', "tiki_p_admin_workspace")) {
	$can_add_users =_TIKI_P_CREATE_WORKSPACE_RESOUR_CAN_ADD_USERS_;
	}
if (isset ($workspace)) {
	$groupName = $topgroupName = "WSGRP".$workspace["code"];
} else {
	$smarty->assign('error_msg', tra("Workspace not selected"));
	$exit_module = true;
	}
if ( $tiki_p_admin == 'y' || $tiki_p_admin_workspace =='y' ) {
	$can_admin_all_workspaces = true;
	$can_add_groups = true;
	$can_add_users =true;
	$can_create_groups = true;
    }
# a non-admin can: create groups only under level0 grp, add groups only to level1 grps
if ( $workspacesLib->user_can_admin_workspace_or_upper($user,$workspace)) {
	if ($topgroupName==$module_params["activeGroup"] ) {
 		$can_create_groups = true;
	}
	if ($topgroupName!=$module_params["activeGroup"] ) {
		$can_add_groups=true;
	} else {
		$can_add_groups=false;
	}
	$can_add_users =true;
}
if (!$can_add_users) {
	$smarty->assign('error_msg', tra("Permission denied, you cannot admin the workspace"));
	$exit_module = true;
}
	
if ($exit_module){
	$smarty->assign('activeGroup', $groupName);
}

$smarty->assign('can_admin_all_workspaces', $can_admin_all_workspaces);	
$smarty->assign('can_add_groups', $can_add_groups);	
$smarty->assign('can_add_users', $can_add_users);	
$smarty->assign('can_create_groups', $can_create_groups);	
if (!$exit_module){
	if(!isset($module_params["activeGroup"])){
		$module_params["activeGroup"] = $groupName;
		$module_params["activeParentGroup"] = "-1";
	}
	
	if (isset ($module_params["addGroupActiveName"]) && $can_add_groups) {
		if($module_params["addGroupName"]=="Anonymous" || $module_params["addGroupName"]=="Registered"){
		$smarty->assign('error_msg', tra("Anonymous and Registered group can't be added"));	
		}elseif ($userlib->group_exists($module_params["addGroupName"])) {
			if ($wsUserLib->group_can_include_group($module_params["addGroupActiveName"],$module_params["addGroupName"])) {
				$userlib->group_inclusion($module_params["addGroupName"], $module_params["addGroupActiveName"]);
			} else {
				$smarty->assign('error_msg', tra($module_params["addGroupActiveName"] ."can't  contain group ". $module_params["addGroupName"]));
			}			
		} else {
			$smarty->assign('error_msg', tra("group not found"));
		}
	}
	
	if (isset ($module_params["createGroupActiveName"]) && $can_create_groups) {
		if ($userlib->group_exists($groupName."-".$module_params["createGroupName"])) {
			$smarty->assign('error_msg', tra("group already exist"));
		} else {
			$userlib->add_group($groupName."-".$module_params["createGroupName"], $module_params["createGroupDesc"], '');
			$userlib->group_inclusion($groupName."-".$module_params["createGroupName"], $module_params["createGroupActiveName"]);
		}
	}
	
	if (isset ($module_params["createUserActiveGrpName"])) {
		if ($userlib->user_exists($module_params["createUserName"])) {
			$userlib->assign_user_to_group($module_params["createUserName"], $module_params["createUserActiveGrpName"]);
		} else {
			$smarty->assign('error_msg', tra("user not found"));
		}
	}
	
	if (isset ($module_params["removeGroupActiveName"]) && $can_add_groups) {
		if (!$userlib->group_exists($module_params["removeGroupActiveName"])){
			$smarty->assign('error_msg', tra("group not found"));
		}elseif($module_params["removeGroupActiveName"]=="WSGRP".$workspace["code"]){
			$smarty->assign('error_msg', tra("the main workspace group cant be removed"));
		}else{
			$wsUserLib->remove_inclusion($module_params["removeGroupActiveName"], $module_params["removeGroupActiveParentName"]);
			if (stristr($module_params["removeGroupActiveName"], $groupName)) {
				$userlib->remove_group($module_params["removeGroupActiveName"]);
			}
			$module_params["activeGroup"] = $groupName;
		}
	}
	
	if (isset ($module_params["removeUserGroupActiveName"])) {
		$result = $userlib->remove_user_from_group($module_params["removeUserName"], $module_params["removeUserGroupActiveName"]);
	}

	if ($can_admin_all_workspaces) {
#		should global 'tiki_p_admin_workspace' be able of adding *any* site group
#		   and objectperm 'tiki_p_admin_workspace' only 'workspaces groups' ?
#		OR should we make a difference here between the two perms?
#
#	   if (!$tiki_p_admin) {.....
#      		$allwsgroups = $workspacesLib->get_includable_child_workspaces_groups("0", $groupName, $includeParent = FALSE);
#		}
#		or *all* site groups (included 'admins` 'RolePerms' etc)? Dunno. I choose this.
#	   }else{...
		$allwsgroups = $userlib->list_can_include_groups($groupName);
#		array_shift ($allwsgroups); # take away Anonymous # mmmh, these may not always be at the start
#		array_shift ($allwsgroups); # take away Registered
		unset ($tmpallwsgroups);
		foreach ($allwsgroups as $k => $name) {
			if ($name!="Anonymous" && $name!="Registered" && $name!=""){
				$tmpallwsgroups[]=$allwsgroups[$k];  # no empty slots this way, smarty hates them
			}			
		}
		sort($tmpallwsgroups);
		reset ($tmpallwsgroups);
		$allwsgroups=$tmpallwsgroups;
		}
	else	{
		if ($topmost_workspace_Iadmin=$workspacesLib->get_topmost_workspace_Iadmin($user,$workspace)){
			$allwsgroups=$workspacesLib->get_includable_child_workspaces_groups($topmost_workspace_Iadmin, $groupName);
		}
	}
		$smarty->assign_by_ref('groups', $allwsgroups); 

	$wsgroups = $wsUserLib->get_descendant_groups($groupName, TRUE);
	$tree_nodes = array ();
	$imgGroup = "<img border=0 src='images/workspaces/edu_group.gif'>";
	$c1=0; 
	foreach ($wsgroups as $parentGroup => $childgroups) {
		$c1++; 
		foreach ($childgroups as $childGroup) {
			# only admin can add groups to subtop groups
			if ($c1==1 || $can_admin_all_workspaces) {
				$onclick = "onclick=\"document.getElementById('activeParentGroup').value='$parentGroup';document.getElementById('activeGroup').value='$childGroup';document['groupSelection'].submit();return false\"";
			# others will get a list of selected group users by clicking on faces icon,
			#  but this group will not become an addable group
			} else {
				$onclick = "onclick=\"document.getElementById('activeParentGroup').value='$parentGroup';document.getElementById('activeGroup2').value='$childGroup';document['groupSelection'].submit();return false\"";
 			}		
			$cssclass = "categtree";
			if ($module_params["activeGroup"] == $childGroup) {
				$cssclass = "categtreeActive";
			}
			if ($c1==1 || $can_admin_all_workspaces) {
				$tree_nodes[] = array ("id" => $childGroup, "parent" => $parentGroup, "data" => '<a href="#" class="'.$cssclass.'" '.$onclick.'>'.$imgGroup.'&nbsp;'.$childGroup.'</a><br />');
			} else {
				$tree_nodes[] = array ("id" => $childGroup, "parent" => $parentGroup, "data" => $childGroup.'<a href="#" class="'.$cssclass.'" '.$onclick.'>&nbsp;'.$imgGroup.'?</a>&nbsp;<br />');
			}
		}
	}
	$onclick = "onclick=\"document.getElementById('activeParentGroup').value='-1';document.getElementById('activeGroup').value='$groupName';document['groupSelection'].submit();return false\"";
	
	$cssclass = "categtree";
	if ($module_params["activeGroup"] == $groupName) {
		$cssclass = "categtreeActive";
	}
	$tree_nodes[] = array ("id" => $groupName, "parent" => "99999999", "data" => '<a class="'.$cssclass.'" href="#" '.$onclick.'>'.$imgGroup.'&nbsp;'.$groupName.'</a><br />');
	include_once ('lib/tree/categ_browse_tree.php');
	$tm = new CatBrowseTreeMaker("categ");
	$res = $tm->make_tree("99999999", $tree_nodes);
	$smarty->assign('groupsTree', $res);
	
	//Get users in selected group
	if ($module_params["activeGroup2"])
		$get_userdata_from=$module_params["activeGroup2"];
	else
		$get_userdata_from=$module_params["activeGroup"];
	$groupusers = $wsUserLib->get_group_usersdata($get_userdata_from);
	
	$smarty->assign('workspaceGroupName', $groupName);
	$smarty->assign('groupusers', $groupusers);
	$smarty->assign('activeGroup', $module_params["activeGroup"]);
	$smarty->assign('activeParentGroup', $module_params["activeParentGroup"]);
}

?>