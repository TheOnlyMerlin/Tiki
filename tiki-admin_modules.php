<?php
// $Id: /cvsroot/tikiwiki/tiki/tiki-admin_modules.php,v 1.52.2.1 2007-11-25 21:42:35 sylvieg Exp $
// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
$section = 'admin';
require_once ('tiki-setup.php');
include_once ('lib/menubuilder/menulib.php');
include_once ('lib/rss/rsslib.php');
include_once ('lib/polls/polllib.php');
include_once ('lib/banners/bannerlib.php');
include_once ('lib/dcs/dcslib.php');
include_once ('lib/modules/modlib.php');
include_once ('lib/structures/structlib.php');
if (!isset($dcslib)) {
    $dcslib = new DCSLib($dbTiki);
}
if (!isset($bannerlib)) {
    $bannerlib = new BannerLib($dbTiki);
}
if (!isset($rsslib)) {
    $rsslib = new RssLib($dbTiki);
}
if (!isset($polllib)) {
    $polllib = new PollLib($dbTiki);
}
if (!isset($structlib)) {
    $structlib = new StructLib($dbTiki);
}
$smarty->assign('wysiwyg', 'n');
if (isset($_REQUEST['wysiwyg']) && $_REQUEST['wysiwyg'] == 'y') {
    $smarty->assign('wysiwyg', 'y');
}
// PERMISSIONS: NEEDS p_admin
if ($tiki_p_admin != 'y') {
    $smarty->assign('errortype', 401);
    $smarty->assign('msg', tra('You do not have permission to use this feature'));
    $smarty->display('error.tpl');
    die;
}
$auto_query_args = array();

