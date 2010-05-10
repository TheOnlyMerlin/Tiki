<?php

/*
* @author: Javier Reyes Gomez (jreyes@escire.com)
* @date: 27/01/2006
* @copyright (C) 2006 Javier Reyes Gomez (eScire.com)
* @license http://www.gnu.org/copyleft/lgpl.html GNU/LGPL
*/
//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"], basename(__FILE__)) !== false) {
	header("location: index.php");
	exit;
}
require_once ('tiki-setup.php');
include_once ('lib/workspaces/workspacelib.php');
include_once ('lib/workspaces/typeslib.php');
include_once ('lib/workspaces/resourceslib.php');

global $dbTiki;
global $userlib;
global $feature_phplayers;

$workspacesLib = new WorkspaceLib($dbTiki);
$resourcesLib = new WorkspaceResourcesLib($dbTiki);
$wsresourcestypes=$resourcesLib->ws_object_types;
$wsTypesLib = new WorkspaceTypesLib($dbTiki);
if (isset ($_REQUEST["selectCategoryId"])) {
	$workspace=$workspacesLib->get_workspace_by_catid($_REQUEST["selectCategoryId"]);
	if (is_array($workspace)) {  # its a ws, not a category 
		$wstype = $wsTypesLib->get_workspace_type_by_id($workspace["type"]);
		$workspace["type"]=$wstype;
	}	
	else	{
		$workspace = $workspacesLib->get_current_workspace();
	}
	}
else	{
	$workspace = $workspacesLib->get_current_workspace();
	}
$can_admin_workspace=false;
$can_admin_all_workspaces=false;
$can_create_resources = false;
$can_add_users =false;
$can_delete_resources = false;
$can_assign_objectperms = false;

$exit_module=false;
# echo "CURRENT workspace : ". $workspace["code"]."<p>";
if (!isset ($workspace)) {
	$smarty->assign('error_msg', tra("Workspace not selected"));
	$exit_module = true;
}
global $tiki_p_admin;

if ($tiki_p_admin == "y" || $tiki_p_admin_workspace == "y" ) {
	$can_admin_all_workspaces = "y";
	$can_admin_workspace = "y";
	$can_create_resources = "y";
	$can_delete_resources = "y";
	$can_assign_objectperms = "y";
	
}
if (!$exit_module && $tiki_p_admin != 'y' && $tiki_p_admin_workspace != 'y') {
//	if (!$userlib->object_has_permission($user, $workspace["workspaceId"], 'workspace', "tiki_p_view_workspace")) {
	if (!$tikilib->user_has_perm_on_object($user, $workspace["workspaceId"], 'workspace', "tiki_p_view_workspace")) {
		$smarty->assign('error_msg', tra("Permission denied you cannot view this page"));
		$exit_module = true;
	}
}

//if ($userlib->object_has_permission($user, $workspace["workspaceId"], 'workspace', "tiki_p_create_workspace_resour")) {
if ($tikilib->user_has_perm_on_object($user, $workspace["workspaceId"], 'workspace', "tiki_p_create_workspace_resour")) {
	$can_create_resources = "y";
	$can_add_users = _TIKI_P_CREATE_WORKSPACE_RESOUR_CAN_ADD_USERS_ ; # he will be able to add only single users, not groups
	$can_delete_resources = "n";
	$can_assign_objectperms = "n";
	}
if ($workspacesLib->user_can_admin_workspace_or_upper($user,$workspace)){
	$can_create_resources = "y";
	$can_admin_workspace = "y";
	$can_add_users = "y"; 
	$can_delete_resources = "y";
	$can_assign_objectperms = "y";
}      

