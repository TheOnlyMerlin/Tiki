<?php

include_once('lib/webmail/htmlMimeMail.php');
include_once('lib/webmail/encodestring.php');

class Messu extends Tikilib {
	var $db;

	function Messu($db) {
		if (!$db) {
			die ("Invalid db object passed to MessuLib constructor");
		}

		$this->db = $db;
	}

	function user_exists($user) {
		global $userlib;

		return $userlib->user_exists($user);
	}

	function post_message($user, $from, $to, $cc, $subject, $body, $priority) {
		global $smarty, $userlib, $sender_email, $language;

		$subject = strip_tags($subject);
		$body = strip_tags($body, '<a><b><img><i>');
		// Prevent duplicates
		$hash = md5($subject . $body);

		if ($this->getOne("select count(*) from `messu_messages` where `user`=? and `user_from`=? and `hash`=?",array($user,$from,$hash))) {
			return false;
		}

		$now = date('U');
		$query = "insert into `messu_messages`(`user`,`user_from`,`user_to`,`user_cc`,`subject`,`body`,`date`,`isRead`,`isReplied`,`isFlagged`,`priority`,`hash`) values(?,?,?,?,?,?,?,?,?,?,?,?)";
		$this->query($query,array($user,$from,$to,$cc,$subject,$body,(int) $now,'n','n','n',(int) $priority,$hash));

		// Now check if the user should be notified by email
		$foo = parse_url($_SERVER["REQUEST_URI"]);
		$machine = httpPrefix(). $foo["path"];

		if ($this->get_user_preference($user, 'minPrio', 6) <= $priority) {
			$smarty->assign('mail_site', $_SERVER["SERVER_NAME"]);
			$smarty->assign('mail_machine', $machine);
			$smarty->assign('mail_date', date("U"));
			$smarty->assign('mail_user', stripslashes($user));
			$smarty->assign('mail_from', stripslashes($from));
			$smarty->assign('mail_subject', stripslashes($subject));
			$smarty->assign('mail_body', stripslashes($body));
			$mail_data = $smarty->fetch('mail/messu_message_notification.tpl');
			$email = $userlib->get_user_email($user); echo "eeee";
			if ($email) {
				$mailCharset = $this->get_user_preference($user, 'mailCharset', 'utf-8');
				$mail = new htmlMimeMail();
				$mail->setFrom($sender_email);
				$mail->setSubject(encodeString(tra("New message arrived from ", $this->get_user_preference($user, 'language', $language)). $_SERVER["SERVER_NAME"], $mailCharset));
				$mail->setHeadCharset($mailCharset);
				$mail->setText(encodeString($mail_data, $mailCharset));
				$mail->setTextCharset($mailCharset);
				if (!$mail->send(array($email), 'mail'))
					return false; //TODO echo $mail->errors;
			}
		}

		return true;
	}

	function validate_user($user, $pass) {
		global $userlib;

		$cant = $userlib->validate_user($user, $pass, '', '');
		return $cant;
	}

	function list_user_messages($user, $offset, $maxRecords, $sort_mode, $find, $flag = '', $flagval = '', $prio = '') {
		$bindvars = array($user);
		$mid="";
		if ($prio) {
			$mid = " and priority=? ";
			$bindvars[] = $prio;
		}

		if ($flag) {
			// Process the flags
			$mid.= " and `$flag`=? ";
			$bindvars[] = $flagval;
		}
		if ($find) {
			$findesc = '%'.$find.'%';
			$mid.= " and (`subject` like ? or `body` like ?)";
			$bindvars[] = $findesc;
			$bindvars[] = $findesc;
		}

		$query = "select * from `messu_messages` where `user`=? $mid order by ".$this->convert_sortmode($sort_mode).",".$this->convert_sortmode("msgId_desc");
		$query_cant = "select count(*) from `messu_messages` where `user`=? $mid";
		$result = $this->query($query,$bindvars,$maxRecords,$offset);
		$cant = $this->getOne($query_cant,$bindvars);
		$ret = array();

		while ($res = $result->fetchRow()) {
			$res["len"] = strlen($res["body"]);

			if (empty($res['subject']))
				$res['subject'] = tra('NONE');

			$ret[] = $res;
		}

		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}

	function flag_message($user, $msgId, $flag, $val) {
		if (!$msgId)
			return false;
		$query = "update `messu_messages` set `$flag`=? where `user`=? and `msgId`=?";
		$this->query($query,array($val,$user,(int)$msgId));
	}

	function delete_message($user, $msgId) {
		if (!$msgId)
			return false;
		$query = "delete from `messu_messages` where `user`=? and `msgId`=?";
		$this->query($query,array($user,(int)$msgId));
	}

	function get_next_message($user, $msgId, $sort_mode, $find, $flag, $flagval, $prio) {
		if (!$msgId)
			return 0;
		
		$mid = "";
		$bindvars = array($user,(int)$msgId);
		if ($prio) {
			$mid.= " and priority=? ";
			$bindvars[] = $prio;
		}

		if ($flag) {
			// Process the flags
			$mid.= " and `$flag`=? ";
			$bindvars[] = $flagval;
		}
		if ($find) {
			$findesc = '%'.$find.'%';
			$mid.= " and (`subject` like ? or `body` like ?)";
			$bindvars[] = $findesc;
			$bindvars[] = $findesc;
		}

		$query = "select min(`msgId`) as `nextmsg` from `messu_messages` where `user`=? and `msgId` > ? $mid ";
		$result = $this->query($query,$bindvars,1,0);
		$res = $result->fetchRow();

		if (!$res)
			return false;
		return $res['nextmsg'];
	}

	function get_prev_message($user, $msgId, $sort_mode, $find, $flag, $flagval, $prio) {
		if (!$msgId)
			return 0;
		
		$bindvars = array($user,(int)$msgId);
		$mid="";
		if ($prio) {
			$mid.= " and priority=? ";
			$bindvars[] = $prio;
		}

		if ($flag) {
			// Process the flags
			$mid.= " and `$flag`=? ";
			$bindvars[] = $flagval;
		}
		if ($find) {
			$findesc = '%'.$find.'%';
			$mid.= " and (`subject` like ? or `body` like ?)";
			$bindvars[] = $findesc;
			$bindvars[] = $findesc;
		}
		$query = "select max(`msgId`) as `prevmsg` from `messu_messages` where `user`=? and `msgId` < ? $mid";
		$result = $this->query($query,$bindvars,1,0);
		$res = $result->fetchRow();

		if (!$res)
			return false;

		return $res['prevmsg'];
	}

	function get_message($user, $msgId) {
		$bindvars = array($user,(int)$msgId);
		$query = "select * from `messu_messages` where `user`=? and `msgId`=?";
		$result = $this->query($query,$bindvars);
		$res = $result->fetchRow();
		$res['parsed'] = $this->parse_data($res['body']);

		if (empty($res['subject']))
			$res['subject'] = tra('NONE');

		return $res;
	}
}

$messulib = new Messu($dbTiki);

?>
