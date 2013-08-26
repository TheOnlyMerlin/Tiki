<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_activitystream_info()
{
	return array(
		'name' => tra('Activity Stream'),
		'documentation' => 'PluginActivityStream',
		'description' => tra('Generates a feed or activity stream based on the recorded events in the system.'),
		'prefs' => array('wikiplugin_activitystream', 'activity_custom_events'),
		'default' => 'y',
		'format' => 'html',
		'body' => tra('List configuration information'),
		'filter' => 'wikicontent',
		'profile_reference' => 'search_plugin_content',
		'icon' => 'img/icons/text_list_bullets.png',
		'tags' => array('advanced'),
		'params' => array(
		),
	);
}

function wikiplugin_activitystream($data, $params)
{
	$encoded = Tiki_Security::get()->encode(array(
		'body' => $data,
	));

	$servicelib = TikiLib::lib('service');
	return $servicelib->render('activitystream', 'render', array(
		'stream' => $encoded,
	));
}

