<?php

function wikiplugin_phpfreechat_info() {
	return array(
		'name' => tra('PhpFreeChat'),
		'documentation' => 'Mod+phpfreechat',
		'description' => tra("Displays a chat (using phpfreechat) in a wiki page"),
		'prefs' => array( 'wikiplugin_phpfreechat' ),
		'validate' => 'all',
		'icon' => 'pics/icons/phpfreechat.png',	
		'params' => array(
			'title' => array(
				'required' => true,
				'safe' => true,
				'name' => tra('title'),
				'description' => tra('Title for the chat window'),
				'filter' => 'striptags',
				'advanced' => false,
			),
			'channel' => array(
				'required' => true,
				'safe' => true,
				'name' => tra('channel'),
				'description' => tra('Chat channel name'),
				'filter' => 'striptags',
				'advanced' => false,
			),
		),
	);
}

function wikiplugin_phpfreechat($data, $params) {
	global $user, $TIKI_VIRTUAL;
	require_once('lib/phpfreechat/src/phpfreechat.class.php');

	$chatparams=array();

	$chatparams['channels']=isset($params['channel']) ? array($params['channel']) : array($_SERVER['TIKI_VIRTUAL']);
	$chatparams['title']=isset($params['title']) ? $params['title'] : 'Chat';
	$chatparams['serverid'] = 42;

	$chatparams['nick'] = $user;

	//erk
	$_SESSION['phpfreechat']=$chatparams;

	$chatparams['server_script_url'] = 'tiki-phpfreechat_ajax.php';
	$chatparams['data_public_url'] = 'lib/phpfreechat/data/public';
	$chatparams['theme_path'] = 'lib/phpfreechat/themes';
	$chatparams['theme_default_path'] = 'lib/phpfreechat/themes/default';
	$chatparams['theme_url'] = 'lib/phpfreechat/themes';
	$chatparams['theme_default_url'] = 'lib/phpfreechat/themes/default';

	$chat = new phpFreeChat( $chatparams );

	return "<!--<pre>-->".$chat->printChat(true)."<!--</pre>-->";
}
