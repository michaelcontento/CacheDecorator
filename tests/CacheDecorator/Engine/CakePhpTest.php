<?php

namespace CacheDecorator\Engine;

// For this tests we now load our own simple implementation of the static
// Cake class. It's a mocked version that allows us to check the call stack and
// define the result for all following calls to read().
require __DIR__ . "/__files/Cake.php";
use Cake;

/** 
 * @author Michael Contento <michaelcontento@gmail.com>
 * @see    https://github.com/michaelcontento/CacheDecorator
 */
class CakePhpTest extends \PHPUnit_Framework_TestCase
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
            array("null", null)
        );
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGet($key, $result)
    {
        $cache = new CakePhp();

        Cake::$nextResult = $result;
        $this->assertEquals($result, $cache->get($key));
    }

    public function testCacheExceptionOnFalseResult()
    {
        Cake::$nextResult = false;
        $cache = new CakePhp();

        $this->setExpectedException("CacheDecorator\Engine\Exception");
        $cache->get("key");
    }

    public function testShouldPassTheKeyOnGet()
    {
        $cache = new CakePhp();
        $cache->get("key");

        $this->assertCount(1, Cake::$stack);

        list($method, $key) = Cake::$stack[0];
        $this->assertEquals("get", $method);
        $this->assertEquals("key", $key);
    }

    public function testShouldPassTheKeyAndValueOnSet()
    {
        $cache = new CakePhp();
        $cache->set("key", "value");

        $this->assertCount(1, Cake::$stack);

        list($method, $key, $value) = Cake::$stack[0];
        $this->assertEquals("set", $method);
        $this->assertEquals("key", $key);
        $this->assertEquals("value", $value);
    }

    public function testShouldPassTheConfigOnGet()
    {
        $cache = new CakePhp("config");
        $cache->get("key");

        $this->assertCount(1, Cake::$stack);

        list(, , $config) = Cake::$stack[0];
        $this->assertEquals("config", $config);
    }

    public function testShouldPassTheConfigOnSet()
    {
        $cache = new CakePhp("config");
        $cache->set("key", "value");

        $this->assertCount(1, Cake::$stack);

        list(, , , $config) = Cake::$stack[0];
        $this->assertEquals("config", $config);
    }
}
