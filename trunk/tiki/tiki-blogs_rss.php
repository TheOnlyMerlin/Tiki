<?php
// $Header: /cvsroot/tikiwiki/tiki/tiki-blogs_rss.php,v 1.23 2004-01-15 09:56:26 redflo Exp $

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

require_once ('tiki-setup.php');
require_once ('lib/tikilib.php');
include_once ('lib/blogs/bloglib.php');

if ($rss_blogs != 'y') {
        $errmsg=tra("rss feed disabled");
        require_once ('tiki-rss_error.php');
}

if ($tiki_p_read_blog != 'y') {
        $errmsg=tra("Permission denied you cannot view this section");
        require_once ('tiki-rss_error.php');
}

$feed = "blogs";
$title = "Tiki RSS feed for weblogs"; // TODO: make configurable
$desc = "Last posts to weblogs."; // TODO: make configurable
$now = date("U");
$id = "blogId";
$descId = "data";
$dateId = "created";
$titleId = "blogtitle";
$readrepl = "tiki-view_blog_post.php?$id=";

require ("tiki-rss_readcache.php");

if ($output == "EMPTY") {
  $changes = $bloglib -> list_all_blog_posts(0, $max_rss_blogs, $dateId.'_desc', '', $now);
  $output="";
}

require ("tiki-rss.php");

?>
