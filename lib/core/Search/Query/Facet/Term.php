<?php
// (c) Copyright 2002-2014 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Search_Query_Facet_Term implements Search_Query_Facet_Interface
{
	private $field;
	private $renderCallback;
	private $operator = 'or';

	static function fromField($field)
	{
		return new self($field);
	}

	function __construct($field)
	{
		$this->field = $field;
		$this->label = ucfirst($field);
	}

	function getName()
	{
		return $this->field;
	}

	function getField()
	{
		return $this->field;
	}

	function getLabel()
	{
		return $this->label;
	}

	function setLabel($label)
	{
		$this->label = $label;
		return $this;
	}

	function setRenderCallback($callback)
	{
		$this->renderCallback = $callback;
		return $this;
	}

	function setRenderMap(array $map)
	{
		return $this->setRenderCallback(
			function ($value) use ($map) {
				if (isset($map[$value])) {
					return $map[$value];
				} else {
					return $value;
				}
			}
		);
	}

	function render($value)
	{
		if ($cb = $this->renderCallback) {
			return call_user_func($cb, $value);
		}

		return $value;
	}

	function setOperator($operator)
	{
		$this->operator = in_array($operator, array('and', 'or')) ? $operator : 'or';
		return $this;
	}

	function getOperator()
	{
		return $this->operator;
	}
}

