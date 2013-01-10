<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Search_ActionStepTest extends PHPUnit_Framework_TestCase
{
	function testMissingField()
	{
		$action = $this->getMock('Search_Action_Action');
		$action->expects($this->any())
			->method('getValues')
			->will($this->returnValue(array(
				'hello' => true,
			)));

		$step = new Search_Action_ActionStep($action, array());
		$this->assertFalse($step->validate(array()));
	}

	function testMissingValueButNotRequired()
	{
		$action = $this->getMock('Search_Action_Action');
		$action->expects($this->any())
			->method('getValues')
			->will($this->returnValue(array(
				'hello' => false,
			)));
		$action->expects($this->once())
			->method('validate')
			->with($this->equalTo(new JitFilter(array('hello' => null))))
			->will($this->returnValue(true));

		$step = new Search_Action_ActionStep($action, array());
		$this->assertTrue($step->validate(array()));
	}

	function testValueProvidedStaticInDefinition()
	{
		$action = $this->getMock('Search_Action_Action');
		$action->expects($this->any())
			->method('getValues')
			->will($this->returnValue(array(
				'hello' => true,
			)));
		$action->expects($this->once())
			->method('validate')
			->with($this->equalTo(new JitFilter(array('hello' => 'world'))))
			->will($this->returnValue(true));

		$step = new Search_Action_ActionStep($action, array('hello' => 'world'));
		$this->assertTrue($step->validate(array()));
	}

	function testValueProvidedInEntryDirectly()
	{
		$action = $this->getMock('Search_Action_Action');
		$action->expects($this->any())
			->method('getValues')
			->will($this->returnValue(array(
				'hello' => true,
			)));
		$action->expects($this->once())
			->method('validate')
			->with($this->equalTo(new JitFilter(array('hello' => 'world'))))
			->will($this->returnValue(true));

		$step = new Search_Action_ActionStep($action, array());
		$this->assertTrue($step->validate(array('hello' => 'world')));
	}

	function testDefinitionDefersToSingleField()
	{
		$action = $this->getMock('Search_Action_Action');
		$action->expects($this->any())
			->method('getValues')
			->will($this->returnValue(array(
				'hello' => true,
			)));
		$action->expects($this->once())
			->method('validate')
			->with($this->equalTo(new JitFilter(array('hello' => 'world'))))
			->will($this->returnValue(true));

		$step = new Search_Action_ActionStep($action, array('hello_field' => 'test'));
		$this->assertTrue($step->validate(array('test' => 'world')));
	}

	function testDefinitionCoalesceField()
	{
		$action = $this->getMock('Search_Action_Action');
		$action->expects($this->any())
			->method('getValues')
			->will($this->returnValue(array(
				'hello' => true,
			)));
		$action->expects($this->once())
			->method('validate')
			->with($this->equalTo(new JitFilter(array('hello' => 'right'))))
			->will($this->returnValue(true));

		$step = new Search_Action_ActionStep($action, array('hello_field_coalesce' => 'foo,bar,test,baz,hello'));
		$this->assertTrue($step->validate(array('test' => 'right', 'baz' => 'wrong')));
	}

	function testDefinitionCoalesceFieldNoMatch()
	{
		$action = $this->getMock('Search_Action_Action');
		$action->expects($this->any())
			->method('getValues')
			->will($this->returnValue(array(
				'hello' => true,
			)));

		$step = new Search_Action_ActionStep($action, array('hello_field_coalesce' => 'foo,bar,test,baz,hello'));
		$this->assertFalse($step->validate(array()));
	}

	function testRequiresValueAsArrayButMissing()
	{
		$action = $this->getMock('Search_Action_Action');
		$action->expects($this->any())
			->method('getValues')
			->will($this->returnValue(array(
				'hello+' => false,
			)));
		$action->expects($this->once())
			->method('validate')
			->with($this->equalTo(new JitFilter(array('hello' => array()))))
			->will($this->returnValue(true));

		$step = new Search_Action_ActionStep($action, array());
		$this->assertTrue($step->validate(array()));
	}

	function testRequiresValueAsArrayAndSingleValue()
	{
		$action = $this->getMock('Search_Action_Action');
		$action->expects($this->any())
			->method('getValues')
			->will($this->returnValue(array(
				'hello+' => false,
			)));
		$action->expects($this->once())
			->method('validate')
			->with($this->equalTo(new JitFilter(array('hello' => array('world')))))
			->will($this->returnValue(true));

		$step = new Search_Action_ActionStep($action, array('hello' => 'world'));
		$this->assertTrue($step->validate(array()));
	}

	function testRequiresValueAsArrayAndMultipleValues()
	{
		$action = $this->getMock('Search_Action_Action');
		$action->expects($this->any())
			->method('getValues')
			->will($this->returnValue(array(
				'hello+' => false,
			)));
		$action->expects($this->once())
			->method('validate')
			->with($this->equalTo(new JitFilter(array('hello' => array('a', 'b')))))
			->will($this->returnValue(true));

		$step = new Search_Action_ActionStep($action, array('hello_field_multiple' => 'foo,bar,baz'));
		$this->assertTrue($step->validate(array('foo' => 'a', 'baz' => 'b')));
	}
}

