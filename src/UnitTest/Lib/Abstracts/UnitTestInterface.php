<?php
/**
 * CarteBlanche - PHP framework package - Unit Test bundle
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/carte-blanche>
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