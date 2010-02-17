<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

require_once 'lib/core/lib/Perms/Resolver.php';

/**
 * Simple resolver always providing the same answer. Primarly
 * used for testing purposes, but also used as the administrator
 * resolver.
 */
class Perms_Resolver_Default implements Perms_Resolver
{
	private $value;

	function __construct( $value ) {
		$this->value = (bool) $value;
	}

	function check( $name, array $groups ) {
		return $this->value;
	}
}
