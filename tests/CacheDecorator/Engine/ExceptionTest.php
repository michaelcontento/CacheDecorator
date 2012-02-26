<?php

namespace CacheDecorator\Engine;

/** 
 * @author Michael Contento <michaelcontento@gmail.com>
 * @see    https://github.com/michaelcontento/CacheDecorator
 */
class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionExtendsPhpBaseException()
    {
        $this->assertInstanceOf("\Exception", new Exception());
    }
}
