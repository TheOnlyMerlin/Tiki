<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: Controller.php 39180 2011-12-14 12:04:12Z lphuberdeau $

class Services_Draw_Controller
{
	function setUp()
	{
		global $prefs;

		if ($prefs['feature_file_galleries'] != 'y') {
			throw new Services_Exception_Disabled('feature_file_galleries');
		}
		
		if ($prefs['feature_draw'] != 'y') {
			throw new Services_Exception_Disabled('feature_draw');
		}
	}

	function action_edit($input)
	{
		global $tikilib, $access, $headerlib, $drawFullscreen;
		$drawFullscreen = true;
		
		$_REQUEST['fileId'] = $input->fileId->int();
		$_REQUEST['galleryId'] = $input->galleryId->int();
		
		include_once 'tiki-edit_draw.php';
	}
}

