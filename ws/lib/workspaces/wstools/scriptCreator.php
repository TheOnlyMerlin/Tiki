<?php
require_once('../../../tiki-setup.php');

/* 
 NOTE: before you run this you should create 5 wiki pages with the names "Wiki1",
 "Wiki2", "Wiki3", "Wiki4" and "Wiki5", and two groups called "G1" and "G2" (This can be
 done if the script is called by ".../lib/workspaces/wstools/scriptCreator.php?action=init).
 The next thing to do is to give to Wiki2 or Wiki3 the 
 tiki_p_view for group G1 or G2. 
 
 To create sample WS and assign sample groups and wiki pages to WS:
 .../lib/workspaces/wstools/scriptCreator.php?action=create
 
 To delete the sample WS:
 .../lib/workspaces/wstools/scriptCreator.php?action=destroy
 
 For better performance, it's recomended to enable Workspaces from Admin Features 
 before running.
 Only for evaluation purposes.
 */


include_once('lib/objectlib.php');
include_once('lib/userslib.php');
include_once('lib/tikilib.php');
include_once('lib/workspaces/wslib.php');



global $prefs, $tikilib;
$wsContainerId = (int) $prefs['ws_container'];
$user = 'Ben';

if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'init'))
{
	if (!$objectlib->get_object_id('wiki page','Wiki1'))
	{
		$objectlib->add_object('wiki page','Wiki1');
		$tikilib->create_page('Wiki1', 0, '', time(), '');
	}
	if (!$objectlib->get_object_id('wiki page','Wiki2'))
	{
		$objectlib->add_object('wiki page','Wiki2');
		$tikilib->create_page('Wiki2', 0, '', time(), '');
	}
	if (!$objectlib->get_object_id('wiki page','Wiki3'))
	{
		$objectlib->add_object('wiki page','Wiki3');
		$tikilib->create_page('Wiki3', 0, '', time(), '');
	}
	if (!$objectlib->get_object_id('wiki page','Wiki4'))
	{
		$objectlib->add_object('wiki page','Wiki4');
		$tikilib->create_page('Wiki4', 0, '', time(), '');
	}
	if (!$objectlib->get_object_id('wiki page','Wiki5'))
	{
		$objectlib->add_object('wiki page','Wiki5');
		$tikilib->create_page('Wiki5', 0, '', time(), '');
	}
	
	if ($userlib->add_group('G1'));
	if ($userlib->add_group('G2'));
	if ($userlib->add_user($user, '12345'))
	{
		$userlib->assign_user_to_group($user, 'G1');
		$userlib->assign_user_to_group($user, 'G2');
	}
	
}

if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'create') && ($wsContainerId))
{
	//Creating new WS
	if  (!($id1 = $wslib->get_ws_id('WS1',0)))
		$id1 = $wslib->create_ws ('WS1', 'G2', null, true ,array('tiki_p_ws_admingroups'));
	 if (!($id2 = $wslib->get_ws_id('WS2',0)))
		$id2 = $wslib->create_ws ('WS2', 'G2', null, true ,array('tiki_p_ws_adminresources'));
	if  (!($id3 = $wslib->get_ws_id('WS3',0)))
		$id3 = $wslib->create_ws ('WS3', 'G1', null, true ,array('tiki_p_ws_adminws'));

	//Creating new sub-WS under WS2
	if  (!($id4 = $wslib->get_ws_id('WS21',$id2)))
		$id4 = $wslib->create_ws ('WS21', 'G2', $id2, true);
	if  (!($id5 = $wslib->get_ws_id('WS22',$id2)))
		$id5 = $wslib->create_ws ('WS22', 'G2', $id2, true,array('tiki_p_ws_adminws'));
	
	// Giving access to G2 in WS3
	$wslib->set_permissions_for_group_in_ws($id3,'G2',array('tiki_p_ws_view','tiki_p_ws_addresource'));
	
	//Adding Resources in WS
	$wslib->add_ws_object($id1,'Wiki1','wiki_page');
	$wslib->add_ws_object($id2,'Wiki2','wiki_page');
	$wslib->add_ws_object($id3,'Wiki2','wiki_page');
	$wslib->add_ws_object($id3,'Wiki3','wiki_page');
	$wslib->add_ws_object($id4,'Wiki4','wiki_page');
	$wslib->add_ws_object($id5,'Wiki5','wiki_page');
	
	//Adding ObjectPerms in Wiki2 (for G1) and Wiki3 (for G2)
	$userlib->assign_object_permission('G1', 'Wiki2', 'wiki page', 'tiki_p_view');
	$userlib->assign_object_permission('G2', 'Wiki3', 'wiki page', 'tiki_p_view');
}
	
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'destroy') && ($wsContainerId))
{
	//Getting existing WS id
	$id1= $wslib->get_ws_id('WS1',0);
	$id2= $wslib->get_ws_id('WS2',0);
	$id3= $wslib->get_ws_id('WS3',0);
	$id5= $wslib->get_ws_id('WS22',$id2);
	
	//Removing WS
	$wslib->remove_ws($id1);
	$wslib->remove_ws($id5);
	$wslib->remove_ws($id3);
	$wslib->remove_ws($id2);
}

