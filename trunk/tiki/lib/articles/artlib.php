<?php

class ArtLib extends TikiLib {
	function ArtLib($db) {
		# this is probably uneeded now
		if (!$db) {
			die ("Invalid db object passed to ArtLib constructor");
		}

		$this->db = $db;
	}

	// 29-Jun-2003, by zaufi
	// The 2 functions below contain duplicate code
	// to remove <PRE> tags... (moreover I copy this code
	// from tikilib.php, and paste to artlib.php, bloglib.php
	// and wikilib.php)
	// TODO: it should be separate function to avoid
	// maintain 3 pieces... (but I don't know PHP and TIKI
	// architecture very well yet to make this :()

	//Special parsing for multipage articles
	function get_number_of_pages($data) {
		// Temporary remove <PRE></PRE> secions to protect
		// from broke <PRE> tags and leave well known <PRE>
		// behaviour (i.e. type all text inside AS IS w/o
		// any interpretation)
		$preparsed = array();

		preg_match_all("/(<[Pp][Rr][Ee]>)((.|\n)*?)(<\/[Pp][Rr][Ee]>)/", $data, $preparse);
		$idx = 0;

		foreach (array_unique($preparse[2])as $pp) {
			$key = md5($this->genPass());

			$aux["key"] = $key;
			$aux["data"] = $pp;
			$preparsed[] = $aux;
			$data = str_replace($preparse[1][$idx] . $pp . $preparse[4][$idx], $key, $data);
			$idx = $idx + 1;
		}

		$parts = explode("...page...", $data);
		return count($parts);
	}

	function get_page($data, $i) {
		// Temporary remove <PRE></PRE> secions to protect
		// from broke <PRE> tags and leave well known <PRE>
		// behaviour (i.e. type all text inside AS IS w/o
		// any interpretation)
		$preparsed = array();

		preg_match_all("/(<[Pp][Rr][Ee]>)((.|\n)*?)(<\/[Pp][Rr][Ee]>)/", $data, $preparse);
		$idx = 0;

		foreach (array_unique($preparse[2])as $pp) {
			$key = md5($this->genPass());

			$aux["key"] = $key;
			$aux["data"] = $pp;
			$preparsed[] = $aux;
			$data = str_replace($preparse[1][$idx] . $pp . $preparse[4][$idx], $key, $data);
			$idx = $idx + 1;
		}

		// Get slides
		$parts = explode("...page...", $data);

		if (substr($parts[$i - 1], 1, 5) == "<br/>")
			$ret = substr($parts[$i - 1], 6);
		else
			$ret = $parts[$i - 1];

		// Replace back <PRE> sections
		foreach ($preparsed as $pp)
			$ret = str_replace($pp["key"], "<pre>" . $pp["data"] . "</pre>", $ret);

		return $ret;
	}

	function approve_submission($subId) {
		$data = $this->get_submission($subId);

		if (!$data)
			return false;

		if (!$data["image_x"])
			$data["image_x"] = 0;

		if (!$data["image_y"])
			$data["image_y"] = 0;

		$this->replace_article($data["title"], $data["authorName"], $data["topicId"], $data["useImage"], $data["image_name"],
			$data["image_size"], $data["image_type"], $data["image_data"], $data["heading"], $data["body"], $data["publishDate"],
			$data["author"], 0, $data["image_x"], $data["image_y"], $data["type"], $data["rating"]);
		$this->remove_submission($subId);
	}

	function add_article_hit($articleId) {
		global $count_admin_pvs;

		global $user;

		if ($count_admin_pvs == 'y' || $user != 'admin') {
			$query = "update `tiki_articles` set `reads`=`reads`+1 where `articleId`=?";

			$result = $this->query($query,array($articleId));
		}

		return true;
	}

	function remove_article($articleId) {
		if ($articleId) {
			$query = "delete from `tiki_articles` where `articleId`=?";

			$result = $this->query($query,array($articleId));
			$this->remove_object('article', $articleId);
			return true;
		}
	}

	function remove_submission($subId) {
		if ($subId) {
			$query = "delete from `tiki_submissions` where `subId`=$subId";

			$result = $this->query($query,array($subId));

			return true;
		}
	}