// Values for the user_module edit/create form
$smarty->assign('um_name', '');
$smarty->assign('um_title', '');
$smarty->assign('um_data', '');
$smarty->assign('um_parse', '');
$smarty->assign('assign_name', '');
//$smarty->assign('assign_title','');
$smarty->assign('assign_position', '');
$smarty->assign('assign_order', '');
$smarty->assign('assign_cache', 0);
$smarty->assign('assign_rows', 10);
$smarty->assign('assign_params', '');
if (isset($_REQUEST["clear_cache"])) {
    check_ticket('admin-modules');
    $modlib->clear_cache();
}
$module_groups = array();
$smarty->assign('assign_selected', '');
$smarty->assign('assign_type', '');
$smarty->assign('assign_title', '');
if (!empty($_REQUEST['edit_assign'])) {
    check_ticket('admin-modules');
    $info = $modlib->get_assigned_module($_REQUEST['edit_assign']);
    $grps = '';
    if ($info["groups"]) {
        $module_groups = unserialize($info["groups"]);
        foreach($module_groups as $amodule) {
            $grps = $grps . ' $amodule ';
        }
    }
    $smarty->assign('module_groups', $grps);
    if (isset($info["ord"])) {
        $cosa = "" . $info["ord"];
    } else {
        $cosa = "";
    }
    $smarty->assign_by_ref('assign_name', $info["name"]);
    //$smarty->assign_by_ref('assign_title',$info["title"]);
    $smarty->assign_by_ref('assign_position', $info["position"]);
    $smarty->assign_by_ref('assign_cache', $info["cache_time"]);
    $smarty->assign_by_ref('assign_type', $info["type"]);
    $smarty->assign_by_ref('assign_order', $cosa);
    $smarty->assign_by_ref('info', $info);
    if (!$info['name']) {
        $smarty->assign('assign_selected', $_REQUEST['edit_assign']);
    }

	$modinfo = $modlib->get_module_info( $info['name'] );
	$modlib->dispatchValues( $info['params'], $modinfo['params'] );
	if ($modinfo["type"] != "function") {
		$smarty->assign_by_ref('assign_rows', $info["rows"]);
		$smarty->assign_by_ref('assign_params', $info["params"]); // For old-style modules
	} else {
		if (isset($modinfo['params']['rows']))
			$modinfo['params']['rows']['value'] = $info["rows"];
	}
	$smarty->assign('assign_info', $modinfo);
}
if (!empty($_REQUEST['unassign'])) {
    check_ticket('admin-modules');
    $info = $modlib->get_assigned_module($_REQUEST['unassign']);
    if ($prefs['feature_ticketlib2'] != 'y' or (isset($_POST['daconfirm']) and isset($_SESSION["ticket_$area"]))) {
        $modlib->unassign_module($_REQUEST['unassign']);
        $logslib->add_log('adminmodules', 'unassigned module ' . $info['name']);
    } else {
        key_get($area, tra('Unassign module:') . ' ' . $info['name']);
    }
}
if (!empty($_REQUEST['modup'])) {
    check_ticket('admin-modules');
    $modlib->module_up($_REQUEST['modup']);
}
if (!empty($_REQUEST['moddown'])) {
    check_ticket('admin-modules');
    $modlib->module_down($_REQUEST['moddown']);
}
if (!empty($_REQUEST['modleft'])) {
    check_ticket('admin-modules');
    $modlib->module_left($_REQUEST['modleft']);
}
if (!empty($_REQUEST['modright'])) {
    check_ticket('admin-modules');
    $modlib->module_right($_REQUEST['modright']);
}
/* Edit or delete a user module */
if (isset($_REQUEST["um_update"])) {
    if (empty($_REQUEST["um_name"])) {
        $smarty->assign('msg', tra("Cannot create or update module: You need to specify a name to the module"));
        $smarty->display("error.tpl");
        die;
    }
    if (empty($_REQUEST["um_data"])) {
        $smarty->assign('msg', tra("Cannot create or update module: You cannot leave the data field empty"));
        $smarty->display("error.tpl");
        die;
    }
    check_ticket('admin-modules');
    $_REQUEST["um_update"] = urldecode($_REQUEST["um_update"]);
    $smarty->assign_by_ref('um_name', $_REQUEST["um_name"]);
    $smarty->assign_by_ref('um_title', $_REQUEST["um_title"]);
    $smarty->assign_by_ref('um_data', $_REQUEST["um_data"]);
    $smarty->assign_by_ref('um_parse', $_REQUEST["um_parse"]);
    $modlib->replace_user_module(preg_replace("/\W/", "_", $_REQUEST["um_name"]) , $_REQUEST["um_title"], $_REQUEST["um_data"], $_REQUEST["um_parse"]);
    $logslib->add_log('adminmodules', 'changed user module ' . $_REQUEST["um_name"]);
}
if (!isset($_REQUEST["groups"])) {
    $_REQUEST["groups"] = array();
}
if (isset($_REQUEST["assign"]) || isset($_REQUEST["preview"])) { // Verify that required parameters are present
	$missing_params = array();
	$modinfo = $modlib->get_module_info( $_REQUEST['assign_name'] );
	foreach($modinfo["params"] as $pname => $param) {
		if ($param["required"] && empty($_REQUEST["assign_params"][$pname]))
			$missing_params[] = $param["name"];
	}
	$smarty->assign('missing_params', $missing_params);
}
$smarty->assign('preview', 'n');
if (isset($_REQUEST["preview"])) {
    check_ticket('admin-modules');
    $smarty->assign('preview', 'y');
    $smarty->assign_by_ref('assign_name', $_REQUEST["assign_name"]);
    if (!is_array($_REQUEST["assign_params"])) {
        TikiLib::parse_str($_REQUEST["assign_params"], $module_params);
    } else {
        $module_params=$_REQUEST["assign_params"];
    }
    $smarty->assign_by_ref('module_params', $module_params);
	if (isset($module_params['title'])) {
		$smarty->assign('tpl_module_title', tra( $module_params['title'] ) );
	}

	if (isset($_REQUEST["assign_rows"])) {
		$module_rows = $_REQUEST["assign_rows"];
		$smarty->assign_by_ref('assign_rows', $_REQUEST["assign_rows"]);
	} elseif (isset($_REQUEST["assign_params"]["rows"]))
		$module_rows = $_REQUEST["assign_params"]["rows"];
	else
		$module_rows = 10;

    if ($tikilib->is_user_module($_REQUEST["assign_name"])) {
        $info = $tikilib->get_user_module($_REQUEST["assign_name"]);
        $smarty->assign_by_ref('user_title', $info["title"]);
        if ($info["parse"] == "y") {
            $parse_data = $tikilib->parse_data($info["data"]);
            $smarty->assign_by_ref('user_data', $parse_data);
        } else {
            $smarty->assign_by_ref('user_data', $info["data"]);
        }
        $data = $smarty->fetch('modules/user_module.tpl');
    } else {
        $phpfile = 'modules/mod-' . $_REQUEST["assign_name"] . '.php';
        $phpfuncfile = 'modules/mod-func-' . $_REQUEST["assign_name"] . '.php';
        $template = 'modules/mod-' . $_REQUEST["assign_name"] . '.tpl';
        if (file_exists($phpfile)) {
            include ($phpfile);
        } elseif (file_exists($phpfuncfile)) {
			if (isset($_REQUEST["assign_params"]["rows"]))
				$module_rows = $_REQUEST["assign_params"]["rows"];
			else
				$module_rows = 10;
            include_once ($phpfuncfile);
            $function = 'module_' . $_REQUEST["assign_name"];
            if( function_exists( $function ) ) {
                $function( array("name" => $_REQUEST["assign_name"], "position" => $_REQUEST["assign_position"], "ord" => $_REQUEST["assign_order"], "cache_time" => $_REQUEST["assign_cache"], "rows" => $module_rows), $_REQUEST["assign_params"] ); // Warning: First argument should have all tiki_modules table fields. This is just a best effort.
            }
        }

        if (file_exists('templates/' . $template)) {
            $data = $smarty->fetch($template);
        } else {
            $data = '';
        }
    }
    if (!empty($_REQUEST['moduleId'])) {
        $smarty->assign_by_ref('moduleId', $_REQUEST['moduleId']);
    }
    $smarty->assign_by_ref('assign_name', $_REQUEST["assign_name"]);
    $smarty->assign_by_ref('assign_params', $_REQUEST["assign_params"]);
    $smarty->assign_by_ref('assign_position', $_REQUEST["assign_position"]);
    $smarty->assign_by_ref('assign_order', $_REQUEST["assign_order"]);
    $smarty->assign_by_ref('assign_cache', $_REQUEST["assign_cache"]);
    $module_groups = $_REQUEST["groups"];
    $grps = '';
    foreach($module_groups as $amodule) {
        $grps = $grps . " $amodule ";
    }
    $smarty->assign('module_groups', $grps);
    $smarty->assign_by_ref('preview_data', $data);

	$modlib->dispatchValues( $_REQUEST['assign_params'], $modinfo['params'] );
	$smarty->assign( 'assign_info', $modinfo );
}
if (isset($_REQUEST["assign"])) {
    check_ticket('admin-modules');
    $_REQUEST["assign"] = urldecode($_REQUEST["assign"]);
    $smarty->assign_by_ref('assign_name', $_REQUEST["assign_name"]);
    $smarty->assign_by_ref('assign_position', $_REQUEST["assign_position"]);
    $smarty->assign_by_ref('assign_params', $_REQUEST["assign_params"]);
    $smarty->assign_by_ref('assign_order', $_REQUEST["assign_order"]);
    $smarty->assign_by_ref('assign_cache', $_REQUEST["assign_cache"]);

	if (isset($_REQUEST["assign_rows"])) {
		$module_rows = $_REQUEST["assign_rows"];
		$smarty->assign_by_ref('assign_rows', $_REQUEST["assign_rows"]);
	} elseif (isset($_REQUEST["assign_params"]["rows"])) {
		$module_rows = $_REQUEST["assign_params"]["rows"];
		unset($_REQUEST["assign_params"]["rows"]); // hack, since rows goes in its own DB field
	} else
		$module_rows = 10;
    $smarty->assign_by_ref('assign_type', $_REQUEST["assign_type"]);
    $module_groups = $_REQUEST["groups"];
    $grps = '';
    foreach($module_groups as $amodule) {
        $grps = $grps . " $amodule ";
    }
    $smarty->assign('module_groups', $grps);
	if (empty($missing_params)) {
		$modlib->assign_module(isset($_REQUEST['moduleId']) ? $_REQUEST['moduleId'] : 0, $_REQUEST["assign_name"], '', $_REQUEST["assign_position"], $_REQUEST["assign_order"], $_REQUEST["assign_cache"], $module_rows, serialize($module_groups) , $_REQUEST["assign_params"], $_REQUEST["assign_type"]);
		$logslib->add_log('adminmodules', 'assigned module ' . $_REQUEST["assign_name"]);
		header("location: tiki-admin_modules.php");
	} else {
		$modlib->dispatchValues( $_REQUEST['assign_params'], $modinfo['params'] );
		$smarty->assign( 'assign_info', $modinfo );
	}
}

