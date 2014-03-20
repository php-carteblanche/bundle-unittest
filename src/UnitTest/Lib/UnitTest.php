<?php
/**
 * This file is part of the CarteBlanche PHP framework
 * (c) Pierre Cassat and contributors
 * 
 * Sources <http://github.com/php-carteblanche/bundle-unittest>
 *
 * License Apache-2.0
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UnitTest\Lib;

use \UnitTest\Lib\TestCase;
use \UnitTest\Lib\TestGroup;
use \UnitTest\Lib\UnitTestReporter;

/**
 * A simple unit test class to test assertions
 */
class UnitTest
{

	/**
	 * The UnitTestReport object
	 */
	protected $reporter;

	/**
	 * All test case objects
	 */
	protected $cases_stack=array();

	/**
	 * All test group objects
	 */
	protected $groups_stack=array();

	/**
	 * Archived test case objects
	 */
	protected $old_cases_stack=array();

	/**
	 * Archived test group objects
	 */
	protected $old_groups_stack=array();

	/**
	 * Flag for initialization
	 */
	protected $is_inited=false;

	/**
	 * UnitTest constructor
	 *
	 * @param string $group_info The title of the group of tests
	 * @param string $tested_name The name of the tested function or class
	 * @param bool $html Does the reporter must render HTML infos ?
	 */
	public function __construct( $group_info=null, $tested_name=null, $html=null )
	{
		$this->reporter = new UnitTestReporter;

		if (!is_null($html)) $this->setHtml( $html );

		if (!is_null($group_info) || !is_null($tested_name))
		{
			$this->_init( $group_info, $tested_name );
		}
	}

	/**
	 * Initialization : creation of a group if so ...
	 */
	protected function _init( $group_info=null, $tested_name=null )
	{
		if (
			count($this->groups_stack)==0 ||
			(!is_null($group_info) || !is_null($tested_name))
		){
			if (is_null($group_info) && is_null($tested_name) && count($this->old_groups_stack)>0)
			{
				$last_group = end($this->old_groups_stack);
				$group_info = $last_group->getInfo();
				$tested_name = $last_group->getMethod();
			}
			$this->newTestGroup($group_info, $tested_name);
		}
		$this->is_inited=true;
	}

	/**
	 * Re-initialization
	 * Archives the test cases and groups stacks and clear the object for next tests
	 */
	protected function _reset()
	{
		$this->old_groups_stack = $this->groups_stack;
		$this->groups_stack=array();

		$this->old_cases_stack = $this->cases_stack;
		$this->cases_stack=array();

		$this->is_inited=false;
	}

	/**
	 * @see self::render()
	 */
	public function __toString()
	{
		return $this->render();
	}

/////////////////////////////
// UTILITIES
/////////////////////////////
	
	/**
	 * Get the global reporter object
	 */	
	public function getReporter()
	{
		return $this->reporter;
	}

	/**
	 * Set the HTML object property
	 */	
	public function setHtml( $html )
	{
		$this->reporter->setHtml( $html );
		return $this;
	}

	/**
	 * Creates and references a new test case
	 */
	public function newTestCase( $value=null, $info=null, $method=null, $method_description=null, $return_testCase=false )
	{
		$test = $this->getNewTestCase( $value, $info, $method, $method_description );
		$this->addTestCase( $test );
		return true===$return_testCase ? $test : $this;
	}

	/**
	 * Creates a new test case
	 */
	protected function getNewTestCase( $value=null, $info=null, $method=null, $method_description=null )
	{
		return new TestCase( $value, $info, $method, $method_description );
	}

	/**
	 * References a new test case in the stack
	 */
	protected function addTestCase( \UnitTest\Lib\TestCase $test )
	{
		if (false===$this->is_inited) $this->_init();
		$group = $this->getTestGroup();
		$test->setGroupId( $group->getId() );
		$group->addTestCase( $test->getId() );
		$this->cases_stack[ $test->getId() ] = $test;
		return $this;
	}

	/**
	 * Returns last test case of the stack
	 */
	public function getTestCase()
	{
		return end($this->cases_stack);
	}

	/**
	 * Returns a group of the stack
	 */
	public function findTestCase( $test_id )
	{
		return array_key_exists($test_id, $this->cases_stack) ? 
			$this->cases_stack[ $test_id ] : false;
	}

	/**
	 * Creates and references a new tests group
	 */	
	public function newTestGroup( $info=null, $method=null, $presentation=null, $return_testGroup=false )
	{
		$group = $this->getNewTestGroup( $info, $method, $presentation );
		$this->addTestGroup( $group );
		return true===$return_testGroup ? $group : $this;
	}

	/**
	 * Creates a new test case
	 */
	protected function getNewTestGroup( $info=null, $method=null, $presentation=null )
	{
		return new TestGroup( $info, $method, $presentation );
	}

	/**
	 * References a new test group in the stack
	 */
	protected function addTestGroup( \UnitTest\Lib\TestGroup $group )
	{
		$this->groups_stack[ $group->getId() ] = $group;
		return $this;
	}

	/**
	 * Returns last group of the stack
	 */
	public function getTestGroup()
	{
		return end($this->groups_stack);
	}

	/**
	 * Returns a group of the stack
	 */
	public function findTestGroup( $group_id )
	{
		return array_key_exists($group_id, $this->groups_stack) ? 
			$this->groups_stack[ $group_id ] : false;
	}

	/**
	 * References a success on a test
	 */	
	protected function testPassed( \UnitTest\Lib\TestCase $test )
	{
		$test->setResult( true );
	}

	/**
	 * References a failure on a test
	 */	
	protected function testFailed( \UnitTest\Lib\TestCase $test )
	{
		$test->setResult( false );
	}

/////////////////////////////
// INFORMERS
/////////////////////////////
	
	/**
	 * Construction of the whole tests information string
	 */
	public function render()
	{
		$array=array();
		foreach($this->groups_stack as $_id=>$_group)
		{
			$array[] = $_group->render( $this );
		}
		$this->_reset();
		return $this->reporter->buildUnitTestInfo( $array );
	}

/////////////////////////////
// TESTERS
/////////////////////////////
	
	/**
	 * Test if $what is TRUE
	 *
	 * @param bool $what The value to test, must be a boolean
	 * @param string $info An information string, passed thru 'sprintf' function with the value tested
	 * @return object $this for method chaining
	 */
	public function assertTrue( $what, $info=null )
	{
		$test = $this->newTestCase( (bool) $what, $info, 
			'assertTrue', 'Expect boolean TRUE value got [%s]', true );
		if (true === (bool) $what)
		{
			$this->testPassed( $test );
		}
		else
		{
			$this->testFailed( $test );
		}
		return $this;
	}

	/**
	 * Test if $what is FALSE
	 *
	 * @param bool $what The value to test, must be a boolean
	 * @param string $info An information string, passed thru 'sprintf' function with the value tested
	 * @return object $this for method chaining
	 */
	public function assertFalse( $what, $info=null )
	{
		$test = $this->newTestCase( (bool) $what, $info, 
			'assertFalse', 'Expect boolean FALSE value got [%s]', true );
		if (false === (bool) $what)
		{
			$this->testPassed( $test );
		}
		else
		{
			$this->testFailed( $test );
		}
		return $this;
	}

}

// Endfile