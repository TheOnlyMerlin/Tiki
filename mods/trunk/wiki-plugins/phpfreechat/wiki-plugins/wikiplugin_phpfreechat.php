<?php

function wikiplugin_phpfreechat_help() {
        return tra("Displays a chat (using phpfreechat) in a wiki page").":<br />~np~{PHPFREECHAT(title=title,channel=name)}{PHPFREECHAT}~/np~";
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

	return $chat->printChat(true);
}

?>
