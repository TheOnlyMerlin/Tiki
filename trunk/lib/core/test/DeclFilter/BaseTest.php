<?php

class DeclFilter_BaseTest extends PHPUnit_Framework_TestCase
{
	function testSimple()
	{
		$data = array(
			'numeric' => '123abc',
			'alpha' => 'alpha123',
		);

		$filter = new DeclFilter;
		$filter->addStaticKeyFilters( array(
			'numeric' => 'digits',
			'alpha' => 'alpha',
		) );

		$data = $filter->filter( $data );

		$this->assertEquals( $data['numeric'], '123' );
		$this->assertEquals( $data['alpha'], 'alpha' );
	}

	function testStructure()
	{
		$data = array(
			'num_array' => array( 134, '456', 'abc', '123abc' ),
		);

		$filter = new DeclFilter;
		$filter->addStaticKeyFiltersForArrays( array(
			'num_array' => 'digits',
		) );

		$data = $filter->filter( $data );

		$this->assertContains( '134', $data['num_array'] );
		$this->assertContains( '456', $data['num_array'] );
		$this->assertContains( '123', $data['num_array'] );

		$this->assertNotContains('abc', $data );
	}

	function testDefault()
	{
		$filter = new DeclFilter;
		$filter->addStaticKeyFilters( array(
			'hello' => 'digits',
		) );
		$filter->addCatchAllFilter( 'alpha' );
		
		$data = $filter->filter( array( 
			'hello' => '123abc',
			'world' => '123abc',
		) );
		
		$this->assertEquals( $data['world'], 'abc' );
		$this->assertEquals( $data['hello'], '123' );
	}

	function testNoDefault()
	{
		$filter = new DeclFilter;
		$filter->addStaticKeyFilters( array(
			'hello' => 'digits',
		) );
		
		$data = $filter->filter( array( 
			'hello' => '123abc',
			'world' => '123abc',
		) );
		
		$this->assertEquals( $data['world'], '123abc' );
		$this->assertEquals( $data['hello'], '123' );
	}
}

?>
