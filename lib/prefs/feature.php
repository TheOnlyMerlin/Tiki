<?php

function prefs_feature_list() {
	return array(
		'feature_wiki' => array(
			'name' => tra('Wiki'),
			'description' => tra('Collaboratively authored documents with history of changes.'),
			'type' => 'flag',
			'help' => 'Wiki',
		),
		'feature_blogs' => array(
			'name' => tra('Blog'),
			'description' => tra('Online diaries or journals.'),
			'type' => 'flag',
			'help' => 'Blogs',
		),
		'feature_galleries' => array(
			'name' => tra('Image Gallery'),
			'description' => tra('Collections of graphic images for viewing or downloading (photo album)'),
			'type' => 'flag',
			'help' => 'Image+Gallery',
		),
		'feature_machine_translation' => array(
			'name' => tra('Machine Translation (by Google Translate)'),
			'description' => tra('Uses Google Translate to translate the content of wiki pages to other languages.'),
			'help' => 'Translating+Tiki+Content',
			'warning' => tra('Experimental. This feature is still under development.'),
			'type' => 'flag',
		),	
		'feature_trackers' => array(
			'name' => tra('Trackers'),
			'description' => tra('Database & form generator'),
			'help' => 'Trackers',
			'type' => 'flag',
		),
		'feature_forums' => array(
			'name' => tra('Forums'),
			'description' => tra('Online discussions on a variety of topics. Threaded or flat.'),
			'help' => 'Forums',
			'type' => 'flag',
		),
		'feature_file_galleries' => array(
			'name' => tra('File Gallery'),
			'description' => tra('Computer files, videos or software for downloading. With check-in & check-out (lock)'),
			'help' => 'File Gallery',
			'type' => 'flag',
		),
		'feature_articles' => array(
			'name' => tra('Articles'),
			'description' => tra('Articles can be used for date-specific news and announcements. You can configure articles to automatically publish and expire at specific times or to require that submissions be approved before becoming "live."'),
			'help' => 'Article',
			'type' => 'flag',
		),
		'feature_polls' => array(
			'name' => tra('Polls'),
			'description' => tra('Brief list of votable options; appears in module (left or right column)'),
			'help' => 'Poll',
			'type' => 'flag',
		),
		'feature_newsletters' => array(
			'name' => tra('Newletters'),
			'description' => tra('Content mailed to registered users.'),
			'help' => 'Newsletters',
			'type' => 'flag',
		),
		'feature_calendar' => array(
			'name' => tra('Calendar'),
			'description' => tra('Events calendar with public, private and group channels.'),
			'help' => 'Calendar',
			'type' => 'flag',
		),
		'feature_banners' => array(
			'name' => tra('Banners'),
			'description' => tra('Insert, track, and manage advertising banners.'),
			'help' => 'Banners',
			'type' => 'flag',
		),
		'feature_categories' => array(
			'name' => tra('Category'),
			'description' => tra('Global category system. Items of different types (wiki pages, articles, tracker items, etc) can be added to one or many categories. Categories can have permissions.'),
			'help' => 'Category',
			'type' => 'flag',
		),
		'feature_score' => array(
			'name' => tra('Score'),
			'description' => tra('Score is a game to motivate participants to increase their contribution by comparing to other users.'),
			'help' => 'Score',
			'type' => 'flag',
		),
		'feature_search' => array(
			'name' => tra('Search'),
			'description' => tra('Enables searching for content on the website.'),
			'help' => 'Search',
			'type' => 'flag',
		),
		'feature_freetags' => array(
			'name' => tra('Freetags'),
			'description' => tra('Allows to set tags on pages and various objects within the website and generate tag cloud navigation patterns.'),
			'help' => 'Tags',
			'type' => 'flag',
		),
		'feature_actionlog' => array(
			'name' => tra('Action Log'),
			'description' => tra('Allows to keep track of what users are doing and produce reports on a per-user or per-category basis.'),
			'help' => 'Action+Log',
			'type' => 'flag',
		),
		'feature_contribution' => array(
			'name' => tra('Contribution'),
			'description' => tra('Allows users to specify the type of contribution they are making while editing objects. The contributions are then displayed as color-coded in history and other reports.'),
			'help' => 'Contribution',
			'type' => 'flag',
		),
		'feature_multilingual' => array(
			'name' => tra('Multilingual'),
			'description' => tra('Enables internationalization features and multilingual support for then entire site.'),
			'help' => 'Internationalization',
			'type' => 'flag',
		),
		'feature_faqs' => array(
			'name' => tra('FAQ'),
			'description' => tra('Frequently asked questions and answers'),
			'help' => 'FAQ',
			'type' => 'flag',
		),
		'feature_surveys' => array(
			'name' => tra('Surveys'),
			'description' => tra('Questionnaire with multiple choice or open ended question'),
			'help' => 'Surveys',
			'type' => 'flag',
		),
		'feature_directory' => array(
			'name' => tra('Directory'),
			'description' => tra('User-submitted Web links'),
			'help' => 'Directory',
			'type' => 'flag',
		),
		'feature_quizzes' => array(
			'name' => tra('Quizzes '),
			'description' => tra('Timed questionnaire with recorded scores.'),
			'help' => 'Quizzes',
			'type' => 'flag',
		),
		'feature_featuredLinks' => array(
			'name' => tra('Featured links'),
			'description' => tra('Simple menu system which can optionally add an external web page in an iframe'),
			'help' => 'Featured+links',
			'type' => 'flag',
		),
		'feature_copyright' => array(
			'name' => tra('Copyright'),
			'description' => tra('The Copyright Management System (or ©MS) is a way of licensing your content'),
			'help' => 'Copyright',
			'type' => 'flag',
		),
		'feature_multimedia' => array(
			'name' => tra('Multimedia'),
			'description' => tra('The applet is designed to read MP3 or FLV file'),
			'help' => 'Multimedia',
			'type' => 'flag',
			'warning' => tra('Experimental. This feature is not actively maintained.'),
		),
		'feature_shoutbox' => array(
			'name' => tra('Shoutbox'),
			'description' => tra('Quick comment (graffiti) box. Like a group chat, but not in real time.'),
			'help' => 'Shoutbox',
			'type' => 'flag',
		),
		'feature_maps' => array(
			'name' => tra('Maps'),
			'description' => tra('Navigable, interactive maps with user-selectable layers'),
			'help' => 'Maps',
			'warning' => tra('Requires mapserver'),
			'type' => 'flag',
		),
		'feature_gmap' => array(
			'name' => tra('Google Maps'),
			'description' => tra('Use of Google Maps interactively inside Tiki'),
			'help' => 'GMap',
			'type' => 'flag',
		),
		'feature_live_support' => array(
			'name' => tra('Live support system'),
			'description' => tra('One-on-one chatting with customer'),
			'help' => 'Live+Support',
			'type' => 'flag',
		),
		'feature_tell_a_friend' => array(
			'name' => tra('Tell a Friend'),
			'description' => tra('Add a link "Email this page" in all the pages'),
			'help' => 'Tell+a+Friend',
			'type' => 'flag',
		),
		'feature_html_pages' => array(
			'name' => tra('HTML pages'),
			'description' => tra('Static and dynamic HTML content'),
			'help' => 'HTML+Pages',
			'warning' => tra('HTML can be used in wiki pages. This is a separate feature.'),
			'type' => 'flag',
		),
		'feature_contact' => array(
			'name' => tra('Contact Us'),
			'description' => tra('Basic form from visitor to admin'),
			'help' => 'Contact+us',
			'type' => 'flag',
		),
		'feature_minichat' => array(
			'name' => tra('Minichat'),
			'description' => tra('Real-time group text chatting'),
			'help' => 'Minichat',
			'type' => 'flag',
		),
		'feature_comments_moderation' => array(
			'name' => tra('Comments Moderation '),
			'description' => tra('An admin must validate a comment before it is visible'),
			'help' => 'Comments',
			'type' => 'flag',
		),
		'feature_comments_locking' => array(
			'name' => tra('Comments Locking'),
			'description' => tra('Comments can be closed (no new comments)'),
			'help' => 'Comments',
			'type' => 'flag',
		),
		'feature_wiki_description' => array(
			'name' => tra('Display page description'),
			'description' => tra('Display the page description below the heading when viewing the page.'),
			'type' => 'flag',
		),
		'feature_page_title' => array(
			'name' => tra('Display page title'),
			'description' => tra('Display the page name at the top of each page. If not enabled, the content must be structured to contain a header.'),
			'type' => 'flag',
		),
		'feature_wiki_pageid' => array(
			'name' => tra('Display page ID'),
			'description' => tra('Display the internal page ID when viewing the page.'),
			'type' => 'flag',
		),
		'feature_wiki_icache' => array(
			'name' => tra('Individual wiki cache'),
			'description' => tra('Allow users to change the duration of the cache on a per-page basis.'),
			'type' => 'flag',
		),
		'feature_jscalendar' => array(
			'name' => tra('JS Calendar'),
			'description' => tra('JavaScript popup date selector.'),
			'help' => 'JS+Calendar',
			'type' => 'flag',
		),
		'feature_phplayers' => array(
			'name' => tra('PHPLayers'),
			'description' => tra('PhpLayers Dynamic menus.'),
			'help' => 'http://themes.tikiwiki.org/PhpLayersMenu',
			'type' => 'flag',
		),
		'feature_htmlpurifier_output' => array(
			'name' => tra('Tiki Ouput Should be HTMLPurified'),
			'description' => tra('This enable HTPMPurifier on Tiki outputs to filter remaining security problems like XSS.'),
			'help' => 'http://themes.tikiwiki.org/HTMLPurifier',
			'type' => 'flag',
			'default' => 'n',
		),
		'feature_fullscreen' => array(
			'name' => tra('Full Screen'),
			'description' => tra('Allow users to activate fullscreen mode.'),
			'help' => 'Fullscreen',
			'type' => 'flag',
		),
		'feature_cssmenus' => array(
			'name' => tra('Css Menus'),
			'description' => tra('Css Menus (suckerfish).'),
			'help' => 'Menus',
			'type' => 'flag',
		),
		'feature_shadowbox' => array(
			'name' => tra('Shadowbox'),
			'description' => tra('Shadowbox'),
			'help' => 'Shadowbox',
			'type' => 'flag',
		),
		'feature_quick_object_perms' => array(
			'name' => tra('QuickPerms'),
			'description' => tra('Quickperms'),
			'help' => 'Quickperms',
			'type' => 'flag',
		),
		'feature_purifier' => array(
			'name' => tra('HTML Purifier'),
			'description' => tra('HTML Purifier'),
			'help' => 'HTML Purifier',
			'type' => 'flag',
		),
		'feature_ajax' => array(
			'name' => tra('Ajax'),
			'description' => tra('Ajax'),
			'help' => 'Ajax',
			'type' => 'flag',
		),
		'feature_' => array(
			'name' => tra(''),
			'description' => tra(''),
			'help' => '',
			'type' => 'flag',
		),
		'feature_mobile' => array(
			'name' => tra('Mobile'),
			'description' => tra('Mobile'),
			'help' => 'http://mobile.tikiwiki.org',
			'type' => 'flag',
		),
		'feature_morcego' => array(
			'name' => tra('Morcego 3D browser'),
			'description' => tra('Morcego 3D browser'),
			'help' => 'Wiki+3D',
			'type' => 'flag',
		),
		'feature_webmail' => array(
			'name' => tra('Webmail'),
			'description' => tra('Webmail'),
			'help' => 'WEbmail',
			'type' => 'flag',
		),
		'feature_intertiki' => array(
			'name' => tra('Intertiki'),
			'description' => tra('Intertiki'),
			'help' => 'Intertiki',
			'type' => 'flag',
		),
		'feature_mailin' => array(
			'name' => tra('Mail-in'),
			'description' => tra('Mail-in'),
			'help' => 'Mail-in',
			'type' => 'flag',
		),
		'feature_wiki_mindmap' => array(
			'name' => tra('Mindmap'),
			'description' => tra('Mindmap'),
			'help' => 'MindMap',
			'type' => 'flag',
		),
		'feature_print_indexed' => array(
			'name' => tra('Print Indexed'),
			'description' => tra('Print Indexed'),
			'help' => 'Print+Indexed',
			'type' => 'flag',
		),
		'feature_' => array(
			'name' => tra(''),
			'description' => tra(''),
			'help' => '',
			'type' => 'flag',
		),
		'feature_' => array(
			'name' => tra(''),
			'description' => tra(''),
			'help' => '',
			'type' => 'flag',
		),
		'feature_sefurl' => array(
			'name' => tra('Search engine friendly url'),
			'description' => tra('Search engine friendly url'),
			'help' => 'Rewrite+Rules',
			'type' => 'flag',
		),
		'feature_sheet' => array(
			'name' => tra('Tiki Sheet'),
			'description' => tra('Tiki Sheet'),
			'help' => 'Spreadsheet',
			'type' => 'flag',
		),
		'feature_wysiwyg' => array(
			'name' => tra('Wysiwyg editor'),
			'description' => tra('Wysiwyg editor'),
			'help' => 'Wysiwyg+Editor',
			'type' => 'flag',
		),
		'feature_ajax_autosave' => array(
			'name' => tra('Ajax auto-save'),
			'description' => tra('Ajax auto-save'),
			'help' => 'Lost+Edit+Protection',
			'type' => 'flag',
		),
		'feature_workspaces' => array(
			'name' => tra('Workspaces'),
			'description' => tra('Worksapces'),
			'help' => '',
			'type' => 'flag',
		),
		'feature_friends' => array(
			'name' => tra('Friendship Network'),
			'description' => tra('Friendship Network'),
			'help' => 'Friendship',
			'type' => 'flag',
		),
		'feature_banning' => array(
			'name' => tra('Banning system'),
			'description' => tra('Banning system'),
			'help' => 'Banning',
			'type' => 'flag',
		),
		'feature_stats' => array(
			'name' => tra('Stats'),
			'description' => tra('Stats'),
			'help' => 'Stats',
			'type' => 'flag',
		),
		'feature_action_calendar' => array(
			'name' => tra('Tiki action calendar'),
			'description' => tra('Tiki action calendar'),
			'help' => 'Action+Calendar',
			'type' => 'flag',
		),
		'feature_referer_stats' => array(
			'name' => tra('Referer Stats'),
			'description' => tra('Referer Stats'),
			'help' => 'Stats',
			'type' => 'flag',
		),
		'feature_redirect_on_error' => array(
			'name' => tra('Redirect On Error'),
			'description' => tra('Redirect On Error'),
			'help' => '',
			'type' => 'flag',
		),
		'feature_comm' => array(
			'name' => tra('Communications (send/receive objects)'),
			'description' => tra('Communications (send/receive objects)'),
			'help' => 'Communication+Center',
			'type' => 'flag',
		),
		'feature_custom_home' => array(
			'name' => tra('Custom Home'),
			'description' => tra('Custom Home'),
			'help' => 'Custom+Home',
			'type' => 'flag',
		),
		'feature_mytiki' => array(
			'name' => tra("Display 'MyTiki' in the application menu"),
			'description' => tra("Display 'MyTiki' in the application menu"),
			'help' => '',
			'type' => 'flag',
		),
		'feature_minical' => array(
			'name' => tra('Mini Calendar'),
			'description' => tra('Mini Calendar'),
			'help' => 'Calendar',
			'type' => 'flag',
		),
		'feature_userPreferences' => array(
			'name' => tra('User Preferenes Screen'),
			'description' => tra('User Preferenes Screen'),
			'help' => 'User+Preferences',
			'type' => 'flag',
		),
		'feature_notepad' => array(
			'name' => tra('User Notepad'),
			'description' => tra('User Notepad'),
			'help' => 'Notepad',
			'type' => 'flag',
		),
		'feature_user_bookmarks' => array(
			'name' => tra('User Bookmarks'),
			'description' => tra('User Bookmarks'),
			'help' => 'Bookmarks',
			'type' => 'flag',
		),
		'feature_contacts' => array(
			'name' => tra('User Contacts'),
			'description' => tra('User Contacts'),
			'help' => 'Contacts',
			'type' => 'flag',
		),
		'feature_user_watches' => array(
			'name' => tra('User Watches'),
			'description' => tra('User Watches'),
			'help' => 'User+Watches',
			'type' => 'flag',
		),
		'feature_group_watches' => array(
			'name' => tra('Group Watches'),
			'description' => tra('Group Watches'),
			'help' => 'Group+Watches',
			'type' => 'flag',
		),
		'feature_daily_report_watches' => array(
			'name' => tra('Daily Reports for User Watches'),
			'description' => tra('Daily Reports for User Watches'),
			'help' => 'Daily+Reports',
			'type' => 'flag',
		),
		'feature_user_watches_translations' => array(
			'name' => tra('User Watches Translations'),
			'description' => tra('User Watches Translations'),
			'help' => 'User+Watches',
			'type' => 'flag',
		),
		'feature_usermenu' => array(
			'name' => tra('User Menu'),
			'description' => tra('User Menu'),
			'help' => 'User+Menu',
			'type' => 'flag',
		),
		'feature_tasks' => array(
			'name' => tra('User Tasks'),
			'description' => tra('User Tasks'),
			'help' => 'Task',
			'type' => 'flag',
		),
		'feature_messages' => array(
			'name' => tra('User Messages'),
			'description' => tra('User Messages'),
			'help' => 'Inter-user+Messages',
			'type' => 'flag',
		),
		'feature_userfiles' => array(
			'name' => tra('User Files'),
			'description' => tra('User Files'),
			'help' => 'User+Files',
			'type' => 'flag',
		),
		'feature_userlevels' => array(
			'name' => tra('User Levels'),
			'description' => tra('User Levels'),
			'help' => 'User+Levels',
			'type' => 'flag',
		),
		'feature_groupalert' => array(
			'name' => tra('Group Alert'),
			'description' => tra('Group Alert'),
			'help' => 'Group+Alert',
			'type' => 'flag',
		),
		'feature_integrator' => array(
			'name' => tra('Integrator'),
			'description' => tra('Integrator'),
			'help' => 'Tiki+Integrator',
			'type' => 'flag',
		),
		'feature_xmlrpc' => array(
			'name' => tra('XMLRPC API'),
			'description' => tra('XMLRPC API'),
			'help' => 'Xmlrpc',
			'type' => 'flag',
		),
		'feature_debug_console' => array(
			'name' => tra('Debugger Console'),
			'description' => tra('Debugger Console'),
			'help' => 'Debugger+Console',
			'type' => 'flag',
		),
		'feature_tikitests' => array(
			'name' => tra('Tikitests (requires PHP5 or above)'),
			'description' => tra('Tikitests (requires PHP5 or above)'),
			'help' => '',
			'type' => 'flag',
		),
		'feature_workflow' => array(
			'name' => tra('Workflow engine'),
			'description' => tra('Workflow engine'),
			'help' => 'Workflow',
			'type' => 'flag',
		),
		'feature_use_minified_scripts' => array(
			'name' => tra('Use Minified Scripts'),
			'description' => tra('Use Minified Scripts'),
			'help' => 'MinifiedScripts',
			'type' => 'flag',
		),
		'feature_debug_ignore_xdebug' => array(
			'name' => tra('Ignore XDebug'),
			'description' => tra("Ignore XDebug: Don't use XDebug debugging info if installed. Try this if you use xdebug and are geting blank pages."),
			'help' => 'DebugIgnoreXDebug',
			'type' => 'flag',
		),
	);
}
