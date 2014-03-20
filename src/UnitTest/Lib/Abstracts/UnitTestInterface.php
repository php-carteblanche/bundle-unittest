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