if (!$exit_module){
	if (!isset ($_REQUEST["objectCreated"]) 
	   && isset ($_REQUEST["createObject"]) && isset ($_REQUEST["createObjectCategoryId"])) {
		if( $can_admin_all_workspaces != 'y' 
	 	   && $can_admin_workspace != "y"
		   && $can_create_resources != "y") {
			$smarty->assign('error_msg', tra("Permission denied you cannot create new resources"));
		}elseif (!isset ($_REQUEST["createObjectName"])) {
			$smarty->assign('error_msg', tra("Name requiered"));
		} else {
			$wscode = $workspace["code"];
			$wsType = $workspace["type"];
# what Prefix on new objects ?
# original, top cat prefix 
			$id = $resourcesLib->create_object($wscode."-".$_REQUEST["createObjectName"], $_REQUEST["createObjectDesc"], $_REQUEST["createObjectType"], $_REQUEST["createObjectCategoryId"]);
# without prefix	$id = $resourcesLib->create_object($_REQUEST["createObjectName"], $_REQUEST["createObjectDesc"], $_REQUEST["createObjectType"], $_REQUEST["createObjectCategoryId"]);
			# if categories not featured, assign individual objectperms			
			if ($prefs["feature_categories"]!="y") {
				$workspacesLib->assign_permissions($wscode, $_REQUEST["createObjectType"], $id,$wsType);
			}
//  no immediately edit an object just created, so there's time to change its perms before
#			$resourcesLib->redirect($id, $wscode."-".$_REQUEST["createObjectName"], $_REQUEST["createObjectType"]);
			//$smarty->assign('error_msg', $tikilib->httpPrefix()."/tiki-index.php");
		}
	}
	elseif (isset ($_REQUEST["objectCreated"])) {
		$smarty->assign('error_msg', "Object created ".$_REQUEST["objectCreated"]);
	}
	
	global $user;
	global $dbTiki;
	global $categlib;
	include_once ('lib/categories/categlib.php');
	$categlib2 = new CategLib($dbTiki);
	$ctall = $categlib2->get_all_categories_respect_perms($user, 'tiki_p_view_category');
	
	if (isset ($module_params["type"])) {
		$type = $module_params["type"];
		$urlEnd = "&amp;type=".urlencode($type);
	} else {
		$type = '';
		$urlEnd = "";
	}
	if (isset ($module_params["deep"]))
		$deep = $module_params["deep"];
	else
		$deep = 'on';
	$urlEnd .= "&amp;deep=$deep";
	
	$categId = $workspace["categoryId"];
	if ($categId == 0)
		$name = tra("Top");
	else {
		$car = $categlib2->get_category($categId);
		$name = $car["name"];
	}
	
	$selectedCategory = $categId;
	if (isset ($_REQUEST["selectCategoryId"])) {
		$selectedCategory = $_REQUEST["selectCategoryId"];
	}
	//$categObjects = $categlib->get_category_objects($selectedCategory);
	$categObjects = $resourcesLib->get_category_objects($selectedCategory);
	$selectedWsObject=$resourcesLib->get_category_object($selectedCategory);
	foreach ($categObjects as $key => $categObject) {
		$categObjects[$key]["adminURL"] = $resourcesLib->get_url_admin($categObject["objId"], $categObject["name"], $categObject["type"]);
		$categObjects[$key]["removeURL"] = $resourcesLib->get_url_remove($categObject["objId"], $categObject["type"]);
	}

#### pingus start
	if ($can_admin_workspace == "y") {
                $smarty->assign('showAdminBar', 'y');
                $smarty->assign('showCreateBar', 'y');
                $smarty->assign('showAddUser', 'y');
		$smarty->assign('showButtons', 'y');
                $smarty->assign('showDeleteButton', 'y');
                $smarty->assign('showObjPerms', 'y');
	}elseif($can_create_resources == "y") {
                $smarty->assign('showAdminBar', 'n');
                $smarty->assign('showCreateBar', 'y');
		if ($can_delete_resources == "y") {
	                $smarty->assign('showDeleteButton', 'y');
			}
		if ($can_add_users== "y") {
			$smarty->assign('showAddUser', 'y');
			}
		if ($can_assign_objectperms== "y") {
			$smarty->assign('showObjPerms', 'y');
			}
		$smarty->assign('showButtons', 'y');
	}

	else{
                $smarty->assign('showAdminBar', 'n');
                $smarty->assign('showCreateBar', 'n');
                $smarty->assign('showAddUser', 'n');
		$smarty->assign('showButtons', 'n');
                $smarty->assign('showDeleteButton', 'n');
	}
		$smarty->assign('showCreationDate', 'n');
#### pingus end
	$smarty->assign('categObjects', $categObjects);
	$smarty->assign('workspaceId',$workspace["workspaceId"]) ;
	$smarty->assign('selectedWsObject', $selectedWsObject);

	$ownurl = $tikilib->httpPrefix().$_SERVER["REQUEST_URI"];
	include_once ('lib/tree/categ_browse_tree.php');
	$imgWiki = "<img align='bottom' border=0 src='img/icons/page.gif'>";
	$imgCateg = "<img border=0 valign='center' src='images/workspaces/edu_folder_closed.png'>";
	$tree_nodes = array ();
	$descendants = $categlib2->get_category_descendants($categId);
	$top = $categId;
	$todo = "";
	$selectedCategData = "";
	foreach ($ctall as $c) {
		if ($c["categId"] == $categId) {
			$top = $c["parentId"];
		}
		if ($c["categId"] == $categId || in_array($c["categId"], $descendants)) {
			if ($selectedCategory == $c["categId"]) {
				$smarty->assign('selectedCategory', $c);
				$selectedCategData = $c;
				$class = "categtreeActive";
			} else {
				$class = "categtree";
			}

//                        $ownurl=ereg_replace("&(.*)","",$ownurl); //clean url
//			$ownurl=$ownurl."&workspaceId=".$workspace["workspaceId"];
			$tree_nodes[] = array ("id" => $c["categId"], "parent" => $c["parentId"], "data" => '<a class="'.$class.'" href="'.$ownurl.'&selectCategoryId='.$c["categId"].'">'.$imgCateg.'&nbsp;'.$c["name"].'</a><br />');
//			$tree_nodes[] = array ("id" => $c["categId"], "parent" => $c["parentId"], "data" => '<a class="'.$class.'" href="'.$ownurl.'">'.$imgCateg.'&nbsp;'.$c["name"].'</a><br />');
		}
	
	}
	$tree_nodes[] = array ("id" => '999', "parent" => $top, "data" => '<br />');
	$tm = new CatBrowseTreeMaker("categ");
	$res = $tm->make_tree($top, $tree_nodes);
	$smarty->assign('tree', $res);
        $smarty->assign('types', $wsresourcestypes);
	
	
	$smarty->assign('ownurl', $ownurl);
	global $short_date_format;
}
