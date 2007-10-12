<?php

// $Header: /cvsroot/tikiwiki/tiki/topic_image.php,v 1.17 2007-10-12 07:55:33 nyloth Exp $

// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

# $Header: /cvsroot/tikiwiki/tiki/topic_image.php,v 1.17 2007-10-12 07:55:33 nyloth Exp $

// application to display an image from the database with 
// option to resize the image dynamically creating a thumbnail on the fly.
if (!isset($_REQUEST["id"])) {
	die;
}

include_once ('lib/init/initlib.php');
include_once ('tiki-setup_base.php');

$topiccachefile = $prefs['tmpDir'];
if ($tikidomain) { $topiccachefile.= "/$tikidomain"; }
$topiccachefile.= "/topic.".$_REQUEST["id"];

if (is_file($topiccachefile) and (!isset($_REQUEST["reload"]))) {
	$size = getimagesize($topiccachefile);
	header ("Content-type: ".$size['mime']);
	readfile($topiccachefile);
	die();
} else {
	$data = $tikilib->get_topic_image($_REQUEST["id"]);
	// if blank then die, otherwise we offer to download strangeness
	// this also catches invalid id's
	if (!$data) {
		die;
	}
	$type = $data["image_type"];
	$data = $data["image_data"];
	if ($data["image_data"]) {
		$fp = fopen($topiccachefile,"wb");
		fputs($fp,$data);
		fclose($fp);
	}
}

header ("Content-type: $type");
if (is_file($topiccachefile)) {
	readfile($topiccachefile);
} else {
	echo $data;
}

?>
