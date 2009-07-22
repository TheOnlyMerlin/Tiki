<?php
// (c) Copyright 2002-2009 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: /cvsroot/tikiwiki/tiki/tiki-notepad_read.php,v 1.22 2007-10-12 07:55:29 nyloth Exp $
$section = 'mytiki';
require_once ('tiki-setup.php');
include_once ('lib/notepad/notepadlib.php');
if ($prefs['feature_notepad'] != 'y') {
	$smarty->assign('msg', tra("This feature is disabled") . ": feature_notepad");
	$smarty->display("error.tpl");
	die;
}
if (!$user) {
	$smarty->assign('msg', tra("Must be logged to use this feature"));
	$smarty->display("error.tpl");
	die;
}
if ($tiki_p_notepad != 'y') {
	$smarty->assign('errortype', 401);
	$smarty->assign('msg', tra("Permission denied to use this feature"));
	$smarty->display("error.tpl");
	die;
}
if (!isset($_REQUEST["noteId"])) {
	$smarty->assign('msg', tra("No note indicated"));
	$smarty->display("error.tpl");
	die;
}
$area = 'delnote';
if (isset($_REQUEST["remove"])) {
	if (isset($_POST['daconfirm']) and isset($_SESSION["ticket_$area"])) {
		key_check($area);
		$notepadlib->remove_note($user, $_REQUEST['noteId']);
		header('location: tiki-notepad_list.php');
		die;
	} else {
		key_get($area, tra('Are you sure you want to delete this note?'));
	}
}
$info = $notepadlib->get_note($user, $_REQUEST["noteId"]);
if ($tiki_p_edit == 'y') {
	if (isset($_REQUEST['wikify'])) {
		check_ticket('notepad-read');
		if (empty($_REQUEST['wiki_name'])) {
			$smarty->assign('msg', tra("No name indicated for wiki page"));
			$smarty->display("error.tpl");
			die;
		}
		if ($tikilib->page_exists($_REQUEST['wiki_name']) && !isset($_REQUEST['over'])) {
			$smarty->assign('msg', tra("Page already exists"));
			$smarty->display("error.tpl");
			die;
		}
		if ($tikilib->page_exists($_REQUEST['wiki_name'])) {
			$tikilib->update_page($_REQUEST['wiki_name'], $info['data'], tra('created from notepad'), $user, '127.0.1.1', $info['name']);
		} else {
			$tikilib->create_page($_REQUEST['wiki_name'], 0, $info['data'], $tikilib->now, tra('created from notepad'), $user, $ip = '0.0.0.0', $info['name']);
		}
	}
}
if ($tikilib->page_exists($info['name'])) {
	$smarty->assign("wiki_exists", "y");
} else {
	$smarty->assign("wiki_exists", "n");
}
if (isset($_REQUEST['parse_mode']) and $_REQUEST['parse_mode'] != $info['parse_mode']) {
	$notepadlib->set_note_parsing($user, $_REQUEST['noteId'], $_REQUEST['parse_mode']);
	$info['parse_mode'] = $_REQUEST['parse_mode'];
}
if ($info['parse_mode'] == 'raw') {
	$info['parsed'] = nl2br(htmlspecialchars($info['data']));
	$smarty->assign('wysiwyg', 'n');
} else {
	include 'tiki-parsemode_setup.php';
	$info['parsed'] = $tikilib->parse_data($info['data'], array('is_html' => $is_html));
}
$smarty->assign('noteId', $_REQUEST["noteId"]);
$smarty->assign('info', $info);
include_once ('tiki-section_options.php');
include_once ('tiki-mytiki_shared.php');
ask_ticket('notepad-read');
$smarty->assign('mid', 'tiki-notepad_read.tpl');
$smarty->display("tiki.tpl");
