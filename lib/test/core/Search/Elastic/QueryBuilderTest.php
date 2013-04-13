<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

use Search_Elastic_QueryBuilder as QueryBuilder;
use Search_Expr_Token as Token;
use Search_Expr_And as AndX;
use Search_Expr_Or as OrX;
use Search_Expr_Not as NotX;
use Search_Expr_Range as Range;

class Search_Elastic_QueryBuilderTest extends PHPUnit_Framework_TestCase
{
	function testSimpleQuery()
	{
		$builder = new QueryBuilder;

		$query = $builder->build(new Token('Hello', 'plaintext', 'contents', 1.5));

		$this->assertEquals(array("query" => array(
			"term" => array(
				"contents" => array("value" => "hello", "boost" => 1.5),
			),
		)), $query);
	}

	function testQueryWithSinglePart()
	{
		$builder = new QueryBuilder;

		$query = $builder->build(new AndX(array(
			new Token('Hello', 'plaintext', 'contents', 1.5),
		)));

		$this->assertEquals(array("query" => array(
			"term" => array(
				"contents" => array("value" => "hello", "boost" => 1.5),
			),
		)), $query);
	}

	function testBuildOrQuery()
	{
		$builder = new QueryBuilder;

		$query = $builder->build(new OrX(array(
			new Token('Hello', 'plaintext', 'contents', 1.5),
			new Token('World', 'plaintext', 'contents', 1.0),
		)));

		$this->assertEquals(array("query" => array(
			"bool" => array(
				"should" => array(
					array(
						"term" => array(
							"contents" => array("value" => "hello", "boost" => 1.5),
						),
					),
					array(
						"term" => array(
							"contents" => array("value" => "world", "boost" => 1.0),
						),
					),
				),
				"minimum_number_should_match" => 1,
			),
		)), $query);
	}

	function testAndQuery()
	{
		$builder = new QueryBuilder;

		$query = $builder->build(new AndX(array(
			new Token('Hello', 'plaintext', 'contents', 1.5),
			new Token('World', 'plaintext', 'contents', 1.0),
		)));

		$this->assertEquals(array("query" => array(
			"bool" => array(
				"must" => array(
					array(
						"term" => array(
							"contents" => array("value" => "hello", "boost" => 1.5),
						),
					),
					array(
						"term" => array(
							"contents" => array("value" => "world", "boost" => 1.0),
						),
					),
				),
			),
		)), $query);
	}

	function testNotBuild()
	{
		$builder = new QueryBuilder;

		$query = $builder->build(new NotX(
			new Token('Hello', 'plaintext', 'contents', 1.5)
		));

		$this->assertEquals(array("query" => array(
			"bool" => array(
				"must_not" => array(
					array(
						"term" => array(
							"contents" => array("value" => "hello", "boost" => 1.5),
						),
					),
				),
			),
		)), $query);
	}

	function testFilterWithIdentifier()
	{
		$builder = new QueryBuilder;

		$query = $builder->build(new Token('Some entry', 'identifier', 'username', 1.5));

		$this->assertEquals(array("query" => array(
			"match" => array(
				"username" => array(
					"query" => "Some entry",
				),
			),
		)), $query);
	}

	function testRangeFilter()
	{
		$builder = new QueryBuilder;

		$query = $builder->build(new Range('Hello', 'World', 'plaintext', 'title', 1.5));

		$this->assertEquals(array("query" => array(
			"range" => array(
				"title" => array(
					"from" => "hello",
					"to" => "world",
					"boost" => 1.5,
					"include_upper" => false,
				),
			),
		)), $query);
	}
}

