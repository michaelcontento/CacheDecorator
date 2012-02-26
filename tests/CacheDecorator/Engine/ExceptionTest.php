<?php

namespace CacheDecorator\Engine;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionExtendsPhpBaseException()
    {
        $this->assertInstanceOf("\Exception", new Exception());
    }
}
