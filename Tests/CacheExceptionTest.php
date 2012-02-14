<?php

class CacheExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testExceptionExtendsPhpBaseException()
    {
        $this->assertInstanceOf("Exception", new CacheException());
    }
}