	function replace_submission($title, $authorName, $topicId, $useImage, $imgname, $imgsize, $imgtype, $imgdata, $heading, $body,
		$publishDate, $user, $subId, $image_x, $image_y, $type, $rating = 0, $isfloat = 'n') {
		global $smarty;

		global $dbTiki;
		global $sender_email;
		include_once ('lib/notifications/notificationlib.php');
		$hash = md5($title . $heading . $body);
		$now = date("U");
		$query = "select `name` from `tiki_topics` where `topicId` = ?";
		$topicName = $this->getOne($query,array($topicId));
		$size = strlen($body);

		if ($subId) {
			// Update the article
			$query = "update `tiki_submissions` set
                `title` = ?,
                `authorName` = ?,
                `topicId` = ?,
                `topicName` = ?,
                `size` = ?,
                `useImage` = ?,
                `isfloat` = ?,
                `image_name` = ?,
                `image_type` = ?,
                `image_size` = ?,
                `image_data` = ?,
                `image_x` = ?,
                `image_y` = ?,
                `heading` = ?,
                `body` = ?,
                `publishDate` = ?,
                `created` = ?,
                `author` = ? ,
                `type` = ?,
                `rating` = ?
                where `subId` = ?";

			$result = $this->query($query,array($title,$authorName,$topicId,$topicName,$size,$useImage,$isfloat,$imgname,$imgtype,$imgsize,$imgdata,$image_x,$image_y,$heading,$body,$publishDate,$now,$user,$type,$rating,$subId));
		} else {
			// Insert the article
			$query = "insert into `tiki_submissions`(`title`,`authorName`,`topicId`,`useImage`,`image_name`,`image_size`,`image_type`,`image_data`,`publishDate`,`created`,`heading`,`body`,`hash`,`author`,`reads`,`votes`,`points`,`size`,`topicName`,`image_x`,`image_y`,`type`,`rating`,`isfloat`)
                         values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

			$result = $this->query($query,array($title,$authorName,$topicId,$useImage,$imgname,$imgsize,$imgtype,$imgdata,$publishDate,$now,$heading,$body,$hash,$user,0,0,0,$size,$topicName,$image_x,$image_y,$type,$rating,$isfloat));
		}

		$query = "select max(`subId`) from `tiki_submissions` where `created` = ? and `title`=? and `hash`=?";
		$id = $this->getOne($query,array($now,$title,$hash));
		$emails = $notificationlib->get_mail_events('article_submitted', '*');
		$foo = parse_url($_SERVER["REQUEST_URI"]);
		$machine = httpPrefix(). $foo["path"];

		foreach ($emails as $email) {
			$smarty->assign('mail_site', $_SERVER["SERVER_NAME"]);

			$smarty->assign('mail_user', $user);
			$smarty->assign('mail_title', $title);
			$smarty->assign('mail_heading', $heading);
			$smarty->assign('mail_body', $body);
			$smarty->assign('mail_date', date("U"));
			$smarty->assign('mail_machine', $machine);
			$smarty->assign('mail_subId', $id);
			$mail_data = $smarty->fetch('mail/submission_notification.tpl');
			@mail($email, tra('New article submitted at '). $_SERVER["SERVER_NAME"], $mail_data,
				"From: $sender_email\r\nContent-type: text/plain;charset=utf-8\r\n");
		}

		return $id;
	}

	function add_topic($name, $imagename, $imagetype, $imagesize, $imagedata) {
		$now = date("U");

		$query = "insert into `tiki_topics`(`name`,`image_name`,`image_type`,`image_size`,`image_data`,`active`,`created`)
                     values(?,?,?,?,?,?,?)";
		$result = $this->query($query,array($name,$imagename,$imagetype,$imagesize,$imagedata,'y',$now));

		$query = "select max(`topicId`) from `tiki_topics` where `created`=? and `name`=?";
		$topicId = $this->getOne($query,array($now,$name));
		return $topicId;
	}

	function remove_topic($topicId) {
		$query = "delete from `tiki_topics` where `topicId`=?";

		$result = $this->query($query,array($topicId));

		$query = "delete from `tiki_articles` where `topicId`=?";
		$result = $this->query($query,array($topicId));

		return true;
	}

	function replace_topic_name($topicId, $name) {
		$query = "update `tiki_topics` set `name` = ? where
			`topicId` = ?";
		$result = $this->query($query, array($name, $topicId));

		return true;
	}

	function replace_topic_image($topicId, $imagename, $imagetype,
			$imagesize, $imagedata) {
		$topicId = (int)$topicId;
		$query = "update `tiki_topics` set `image_name` = ?,
			`image_type` = ?, `image_size` = ?,  `image_data` = ? 
				where `topicId` = ?";
		$result = $this->query($query, array($imagename, $imagetype,
					$imagesize, $imagedata, $topicId));

		return true;
	}

	function activate_topic($topicId) {
		$query = "update `tiki_topics` set `active`=? where `topicId`=?";

		$result = $this->query($query,array('y',$topicId));
	}

	function deactivate_topic($topicId) {
		$query = "update `tiki_topics` set `active`=? where `topicId`=?";

		$result = $this->query($query,array('n',$topicId));
	}

	function get_topic($topicId) {
		$query = "select `topicId`,`name`,`image_name`,`image_size`,`image_type` from `tiki_topics` where `topicId`=?";

		$result = $this->query($query,array($topicId));

		$res = $result->fetchRow();
		return $res;
	}

	function list_topics() {
		$query = "select `topicId`,`name`,`image_name`,`image_size`,`image_type`,`active` from `tiki_topics` order by `name`";

		$result = $this->query($query,array());

		$ret = array();

		while ($res = $result->fetchRow()) {
			$res["subs"] = $this->getOne("select count(*) from `tiki_submissions` where `topicId`=?",array($res["topicId"]));

			$res["arts"] = $this->getOne("select count(*) from `tiki_articles` where `topicId`=?",array($res["topicId"]));
			$ret[] = $res;
		}

		return $ret;
	}

	function list_active_topics() {
		$query = "select * from `tiki_topics` where `active`=?";

		$result = $this->query($query,array('y'));

		$ret = array();

		while ($res = $result->fetchRow()) {
			$ret[] = $res;
		}

		return $ret;
	}
}

$artlib = new ArtLib($dbTiki);

?>
