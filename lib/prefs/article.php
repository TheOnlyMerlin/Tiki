<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_article_list() {
	return array(
		'article_comments_per_page' => array(
			'name' => tra('Default number per page'),
			'type' => 'text',
			'size' => '5',
			'filter' => 'digits',
		),
		'article_comments_default_ordering' => array(
			'name' => tra('Default Ordering'),
			'type' => 'list',
			'options' => array(
				'commentDate_desc' => tra('Newest first'),
				'commentDate_asc' => tra('Oldest first'),
				'points_desc' => tra('Points'),
			),

		),
		'article_paginate' => array(
			'name' => tra('Paginate articles'),
			'description' => tra('Divide articles into multiple pages with pagebreak markers.'),
			'type' => 'flag',
		),
		'article_user_rating' => array(
			'name' => tra('User ratings on articles'),
			'description' => tra('Allows users to rate the articles.'),
			'type' => 'flag',
		),
		'article_user_rating_options' => array(
			'name' => tra('Article rating options'),
			'description' => tra('List of options available for the rating of articles.'),
			'type' => 'text',
			'separator' => ',',
			'filter' => 'int',
		),
	);
}
