<?php
/**
 * CarteBlanche - PHP framework package - Unit Test bundle
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/carte-blanche>
 */

namespace UnitTest\Lib;
use \UnitTest\Lib\Abstracts\UnitTestInterface;

/**
 * The group of tests object
 */
class TestGroup implements UnitTestInterface
{

	/**
	 * Test ID
	 */
	protected $id;

	/**
	 * Test time
	 */
	protected $time;

	/**
	 * Test method
	 */
	protected $method;

	/**
	 * Test info
	 */
	protected $info;

	/**
	 * Test presentation
	 */
	protected $presentation;

	/**
	 * Test test cases of the group
	 */
	protected $test_cases;

	/**
	 * Global tests counter
	 */
	protected $tests_count=0;

	/**
	 * Passed tests counter
	 */
	protected $tests_passed_count=0;

	/**
	 * Failed tests counter
	 */
	protected $tests_failed_count=0;

	public function __construct( $info=null, $method=null, $presentation=null )
	{
		$this
			->setId( uniqid() )
			->setTime( microtime() )
			->setInfo( $info )
			->setMethod( $method )
			->setPresentation( $presentation );
	}

	public function render( \UnitTest\Lib\UnitTest $unit_test )
	{
		$array=array();
		$array['info'] = $unit_test->getReporter()->buildTestGroupInfo( $this );
		
		if (strlen((string) $this->presentation)>0)
		{
			$array['presentation'] = $unit_test->getReporter()->buildTestGroupPresentation( $this );
		}
		
		$array['tests'] = array();
		foreach($this->getTestCases() as $test_id)
		{
			$test_case = $unit_test->findTestCase( $test_id );
			if ('passed'===$test_case->getResult())
			{
				$this->tests_count++;
				$this->tests_passed_count++;
			}
			elseif ('failed'===$test_case->getResult())
			{
				$this->tests_count++;
				$this->tests_failed_count++;
			}
			else
			{
				$this->tests_count++;
			}
			$array['tests'][] = $test_case->render( $unit_test );
		}
		$array['conclusion'] = $unit_test->getReporter()->buildTestGroupConclusion( $this );
		return $array;
	}

	protected function setId( $id )
	{
		$this->id = (string) $id;
		return $this;
	}

	public function getId()
	{
		return (string) $this->id;
	}

	public function setTime( $time )
	{
		$this->time = (string) $time;
		return $this;
	}

	public function getTime()
	{
		return (string) $this->time;
	}

	public function setMethod( $method )
	{
		$this->method = (string) $method;
		return $this;
	}

	public function getMethod()
	{
		return (string) $this->method;
	}

	public function setInfo( $info )
	{
		$this->info = (string) $info;
		return $this;
	}

	public function getInfo()
	{
		return (string) $this->info;
	}

	public function setPresentation( $presentation )
	{
		$this->presentation = (string) $presentation;
		return $this;
	}

	public function getPresentation()
	{
		return (string) $this->presentation;
	}

	public function addTestCase( $id )
	{
		$this->test_cases[] = $id;
		return $this;
	}

	public function getTestCases()
	{
		return $this->test_cases;
	}

	public function getCounter( $which=null )
	{
		switch ($which)
		{
			case 'passed': return $this->tests_passed_count; break;
			case 'failed': return $this->tests_failed_count; break;
			default: return $this->tests_count; break;
		}
	}

}

// Endfile