if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'apocalipsis') && ($wsContainerId))
{
	$wslib->remove_all_ws();
}

if ( isset($_REQUEST['action'])  &&  ($_REQUEST['action'] == 'test') && ($wsContainerId))
{
	$listWS = $wslib->list_all_ws(-1,-1,'name_asc',null,'wiki page','Wiki1');
	echo ("List all WS stored in Tiki");
	echo ("\n<br>");
	foreach ($listWS["data"] as $key)
	{
		echo ($key["categId"]);
		echo ("		");
		echo ($key["name"]);
		echo ("\n<br>");
	}
		
	echo ("\n<br>");
	echo ("List all groups that have access to WS3");
	echo ("\n<br>");
	$id = $wslib->get_ws_id('WS3',0);
	$listWSGroups = $wslib->list_groups_that_can_access_in_ws($id);
	foreach ($listWSGroups as $key)
	{
		echo ($key["groupName"]);
		echo ("\n<br>");
	}
	
	echo ("\n<br>");
	echo ("List all WS that group G2 have access");
	echo ("\n<br>");
	$listGroupWS = $wslib->list_ws_that_can_be_accessed_by_group ('G2');
	ksort($listGroupWS);
	foreach ($listGroupWS as $key)
	{
		echo ($key["categId"]);
		echo ("		");
		echo ($key["name"]);
		echo ("\n<br>");
	}
	
	echo ("\n<br>");
	echo ("List all WS that the cool user named Ben have access");
	echo ("\n<br>");
	$listUserWS = $wslib->list_ws_that_user_have_access($user);
	ksort($listUserWS);
	foreach ($listUserWS as $key)
	{
		echo ($key["categId"]);
		echo ("		");
		echo ($key["name"]);
		echo ("\n<br>");
	}
	
	echo ("\n<br>");
	echo ("List all objects stored in WS3");
	echo ("\n<br>");
	$listWSObjects = $wslib->list_ws_objects($id);
	foreach ($listWSObjects as $key)
	{
		echo ($key["objectId"]);
		echo ("     ");
		echo ($key["type"]);
		echo ("     ");
		echo ($key["name"]);
		echo ("\n<br>");
	}
	
	echo ("\n<br>");
	echo ("List all objects that the cool user named Ben have access from WS3");
	echo ("\n<br>");
	$listWSObjectsUser = $wslib->list_ws_objects_for_user ($id,$user);
	foreach ($listWSObjectsUser as $key)
	{
		echo ($key["objectId"]);
		echo ("     ");
		echo ($key["type"]);
		echo ("     ");
		echo ($key["name"]);
		echo ("\n<br>");
	}
	echo ("\n<br>");
	
	$wslib->add_ws_object($id,'Wiki5','wikipage');
	echo ("Se ha insertardo Wiki5 a WS3\n<br>");
	$objectId1 = $objectlib->get_object_id('wiki page', 'Wiki5');
	if (!$objectlib->get_object_id('wiki page','Wiki6'))
	{
		$objectlib->add_object('wiki page','Wiki6');
		$tikilib->create_page('Wiki6', 0, '', time(), '');
	}
	$wslib->add_ws_object($id,'Wiki6','wikipage');
	echo ("Se ha insertado Wiki6 a WS3\n<br>");
	$userlib->assign_object_permission('G1', 'Wiki5', 'wiki page', 'tiki_p_view');
	$userlib->assign_object_permission('G2', 'Wiki5', 'wiki page', 'tiki_p_view');
	$userlib->assign_object_permission('G1', 'Wiki6', 'wiki page', 'tiki_p_view');
	$objectId2 = $objectlib->get_object_id('wiki page', 'Wiki6');
	$wslib->remove_ws_object($id,$objectId1,'Wiki5','wiki page');
	echo ("Se ha eliminado Wiki5 de WS3 y solo se han eliminado los permisos de los grupos unicos a WS3\n<br>");
	$wslib->remove_ws_object($id,$objectId2,'Wiki6','wiki page');
	echo ("Se ha eliminado Wiki6 de WS3 y se han eliminado todos los permisos asociados\n<br>");
}

if (isset($_REQUEST['redirect']) && ($_REQUEST['redirect'] == 'yes'))
	header("Location: ./../../../tiki-admin.php?page=workspaces");
