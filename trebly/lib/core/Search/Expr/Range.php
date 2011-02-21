<?php

class Search_Expr_Range implements Search_Expr_Interface
{
	private $from;
	private $to;
	private $type;
	private $field;

	function __construct($from, $to, $type = null, $field = null)
	{
		$this->from = $from;
		$this->to = $to;
		$this->type = $type;
		$this->field = $field;
	}

	function getToken($which)
	{
		if ($which != 'from' && $which != 'to') {
			return null;
		}
		
		return new Search_Expr_Token($this->$which, $this->type, $this->field);
	}

	function setType($type)
	{
		$this->type = $type;
	}

	function setField($field = 'global')
	{
		$this->field = $field;
	}

	function walk($callback)
	{
		return call_user_func($callback, $this, array());
	}

	function getValue(Search_Type_Factory_Interface $typeFactory)
	{
		$type = $this->type;
		return $typeFactory->$type($this->string);
	}

	function getField()
	{
		return $this->field;
	}
}

