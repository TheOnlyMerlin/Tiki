<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-login_scr.php,v 1.11 2005-05-18 10:58:58 mose Exp $

// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

# $Header: /cvsroot/tikiwiki/tiki/tiki-login_scr.php,v 1.11 2005-05-18 10:58:58 mose Exp $
include_once ("tiki-setup.php");

if (isset($_REQUEST['user']) && $_REQUEST['user'] == 'admin') {
	$smarty->assign('showloginboxes', 'y');
}

if (isset($_REQUEST['user'])) {
	$smarty->assign('loginuser', $_REQUEST['user']);
}

$smarty->assign('mid', 'tiki-login.tpl');
$smarty->display("tiki.tpl");

?>