<?php
// (c) Copyright 2002-2015 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_menus_list()
{
	return array(
		'menus_items_icons' => array(
			'name' => tra('Menu icons'),
			'description' => tra('Allows icons to be defined for menu entries'),
			'type' => 'flag',
			'default' => 'n',
		),
		'menus_items_icons_path' => array(
			'name' => tra('Default path for the icons'),
            'description' => tra(''),
			'type' => 'text',
			'default' => 'img/icons/large',
		),
		'menus_item_names_raw_teaser' => array(
			'name' => tra('Allow HTML in menu option names and URLs'),
			'description' => tra('If enabled, menu option names and URLs are treated as HTML content and they are not escaped (there is no replacement of HTML special characters). This allows the use of HTML for inserting images, for example (the code must be valid). This also allows menu item editors to input arbitrary HTML; only enable this if the risks are understood and accepted.'),
			'help' => 'Menus',
			'type' => 'flag',
			'default' => 'n',
		),
		'menus_item_names_raw' => array(
			'name' => tra('REALLY allow HTML (INSECURE)'),
			'description' => tra('This needs to be checked for the parent preference to be actually enabled. This allows menu item editors to put arbitrary HTML, which could allow session hijacking; only enable if you know what you are doing.'),
			'help' => 'Menus',
			'dependencies' => array(
				'menus_item_names_raw_teaser',
			),
			'type' => 'flag',
			'default' => 'n',
		),
	);
}
