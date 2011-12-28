<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

$_SERVER['HTTP_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

require_once ('tiki-setup.php');

$_REQUEST['type'] = (!empty($_REQUEST['type']) ? $_REQUEST['type'] : 'html');

if ($_REQUEST['type'] == 'html') {
	$access->check_feature('feature_htmlfeed');
	
	if (!empty($_REQUEST['feed']) && !empty($_REQUEST['name'])) {
		//here we try to view the results of an external feed, admin only
		$access->check_permission('tiki_p_admin');
		
		
		if (isset($_REQUEST['date'])) {
			$item = Feed_Html_Remote::href(urldecode($_REQUEST['feed']))
				->getItemFromDate(urldecode($_REQUEST['name']), urldecode($_REQUEST['date']));
		} else {
			$item = Feed_Html_Remote::href(urldecode($_REQUEST['feed']))->getItem(urldecode($_REQUEST['name']));
		}
		
		print_r(json_encode($item));
		
	} else {
		
		$feed = new Feed_Html();
		print_r(json_encode($feed->feed()));
		
	}
} else if ($_REQUEST['type'] == "forwardlink") {
	$access->check_feature('feature_forwardlinkprotocol');
	
	$feed = new Feed_ForwardLink();
	print_r(json_encode($feed->feed()));
	
} else if ($_REQUEST['type'] == "textlink" && !empty($_REQUEST['contribution'])) {
	$access->check_feature('feature_forwardlinkprotocol');
	
	$contribution = json_decode($_REQUEST['contribution']);
	foreach($contribution->feed->entry as $item) {
		if (!empty($item->originName)) {
			//print_r($item);
			Feed_ForwardLink_Contribution::forwardlink($item->originName)->addItem($item);
		}
	}
}