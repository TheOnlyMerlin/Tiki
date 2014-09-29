<?php
// (c) Copyright 2002-2014 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

namespace Tracker\Tabular\Exception;

class ModeNotSupported extends Exception
{
	private $mode;
	private $permName;

	function __construct($permName, $mode)
	{
		parent::__construct(tr('Field mode not found: %0 for %1', $mode, $permName));
		$this->mode = $mode;
		$this->permName = $permName;
	}
}

