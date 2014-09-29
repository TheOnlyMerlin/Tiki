<?php
// (c) Copyright 2002-2014 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

namespace Tracker\Tabular\Schema;

class DateHelper
{
	private $label;

	function __construct($label)
	{
		$this->label = $label;
	}

	function setupUnix(Column $column)
	{
		$permName = $column->getField();
		$this->setupCallbacks(
			$column,
			function ($value, array $extra) {
				if (empty($value)) {
					return '';
				}

				return $this->convertToUnix($value);
			},
			function (& $info, $value) use ($permName) {
				$info['fields'][$permName] = $value;
			}
		);
	}
	
	function setupFormat($format, Column $column)
	{
		$permName = $column->getField();
		$this->setupCallbacks(
			$column,
			function ($value, array $extra) use ($format) {
				if (empty($value)) {
					return '';
				}

				$unix = $this->convertToUnix($value);
				return date($format, $unix);
			},
			function (& $info, $value) use ($permName, $format) {
				$date = date_create_from_format($format, $value);
				if ($date) {
					$info['fields'][$permName] = $date->getTimestamp();
				}
			}
		);
	}

	private function setupCallbacks($column, callable $render, callable $parseInto)
	{
		$column
			->setLabel($this->label)
			->setRenderTransform($render)
			->setParseIntoTransform($parseInto)
			;
	}

	private function convertToUnix($value)
	{
		if (preg_match('/^\d{14}$/', $value)) {
			return date_create_from_format('YmdHise', $value . 'UTC')->getTimestamp();
		} elseif (is_numeric($value)) {
			return (int) $value;
		} else {
			return strtotime($value);
		}
	}
}

