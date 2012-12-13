<?php
// (c) Copyright 2002-2012 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_module_list()
{
	return array(
		'module_zones_top' => array(
			'name' => tra('Top module zone'),
			'description' => tra('Visibility of area to keep modules such as logo, login etc (header)'),
			'type' => 'list',
			'keywords' => tra('side bar'),
			'help' => 'Users+Flip+Columns',
			'options' => array(
				'y' => tra('Only if module'),
				'fixed' => tra('Always'),
//				'user' => tra('User Decides'),
				'n' => tra('Never'),
			),
			'default' => 'y',
		),
		'module_zones_topbar' => array(
			'name' => tra('Topbar module zone'),
			'description' => tra('Visibility of area for modules such as main horizontal menu, search form, page-wide content, etc.'),
			'type' => 'list',
			'keywords' => tra('topbar'),
			'help' => 'Users+Flip+Columns',
			'options' => array(
				'y' => tra('Only if module'),
				'fixed' => tra('Always'),
//				'user' => tra('User Decides'),
				'n' => tra('Never'),
			),
			'default' => 'y',
		),
		'module_zones_pagetop' => array(
			'name' => tra('Page top module zone'),
			'description' => tra('Visibility of area to keep modules such as share etc'),
			'type' => 'list',
			'keywords' => tra('side bar'),
			'help' => 'Users+Flip+Columns',
			'options' => array(
				'y' => tra('Only if module'),
				'fixed' => tra('Always'),
//				'user' => tra('User Decides'),
				'n' => tra('Never'),
			),
			'default' => 'y',
		),
		'module_zones_bottom' => array(
			'name' => tra('Bottom module zone'),
			'description' => tra('Visibility of area to keep modules such as "powered by" and "rss list" (footer)'),
			'type' => 'list',
			'keywords' => tra('side bar'),
			'help' => 'Users+Flip+Columns',
			'options' => array(
				'y' => tra('Only if module'),
				'fixed' => tra('Always'),
//				'user' => tra('User Decides'),
				'n' => tra('Never'),
			),
			'default' => 'y',
		),
		'module_zones_pagebottom' => array(
			'name' => tra('Page bottom module zone'),
			'description' => tra('Visibility of area to keep modules at the foot of each page'),
			'type' => 'list',
			'keywords' => tra('side bar'),
			'help' => 'Users+Flip+Columns',
			'options' => array(
				'y' => tra('Only if module'),
				'fixed' => tra('Always'),
//				'user' => tra('User Decides'),
				'n' => tra('Never'),
			),
			'default' => 'y',
		),
		'module_file' => array(
			'name' => tr('Module file'),
			'description' => tr('Use a static module definition file instead of relying on the dynamic values generated by Tiki. Useful for custom themes. The file must be in YAML format, following the format used in profiles.'),
			'type' => 'text',
			'default' => '',
		),
		'module_zone_available_extra' => array(
			'name' => tr('Extra module zones available'),
			'description' => tr('Extra module zones to be managed through the module administration interface. Useful if your custom theme requires a special zone other than the predefined ones.'),
			'hint' => tr('Comma-separated list, 20 characters max per entry.'),
			'type' => 'text',
			'separator' => ',',
			'filter' => 'alpha',
			'default' => array(),
		),
	);
}
