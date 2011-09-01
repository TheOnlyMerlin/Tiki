<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function smarty_function_service($params, $smarty)
{
	global $prefs;

	if (! isset($params['controller'])) {
		return 'missing-controller';
	}

	if ($prefs['feature_sefurl'] == 'y') {
		$url = "tiki-{$params['controller']}";

		if (isset($params['action'])) {
			$url .= "-{$params['action']}";
		}

		unset($params['controller']);
		unset($params['action']);
	} else {
		$url = 'tiki-ajax_services.php';
	}

	if (count($params)) {
		$url .= '?' . http_build_query($params, '', '&');
	}

	require_once 'lib/smarty_tiki/modifier.escape.php';	
	return smarty_modifier_escape($url);
}

