<?php

class Cake
{
    /**
     * @var array
     */
    public static $stack = array();

    /**
     * @return mixed
     */
    public static $nextResult;

    /**
     * @param string $key
     * @param mixed $config
     * @return mixed 
     */
    public static function read($key, $config)
    {
        self::$stack[] = array("get", $key, $config);
        return self::$nextResult;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param mixed $config
     * @return void
     */
    public static function write($key, $value, $config)
    {
        self::$stack[] = array("set", $key, $value, $config);
    }
}

class CakePhpCacheTest extends PHPUnit_Framework_TestCase
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
            array("array", array()),
            array("object", new stdClass()),
            array("true", true),
            array("null", null)
        );
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGet($key, $result)
    {
        $cache = new CakePhpCache();

        Cake::$nextResult = $result;
        $this->assertEquals($result, $cache->get($key));
    }

    public function testCacheExceptionOnFalseResult()
    {
        Cake::$nextResult = false;
        $cache = new CakePhpCache();

        $this->setExpectedException("CacheException");
        $cache->get("key");
    }

    public function testShouldPassTheKeyOnGet()
    {
        $cache = new CakePhpCache();
        $cache->get("key");

        $this->assertCount(1, Cake::$stack);

        list($method, $key) = Cake::$stack[0];
        $this->assertEquals("get", $method);
        $this->assertEquals("key", $key);
    }

    public function testShouldPassTheKeyAndValueOnSet()
    {
        $cache = new CakePhpCache();
        $cache->set("key", "value");

        $this->assertCount(1, Cake::$stack);

        list($method, $key, $value) = Cake::$stack[0];
        $this->assertEquals("set", $method);
        $this->assertEquals("key", $key);
        $this->assertEquals("value", $value);
    }

    public function testShouldPassTheConfigOnGet()
    {
        $cache = new CakePhpCache("config");
        $cache->get("key");

        $this->assertCount(1, Cake::$stack);

        list(, , $config) = Cake::$stack[0];
        $this->assertEquals("config", $config);
    }

    public function testShouldPassTheConfigOnSet()
    {
        $cache = new CakePhpCache("config");
        $cache->set("key", "value");

        $this->assertCount(1, Cake::$stack);

        list(, , , $config) = Cake::$stack[0];
        $this->assertEquals("config", $config);
    }
}
