<?php
/**
 * CarteBlanche - PHP framework package - Unit Test bundle
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License Apache-2.0 <http://www.apache.org/licenses/LICENSE-2.0.html>
 * Sources <http://github.com/php-carteblanche/carteblanche>
 */

namespace UnitTest\Lib;
use \UnitTest\Lib\Abstracts\UnitTestInterface;

/**
 * The one test object
 */
class TestCase implements UnitTestInterface
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
	 * Test method description
	 */
	protected $method_description;

	/**
	 * Test info
	 */
	protected $info;

	/**
	 * Test value
	 */
	protected $value;

	/**
	 * Test result
	 */
	protected $result;

	/**
	 * Test group ID
	 */
	protected $group_id;

	/**
	 * The test call filename
	 */
	protected $filename;

	/**
	 * The test call line
	 */
	protected $line;

	/**
	 *
	 */
	public function __construct( $value=null, $info=null, $method=null, $method_description=null )
	{
		$this
			->setId( uniqid() )
			->setTime( microtime() )
			->setValue( $value )
			->setInfo( $info )
			->setMethod( $method )
			->setFilename( \Dev\PhpListener::getTraceInfo(4,'file') )
			->setline( \Dev\PhpListener::getTraceInfo(4,'line') )
			->setMethodDescription( $method_description );
	}

	public function render( \UnitTest\Lib\UnitTest $unit_test )
	{
		return $unit_test->getReporter()->buildTestCaseInfo( $this );
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

	public function setMethodDescription( $method_description )
	{
		$this->method_description = (string) $method_description;
		return $this;
	}

	public function getMethodDescription()
	{
		return (string) $this->method_description;
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

	public function setValue( $value )
	{
		$this->value = $value;
		return $this;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function setResult( $result )
	{
		if (is_bool($result))
		{
			$result = true===$result ? 'passed' : 'failed';
		}
		$this->result = (string) $result;
		return $this;
	}

	public function getResult()
	{
		return $this->result;
	}

	public function setGroupId( $id )
	{
		$this->group_id = (string) $id;
		return $this;
	}

	public function getGroupId()
	{
		return (string) $this->group_id;
	}

	/**
	 */	
	public function setFilename( $filename )
	{
		$this->filename = (string) $filename;
		return $this;
	}

	/**
	 */	
	public function getFilename()
	{
		return $this->filename;
	}

	/**
	 */	
	public function setLine( $line )
	{
		$this->line = (string) $line;
		return $this;
	}

	/**
	 */	
	public function getLine()
	{
		return $this->line;
	}

}

// Endfile