<?php
/**
 * CarteBlanche - PHP framework package - Unit Test bundle
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License Apache-2.0 <http://www.apache.org/licenses/LICENSE-2.0.html>
 * Sources <http://github.com/php-carteblanche/carteblanche>
 */

namespace UnitTest\Lib\Abstracts;
use \UnitTest\Lib\UnitTest;

/**
 * The test suite interface
 */
interface UnitTestInterface
{

	public function render( \UnitTest\Lib\UnitTest $unit_test );

}

// Endfile