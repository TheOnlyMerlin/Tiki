<?php

// $Header: /cvsroot/tikiwiki/tiki/lib/pear/HTTP/index.php,v 1.5 2005-05-18 11:01:11 mose Exp $

// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// This redirects to the sites root to prevent directory browsing

header ("location: ../index.php");
die;

?>
