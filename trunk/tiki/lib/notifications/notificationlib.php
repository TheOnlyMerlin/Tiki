<?php

class NotificationLib extends TikiLib {
	function NotificationLib($db) {
		# this is probably uneeded now
		if (!$db) {
			die ("Invalid db object passed to NotificationLib constructor");
		}

		$this->db = $db;
	}

	function list_mail_events($offset, $maxRecords, $sort_mode, $find) {
		$sort_mode = str_replace("_", " ", $sort_mode);

		if ($find) {
			$findesc = $this->qstr('%' . $find . '%');

			$mid = " where (event like $findesc or email like $findesc)";
		} else {
			$mid = " ";
		}

		$query = "select * from tiki_mail_events $mid order by $sort_mode limit $offset,$maxRecords";
		$query_cant = "select count(*) from tiki_mail_events $mid";
		$result = $this->query($query);
		$cant = $this->getOne($query_cant);
		$ret = array();

		while ($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$ret[] = $res;
		}

		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}

	function add_mail_event($event, $object, $email) {
		$object = addslashes($object);

		$email = addslashes($email);
		$query = "replace into tiki_mail_events(event,object,email) values('$event','$object','$email')";
		$result = $this->query($query);
	}

	function remove_mail_event($event, $object, $email) {
		$object = addslashes($object);

		$query = "delete from tiki_mail_events where event='$event' and object='$object' and email='$email'";
		$result = $this->query($query);
	}

	function get_mail_events($event, $object) {
		$object = addslashes($object);

		$query = "select email from tiki_mail_events where event='$event' and object='$object'";
		$result = $this->query($query);
		$ret = array();

		while ($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$ret[] = $res["email"];
		}

		return $ret;
	}
}

$notificationlib = new NotificationLib($dbTiki);

?>