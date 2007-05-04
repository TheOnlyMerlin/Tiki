<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-user_information.php,v 1.41 2007-05-04 14:09:48 sylvieg Exp $

// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once ('tiki-setup.php');

include_once ('lib/messu/messulib.php');
include_once ('lib/userprefs/scrambleEmail.php');
include_once ('lib/registration/registrationlib.php');
include_once ('lib/wiki/wikilib.php');
include_once ('lib/trackers/trackerlib.php');

if (isset($_REQUEST['userId'])) {
	$userwatch = $tikilib->get_user_login($_REQUEST['userId']);
	if ($userwatch === NULL) {
		$smarty->assign('msg', tra("Unknown user"));
		$smarty->display("error.tpl");
		die;
	}
} elseif (isset($_REQUEST['view_user'])) {
	$userwatch = $_REQUEST['view_user'];
	if (!$userlib->user_exists($userwatch)) {
		$smarty->assign('msg', tra("Unknown user"));
		$smarty->display("error.tpl");
		die;
	}
} else {
	if ($user) {
		$userwatch = $user;
	} else {
		$smarty->assign('msg', tra("You are not logged in and no user indicated"));
		$smarty->display("error.tpl");
		die;
	}
}

$smarty->assign('userwatch', $userwatch);

// Custom fields
$customfields = array();
$customfields = $registrationlib->get_customfields($userwatch);

$smarty->assign_by_ref('customfields', $customfields);

if ($feature_friends == 'y') {
	$smarty->assign('friend', $tikilib->verify_friendship($userwatch, $user));
}

if ($tiki_p_admin != 'y') {
	$user_information = $tikilib->get_user_preference($userwatch, 'user_information', 'public');
	if ($user_information == 'private') {
		$smarty->assign('msg', tra("The user has chosen to make his information private"));
		$smarty->display("error.tpl");
		die;
	}
}


if ($user) {
	$smarty->assign('sent', 0);
	if (isset($_REQUEST['send'])) {
		check_ticket('user-information');
		$smarty->assign('sent', 1);

		$message = '';

		if (empty($_REQUEST['subject']) && empty($_REQUEST['body'])) {
			$smarty->assign('message', tra('ERROR: Either the subject or body must be non-empty'));
			$smarty->display("tiki.tpl");
			die;
		}
		$message = tra('Message sent to'). ':' . $userwatch . '<br />';
		$messulib->post_message($userwatch, $user, $_REQUEST['to'], '', $_REQUEST['subject'], $_REQUEST['body'], $_REQUEST['priority']);
		$smarty->assign('message', $message);
	}
}
if ($feature_score == 'y' and isset($user) and $user != $userwatch) {
	$tikilib->score_event($user, 'profile_see');
	$tikilib->score_event($userwatch, 'profile_is_seen');
}

$smarty->assign('priority',3);
if ($allowmsg_is_optional == 'y') {
	$allowMsgs = $tikilib->get_user_preference($userwatch,'allowMsgs','y');
} else {
	$allowMsgs = 'y';
}
$smarty->assign('allowMsgs',$allowMsgs);
$user_style = $tikilib->get_user_preference($userwatch,'theme',$site_style);
$user_language = $tikilib->get_language($userwatch);
$smarty->assign_by_ref('user_language',$user_language);
$smarty->assign_by_ref('user_style',$user_style);
$realName = $tikilib->get_user_preference($userwatch,'realName','');
$gender = $tikilib->get_user_preference($userwatch,'gender','');
$country = $tikilib->get_user_preference($userwatch,'country','Other');
$smarty->assign('country',$country);
$anonpref = $tikilib->get_preference('userbreadCrumb',4);
$userbreadCrumb = $tikilib->get_user_preference($userwatch,'userbreadCrumb',$anonpref);
$smarty->assign_by_ref('realName',$realName);
$smarty->assign_by_ref('gender',$gender);
$smarty->assign_by_ref('userbreadCrumb',$userbreadCrumb);
$homePage = $tikilib->get_user_preference($userwatch,'homePage','');

$smarty->assign_by_ref('homePage',$homePage);

$avatar = $tikilib->get_user_avatar($userwatch);
$smarty->assign('avatar', $avatar);

$user_information = $tikilib->get_user_preference($userwatch, 'user_information', 'public');
$smarty->assign('user_information', $user_information);

$userinfo = $userlib->get_user_info($userwatch);
$email_isPublic = $tikilib->get_user_preference($userwatch, 'email is public', 'n');
if ($email_isPublic != 'n') {
	$userinfo['email'] = scrambleEmail($userinfo['email'], $email_isPublic);
}
$smarty->assign_by_ref('userinfo', $userinfo);
$smarty->assign_by_ref('email_isPublic',$email_isPublic);
$userPage = $feature_wiki_userpage_prefix.$userinfo['login'];
$exist = $tikilib->page_exists($userPage);
$smarty->assign("userPage_exists", $exist);

if ($feature_display_my_to_others == 'y') {
	$user_pages = $wikilib->get_user_all_pages($userwatch, 'pageName_asc');
	$smarty->assign_by_ref('user_pages', $user_pages);
	$user_blogs = $tikilib->list_user_blogs($userwatch,false);
	$smarty->assign_by_ref('user_blogs', $user_blogs);
	$user_galleries = $tikilib->get_user_galleries($userwatch, -1);
	$smarty->assign_by_ref('user_galleries', $user_galleries);
}

if ( $user_tracker_infos ) {
	// arg passed 11,56,58,68=trackerId,fieldId...
	$trackerinfo = explode(',',$user_tracker_infos) ;
	$userTrackerId = $trackerinfo[0] ;
	array_shift ($trackerinfo);
	$fields = $trklib->list_tracker_fields($userTrackerId, 0, -1, 'position_asc', '', true, array('fields'=>$trackerinfo));
	foreach ($fields['data'] as $field) {
		$lll[$field['fieldId']] = $field;
	}
	$items = $trklib->list_items($userTrackerId, 0, 1, '',  $lll, $trklib->get_field_id_from_type($userTrackerId, 'u', '1'), $userwatch);
	$smarty->assign_by_ref('userItem',$items['data'][0]);
}

ask_ticket('user-information');

// disallow robots to index page:
$smarty->assign('metatag_robots', 'NOINDEX, NOFOLLOW');

$smarty->assign('mid', 'tiki-user_information.tpl');
$smarty->display("tiki.tpl");

?>
