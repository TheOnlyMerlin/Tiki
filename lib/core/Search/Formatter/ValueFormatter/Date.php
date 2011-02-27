<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Search_Formatter_ValueFormatter_Date implements Search_Formatter_ValueFormatter_Interface
{
	function render($value, array $entry)
	{
		global $prefs, $tikilib;
		return $tikilib->date_format($prefs['short_date_format'], $value);
	}
}

