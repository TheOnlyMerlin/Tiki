<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-admin_include_userfiles.php,v 1.5 2004-03-27 21:23:52 mose Exp $

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  die("This script cannot be called directly");
}


if (isset($_REQUEST["userfilesprefs"])) {
	check_ticket('admin-inc-userfiles');
	$tikilib->set_preference("uf_use_db", $_REQUEST["uf_use_db"]);

	$tikilib->set_preference("uf_use_dir", $_REQUEST["uf_use_dir"]);
	$tikilib->set_preference("userfiles_quota", $_REQUEST["userfiles_quota"]);
	$smarty->assign('uf_use_db', $_REQUEST["uf_use_db"]);
	$smarty->assign('uf_use_dir', $_REQUEST["uf_use_dir"]);
	$smarty->assign('userfiles_quota', $_REQUEST['userfiles_quota']);
}
ask_ticket('admin-inc-userfiles');
?>
