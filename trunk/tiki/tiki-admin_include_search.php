<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-admin_include_search.php,v 1.3 2004-03-27 21:23:52 mose Exp $

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  die("This script cannot be called directly");
}


if (isset($_REQUEST["searchprefs"])) {
	check_ticket('admin-inc-search');
	simple_set_int("search_refresh_rate");
	simple_set_int("search_min_wordlength");
	simple_set_int("search_max_syllwords");
	simple_set_int("search_syll_age");
	simple_set_int("search_lru_purge_rate");
	simple_set_int("search_lru_length");
}
ask_ticket('admin-inc-search');
?>
