<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_addfreetag_info()
{
	return array(
		'name' => tra('Add Freetag'),
		'description' => tra('Add a single freetag to specified object'),
		'format' => 'html',
		'prefs' => array('feature_freetags', 'wikiplugin_addfreetag'),
		'introduced' => 8,
		'documentation' => 'PluginAddFreetag',
		'params' => array(
			'object' => array(
				'required' => false,
				'name' => tra('Object'),
				'description' => tra('Object type and id, as in type:id, if unset, will use current object.'),
				'filter' => 'text',
				'default' => null,
				'since' => '8.0',
			),
		),
	);
}

function wikiplugin_addfreetag($data, $params)
{
	global $user;
	$object = current_object();

	if (isset($params['object']) && false !== strpos($params['object'], ':')) {
		list($object['type'], $object['object']) = explode(':', $params['object'], 2);
	}
	if ($object['type'] == 'wiki page' && !ctype_digit($object['object'])) {
		$identifier = 'wp_addfreetag_' . str_replace(array(':',' '), array('_',''), TikiLib::lib('tiki')->get_page_id_from_name($params['object']));
	} else {
		$identifier = 'wp_addfreetag_' . str_replace(array(':',' '), array('_',''), $params['object']);
	}

	if (!empty($_POST[$identifier])) {
		$_POST[$identifier] = '"' . str_replace('"', '', $_POST[$identifier]) . '"';
		if ($object['type'] == 'trackeritem') {
			$permobject = TikiLib::lib('trk')->get_tracker_for_item($object['object']); 
			$permobjecttype = 'tracker';
		} else {
			$permobject = $object['object'];
			$permobjecttype = $object['type']; 
		}
		if (TikiLib::lib('tiki')->user_has_perm_on_object($user, $permobject, $permobjecttype, 'tiki_p_view_freetags')) { 
			TikiLib::lib('freetag')->tag_object( $user, $object['object'], $object['type'], $_POST[$identifier]);
		} 
		if ($object['type'] == 'trackeritem') {
			// need to update tracker field as well
			$definition = Tracker_Definition::get($permobject);
			if ($field = $definition->getFreetagField()) {
				$currenttags = TikiLib::lib('freetag')->get_tags_on_object( $object['object'], 'trackeritem' );
				$taglist = '';	
				foreach ($currenttags['data'] as $tag) {
					if (strstr($tag['tag'], ' ')) {
						$taglist .= '"'.$tag['tag'] . '" ';
					} else {
						$taglist .= $tag['tag'] . ' ';
					}
      				}
				// taglist will have slashes
				TikiLib::lib('trk')->modify_field($object['object'], $field, stripslashes($taglist));
			}
		} 
		$url = $_SERVER['REQUEST_URI'];
		header("Location: $url");
		die;
	}

	$smarty = TikiLib::lib('smarty');
	$smarty->assign('wp_addfreetag', $identifier); 
	return $smarty->fetch('wiki-plugins/wikiplugin_addfreetag.tpl');
}

