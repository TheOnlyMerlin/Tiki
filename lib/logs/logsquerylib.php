<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

class LogsQueryLib
{
	var $type = "";
	var $id = "";
	var $action = "";
	var $start = "";
	var $end = "";
	var $client = "";
	var $groupType = null;
	var $limit = null;
	var $desc = true;
	
	static function type($type = "") {
		$me = new self();
		$me->type = $type;
		return $me;
	}
	
	static function wikiPage($id = "") {
		return LogsQueryLib::type("wiki page")
			->id($id);
	}
	
	static function wikiPagesFrom($user= "") {
		return LogsQueryLib::type("wiki page")
			->id($id);
	}
	
	static function forum($id = "") {
		return LogsQueryLib::type("forum")
			->id($id);
	}
	
	static function fileGallery($id = "") {
		return LogsQueryLib::type("file gallery")
			->id($id);
	}
	
	static function imageGallery($id = "") {
		return LogsQueryLib::type("image gallery")
			->id($id);
	}
	
	static function category($id = "") {
		return LogsQueryLib::type("category")
			->id($id);
	}
	
	static function system($id = "") {
		return LogsQueryLib::type("system")
			->id($id);
	}
	
	static function message($id = "") {
		return LogsQueryLib::type("message")
			->id($id);
	}
	
	static function comment($id = "") {
		return LogsQueryLib::type("comment")
			->id($id);
	}
	
	static function sheet($id = "") {
		return LogsQueryLib::type("sheet")
			->id($id);
	}
	
	static function blog($id = "") {
		return LogsQueryLib::type("blog")
			->id($id);
	}
	
	static function file($id = "") {
		return LogsQueryLib::type("file")
			->id($id);
	}
	
	static function article($id = "") {
		return LogsQueryLib::type("article")
			->id($id);
	}
	
	static function trackerItem($id = "") {
		return LogsQueryLib::type("trackeritem")
			->id($id);
	}
	
	static function wikiPageAttachment($id = "") {
		return LogsQueryLib::type("wiki page attachment")
			->id($id);
	}
	
	static function url($id = "") {
		return LogsQueryLib::type("url")
			->id($id);
	}
	
	function id($id = "") {
		$this->id = $id;
		return $this;
	}
	
	function viewed() {
		return $this->action("viewed");
	}
	
	function action($action) {
		$this->action = $action;
		return $this;
	}
	
	function start($start) {
		$this->start = $start;
		return $this;
	}
	
	function end($end) {
		$this->end = $end;
		return $this;
	}
	
	function client($client) {
		$this->client = $client;
		return $this;
	}
	
	function count() {
		$this->groupType = "count";
		return $this->fetchAll();
	}
	
	function countByDate() {
		$this->groupType = "countByDate";
		return $this->fetchAll();
	}
	
	function limit($limit) {
		$this->limit = $limit;
		return $this;
	}
	
	function desc() {
		$this->desc = true;
		return $this;
	}
	
	function asc() {
		$this->desc = false;
		return $this;
	}
	
	function fetchAll() {
		global $tikilib;
		
		if (empty($this->type)) return array();
		
		
		$query = "
			SELECT
				".($this->groupType == "count" ? " COUNT(actionId) as count " : "")."
				".($this->groupType == "countByDate" ? " COUNT(actionId) AS count, DATE_FORMAT(FROM_UNIXTIME(lastModif), '%m/%d/%Y') as date " : "")."
				".(empty($this->groupType) ? " * " : "")."
			FROM
				tiki_actionlog
			WHERE
				objectType = ?
				".(
					!empty($this->id) ? " AND object = ? " : ""
				)."
				".(
					!empty($this->action) ? " AND action = ? " : ""
				)."
				".(
					!empty($this->start) ? " AND lastModif > ? " : ""
				)."
				".(
					!empty($this->end) ? " AND lastModif < ? " : ""
				)."
				".(
					!empty($this->client) ? " AND client = ? " : ""
				)."
			
			".($this->groupType == "countByDate" ? " GROUP BY DATE_FORMAT(FROM_UNIXTIME(lastModif), '%Y%m%d') " : "")."
			
			ORDER BY lastModif ". ($this->desc == true ? "DESC" : "ASC") ."
			
			".(!empty($this->limit) ? 
				" LIMIT ".$this->limit
				: ""
			)."
		";
		
		$params = array($this->type);
		
		if (!empty($this->id)) $params[] = $this->id;
		if (!empty($this->action)) $params[] = $this->action;
		if (!empty($this->start)) $params[] = $this->start;
		if (!empty($this->end)) $params[] = $this->end;
		if (!empty($this->client)) $params[] = $this->client;
		
		if ($this->groupType == "count") {
			return $tikilib->getOne($query, $params);
		} else {
			return $tikilib->fetchAll($query, $params);
		}
	}
}

$logsqrylib = new LogsQueryLib;
