<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-tests.php,v 1.8 2005-01-01 00:16:35 damosoft Exp $

// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

//foo
include_once ('tiki-setup.php');

include_once ('PHPUnit.php');

$h = opendir('tests');

while ($file = readdir($h)) {
	if (strstr($file, '.php')) {
		print ("<b>$file</b>");

		include_once ("tests/$file");
	}
}

closedir ($h);
?>
