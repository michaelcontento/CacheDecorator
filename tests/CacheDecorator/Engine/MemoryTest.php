<?php

namespace CacheDecorator\Engine;

/** 
 * @author Michael Contento <michaelcontento@gmail.com>
 * @see    https://github.com/michaelcontento/CacheDecorator
 */
class MemoryTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsAdapter() 
    {
        $this->assertInstanceOf("CacheDecorator\Engine\Adapter", new Memory());
    }

    /**
     * @return array
     */
    public function validDataProvider()
    {
        return array(
            array("string", "foo"),
            array("int", 1),
            array("float", 1.2),
            array("array", array()),
            array("object", new \stdClass()),
            array("true", true),
            array("false", false),
            array("null", null)
        );
    }

    /**
     * @dataProvider validDataProvider 
     */
    public function testSetAndGet($key, $value) 
    {
        $cache = new Memory();
        $cache->set($key, $value);
        $this->assertEquals($value, $cache->get($key));
    }

    public function testSetReturnsNothing() 
    {
        $cache = new Memory();
        $this->assertNull($cache->set("key", "value"));
    }

    public function testExceptionOnCacheMiss()
    {
        $cache = new Memory();
        $this->setExpectedException("CacheDecorator\Engine\Exception");
        $cache->get("invalid");
    }
}
