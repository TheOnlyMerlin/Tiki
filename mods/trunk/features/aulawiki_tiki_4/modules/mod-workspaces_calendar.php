<?php

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
	header("location: index.php");
	exit;
}
$workspacesLib = new WorkspaceLib($dbTiki);

$workspace = $workspacesLib->get_current_workspace();

if (!isset ($workspace)) {
	$smarty->assign('error_msg', tra("Workspace not selected"));
	$exit_module = true;
}else{
    if (isset($_SESSION["currentWorkspace"])){
	    global $calendarlib;
	  	include_once ('lib/calendar/calendarlib.php'); 
	  	require_once ('lib/workspaces/resourceslib.php'); 
	  	$viewstart = TikiLib::make_time(0,0,0, $mon, 1, $year);
	  	$viewend = TikiLib::make_time(0,0,0,$mon + 1, 1, $year);
    	/*$calendarData = $calendarlib->list_calendars(0, -1, 'created_desc', "WSCALENDAR".$_SESSION["currentWorkspace"]["code"]);
		$calendarId = '0';
		foreach ($calendarData['data'] as $key => $val) {
			$calendarId = $key;
		}*/
		$resourcesLib = new WorkspaceResourcesLib($dbTiki);
		$calendars = $resourcesLib->get_category_objects($_SESSION["currentWorkspace"]["categoryId"],null,"calendar");

		$calendarIds = array();
		$calIds="";
		foreach ($calendars as $key => $val) {
			$module_params['calIds'][] = $val["objId"];
		}
    }
$module_params['title'] = tra('Workspace Calendar');
include ("modules/mod-calendar_new.php");
}
