<?php

class MemoryCacheTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function validDataProvider()
    {
        return array(
            array("string", "foo"),
            array("int", 1),
            array("float", 1.2),
            array("array", array(1)),
            array("object", new stdClass())
        );
    }

    public function testImplementsCacheInterface() 
    {
        $this->assertInstanceOf("CacheInterface", new MemoryCache());
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