if (isset($_REQUEST["um_remove"])) {
    check_ticket('admin-modules');
    $_REQUEST["um_remove"] = urldecode($_REQUEST["um_remove"]);
    $modlib->remove_user_module($_REQUEST["um_remove"]);
    $logslib->add_log('adminmodules', 'removed user module ' . $_REQUEST["um_remove"]);
}
if (isset($_REQUEST["um_edit"])) {
    check_ticket('admin-modules');
    $_REQUEST["um_edit"] = urldecode($_REQUEST["um_edit"]);
    $um_info = $tikilib->get_user_module($_REQUEST["um_edit"]);
    $smarty->assign_by_ref('um_name', $um_info["name"]);
    $smarty->assign_by_ref('um_title', $um_info["title"]);
    $smarty->assign_by_ref('um_data', $um_info["data"]);
    $smarty->assign_by_ref('um_parse', $um_info["parse"]);
}
$user_modules = $modlib->list_user_modules();
$smarty->assign_by_ref('user_modules', $user_modules["data"]);
$all_modules = $modlib->get_all_modules();
sort($all_modules);
$smarty->assign_by_ref('all_modules', $all_modules);
$all_modules_info = array_combine( 
	$all_modules, 
	array_map( array( $modlib, 'get_module_info' ), $all_modules ) 
) ;
asort($all_modules_info);
$smarty->assign( 'all_modules_info', $all_modules_info);
$orders = array();
for ($i = 1;$i < 50;$i++) {
    $orders[] = $i;
}
$smarty->assign_by_ref('orders', $orders);
$groups = $userlib->list_all_groups();
$allgroups = array();
$temp_max = count($groups);
for ($i = 0;$i < $temp_max;$i++) {
    if (in_array($groups[$i], $module_groups)) {
        $allgroups[$i]["groupName"] = $groups[$i];
        $allgroups[$i]["selected"] = 'y';
    } else {
        $allgroups[$i]["groupName"] = $groups[$i];
        $allgroups[$i]["selected"] = 'n';
    }
}
$smarty->assign("groups", $allgroups);
$galleries = $tikilib->list_galleries(0, -1, 'lastModif_desc', $user, '');
$smarty->assign('galleries', $galleries["data"]);
$polls = $polllib->list_active_polls(0, -1, 'publishDate_desc', '');
$smarty->assign('polls', $polls["data"]);
$contents = $dcslib->list_content(0, -1, 'contentId_desc', '');
$smarty->assign('contents', $contents["data"]);
$rsss = $rsslib->list_rss_modules(0, -1, 'name_desc', '');
$smarty->assign('rsss', $rsss["data"]);
$menus = $menulib->list_menus(0, -1, 'menuId_desc', '');
$smarty->assign('menus', $menus["data"]);
$banners = $bannerlib->list_zones();
$smarty->assign('banners', $banners["data"]);
$wikistructures = $structlib->list_structures('0', '100', 'pageName_asc', '');
$smarty->assign('wikistructures', $wikistructures["data"]);
$left = $tikilib->get_assigned_modules('l');
$right = $tikilib->get_assigned_modules('r');
$smarty->assign_by_ref('left', $left);
$smarty->assign_by_ref('right', $right);
$sameurl_elements = array(
    'offset',
    'sort_mode',
    'where',
    'find'
);
ask_ticket('admin-modules');
// disallow robots to index page:
$smarty->assign('metatag_robots', 'NOINDEX, NOFOLLOW');
$smarty->assign('mid', 'tiki-admin_modules.tpl');
$smarty->display("tiki.tpl");
