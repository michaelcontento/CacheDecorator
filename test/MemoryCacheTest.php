<?php

class MemoryCacheTest extends PHPUnit_Framework_TestCase
{
    public function testImplementsCacheInterface() 
    {
        $this->assertInstanceOf("CacheInterface", new MemoryCache());
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
            array("object", new stdClass()),
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
        $cache = new MemoryCache();
        $cache->set($key, $value);
        $this->assertEquals($value, $cache->get($key));
    }

    public function testSetReturnsNothing() 
    {
        $cache = new MemoryCache();
        $this->assertNull($cache->set("key", "value"));
    }

    public function testExceptionOnCacheMiss()
    {
        $cache = new MemoryCache();
        $this->setExpectedException("CacheException");
        $cache->get("invalid");
    }
}
