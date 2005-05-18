<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-poll_form.php,v 1.15 2005-05-18 10:58:58 mose Exp $

// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once ('tiki-setup.php');

require_once ('lib/tikilib.php'); # httpScheme()
include_once ('lib/polls/polllib.php');

if (!isset($polllib)) {
	$polllib = new PollLib($dbTiki);
}

if ($feature_polls != 'y') {
	$smarty->assign('msg', tra("This feature is disabled").": feature_polls");

	$smarty->display("error.tpl");
	die;
}

if (!isset($_REQUEST["pollId"])) {
	$smarty->assign('msg', tra("No poll indicated"));

	$smarty->display("error.tpl");
	die;
}

$poll_info = $polllib->get_poll($_REQUEST["pollId"]);
$options = $polllib->list_poll_options($_REQUEST["pollId"]);

$smarty->assign_by_ref('menu_info', $poll_info);
$smarty->assign_by_ref('channels', $options);
$smarty->assign('ownurl', $tikilib->httpPrefix(). $_SERVER["REQUEST_URI"]);

ask_ticket('poll-form');

// Display the template
$smarty->assign('title', $poll_info['title']);
$smarty->assign('mid', 'tiki-poll_form.tpl');
$smarty->display("tiki.tpl");

?>
