<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-remind_password.php,v 1.32 2007-07-18 14:05:01 sylvieg Exp $

// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once ('tiki-setup.php');

if ($forgotPass != 'y') {
	$smarty->assign('msg', tra("This feature is disabled").": forgotPass");
	$smarty->display("error.tpl");
	die;
}

$smarty->assign('showmsg', 'n');
$smarty->assign('showfrm', 'y');

$isvalid = false;
if (isset($_REQUEST["user"])) {
	// this is a 'new password activation':
	if (isset($_REQUEST["actpass"])) {
		$oldPass = $userlib->activate_password($_REQUEST["user"], $_REQUEST["actpass"]);
		if ($oldPass) {
			header ("location: tiki-change_password.php?user=". urlencode($_REQUEST["user"])."&oldpass=".$oldPass);
			die;
		}
		$smarty->assign('msg', tra("Invalid username or activation code. Maybe this code has already been used."));
		$smarty->display("error.tpl");
		die;
	}
}

if (isset($_REQUEST["remind"])) {
	if (!empty($_REQUEST['name'])) {
		if (!$userlib->user_exists($_REQUEST['name'])) {
			$showmsg = 'e';
			$smarty->assign('msg', tra('Invalid or unknown username'). ': ' . $_REQUEST['name']);
		} else {
			if (!($_REQUEST['email'] = $userlib->get_user_email($_REQUEST['name']))) {
				$showmsg = 'e';
				$smarty->assign('msg', tra('Unable to send mail. User has not configured email'));
			}
		}
	} else if (!empty($_REQUEST['email'])) {
		if (!($_REQUEST['name'] = $userlib->get_user_by_email($_REQUEST['email']))) {
			$showmsg = 'e';
			$smarty->assign('msg', tra('Invalid or unknown email'). ': ' . $_REQUEST['email']);
		}
	} else {
		$showmsg = 'e';
	}
	if (isset($showmsg) && $showmsg == 'e') {
		$smarty->assign('showmsg', 'e');
	} else {
		include_once ('lib/webmail/tikimaillib.php');
		$name = $_REQUEST['name'];
		if ($feature_clear_passwords == 'y') {
			$pass = $userlib->get_user_password($name);
			$smarty->assign('clearpw', 'y');
		} else {
			$pass = $userlib->renew_user_password($name);
			$smarty->assign('clearpw', 'n');
		}

		$languageEmail = $tikilib->get_user_preference($name, "language", $language);
		
		// Now check if the user should be notified by email
		$foo = parse_url($_SERVER["REQUEST_URI"]);
		$machine = $tikilib->httpPrefix(). dirname($foo["path"]);
		$machine = preg_replace("!/$!", "", $machine); // just incase
		$smarty->assign('mail_machine', $machine);

		$smarty->assign('mail_site', $_SERVER["SERVER_NAME"]);
		$smarty->assign('mail_user', $name);
		$smarty->assign('mail_same', $feature_clear_passwords);
		$smarty->assign('mail_pass', $pass);
		$smarty->assign('mail_apass', md5($pass));
		$smarty->assign('mail_ip', $_SERVER['REMOTE_ADDR']);
		$mail_data = sprintf($smarty->fetchLang($languageEmail, 'mail/password_reminder_subject.tpl'),$_SERVER["SERVER_NAME"]);
		$mail = new TikiMail($name);
		$mail->setSubject(sprintf($mail_data, $_SERVER["SERVER_NAME"]));
		$mail->setText($smarty->fetchLang($languageEmail, 'mail/password_reminder.tpl'));
		if (!$mail->send(array($_REQUEST['email']))) {
			$smarty->assign('msg', tra("The mail can't be sent. Contact the administrator"));
	    	$smarty->display("error.tpl");
    		die;
		}

		// Just show "success" message and no form
		$smarty->assign('showmsg', 'y');
		$smarty->assign('showfrm', 'n');

		if ($feature_clear_passwords == 'y') {
			$tmp = tra("A password and your IP address reminder email has been sent ");
		} else {
			$tmp = tra("A new (and temporary) password and your IP address has been sent ");
		}

		$tmp .= tra("to the registered email address for");
		$tmp .= " " . $name. ".";
		$smarty->assign('msg', $tmp);
	}
}

// disallow robots to index page:
$smarty->assign('metatag_robots', 'NOINDEX, NOFOLLOW');

// Display the template
$smarty->assign('mid', 'tiki-remind_password.tpl');
$smarty->display("tiki.tpl");

?>
