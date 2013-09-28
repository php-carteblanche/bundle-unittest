<?php
/**
 * CarteBlanche - PHP framework package - Unit Test bundle
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/carte-blanche>
 */

namespace UnitTest\Lib;

/**
 * Assertion object
 */
class Assertion
{

	/**
	 * The UnitTestReport object
	 */
	protected $reporter;

	/**
	 * The assert call filename
	 */
	protected $filename;

	/**
	 * The assert call line
	 */
	protected $line;

	/**
	 * The assert code
	 */
	protected $code;

	/**
	 * The assert info
	 */
	protected $info;

	/**
	 * The assert result (FALSE using 'assert()' PHP standard function - if TRUE, nothing is written)
	 */
	protected $assert_succeed=true;

	/**
	 * Assertion constructor
	 *
	 * @param string $group_info The title of the group of tests
	 * @param string $tested_name The name of the tested function or class
	 * @param bool $html Does the reporter must render HTML infos ?
	 */
	public function __construct( $filename, $line, $error, $description=null, $html=null )
	{
		$this->reporter = new UnitTestReporter;
		if (!is_null($html)) $this->setHtml( $html );
		$this->filename = $filename;
		$this->line = $line;
		$this->code = $error;
		$this->info = $description;
	}

	/**
	 * @see self::render()
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 */
	public function render()
	{
		return $this->reporter->buildAssertInfo( $this );
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
	 * Set the HTML object property
	 */	
	public function setFailure()
	{
		$this->assert_succeed=false;
		return $this;
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

	/**
	 */	
	public function setCode( $code )
	{
		$this->code = (string) $code;
		return $this;
	}

	/**
	 */	
	public function getCode()
	{
		return $this->code;
	}

	/**
	 */	
	public function setInfo( $info )
	{
		$this->info = (string) $info;
		return $this;
	}

	/**
	 */	
	public function getInfo()
	{
		return $this->info;
	}

	/**
	 */	
	public function getAssertSucceed()
	{
		return $this->assert_succeed;
	}

}

// Endfile