<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-directory_redirect.php,v 1.2 2003-08-07 04:33:57 rossta Exp $

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once ('tiki-setup.php');

include_once ('lib/directory/dirlib.php');

if ($feature_directory != 'y') {
	$smarty->assign('msg', tra("This feature is disabled"));

	$smarty->display("styles/$style_base/error.tpl");
	die;
}

if ($tiki_p_view_directory != 'y') {
	$smarty->assign('msg', tra("Permission denied"));

	$smarty->display("styles/$style_base/error.tpl");
	die;
}

if (!isset($_REQUEST['siteId'])) {
	$smarty->assign('msg', tra("No site indicated"));

	$smarty->display("styles/$style_base/error.tpl");
	die;
}

$site_info = $dirlib->dir_get_site($_REQUEST['siteId']);
$url = $site_info['url'];
// Add a hit to the site
$dirlib->dir_add_site_hit($_REQUEST['siteId']);

// Redirect to the site URI
header ("location: $url");
die;

?>