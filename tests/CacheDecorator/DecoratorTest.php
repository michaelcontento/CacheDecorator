<?php

namespace CacheDecorator;

use CacheDecorator\Decorator,
    CacheDecorator\Engine\Adapter;

class DecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \stdClass
     */
    private $object;

    /**
     * @var Adapter
     */
    private $cache;
    
    /**
     * @var Decorator
     */
    private $decorator;

    /**
     * @return Adapter
     */
    private function getCacheMock()
    {
        return $this->getMock("CacheDecorator\Engine\Adapter");
    }

    /**
     * @return DecoratorTest
     */
    private function prepareCacheMiss()
    {
        $this->cache
            ->expects($this->once())
            ->method("get")
            ->will($this->throwException(new Engine\Exception()));

        return $this;
    }

    /**
     * @param mixed $result
     * @return DecoratorTest
     */
    private function prepareCacheHit($result)
    {
        $this->cache
            ->expects($this->once())
            ->method("get")
            ->will($this->returnValue($result));

        return $this;
    }

    public function setUp()
    {
        $this->object = new \stdClass();
        $this->cache = $this->getCacheMock();
        $this->decorator = new Decorator($this->object, $this->cache);
    }

    /**
     * @return array
     */
    public function invalidObjectArguments()
    {
        return array(
            array("string"),
            array(1),
            array(1.2),
            array(array()),
            array(true),
            array(false),
            array(null)
        );
    }

    /**
     * @dataProvider invalidObjectArguments 
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionOnInvalidObjectArgument($object) 
    {
        new Decorator($object, $this->getCacheMock());
    }

    /**
     * @return array
     */
    public function invalidPrefixArguments()
    {
        return array(
            array(1),
            array(1.2),
            array(array()),
            array(new \stdClass()),
            array(true),
            array(false),
            array(null)
        );
    }

    /**
     * @dataProvider invalidPrefixArguments
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionOnInvalidPrefixArgument($prefix)
    {
        new Decorator(new \stdClass(), $this->getCacheMock(), $prefix);
    }

    public function testShouldPassMagicSet()
    {
        $this->decorator->__set("key", "foo");
        $this->assertEquals("foo", $this->object->key);
    }

    public function testMagicSetShouldReturnNull()
    {
        $this->assertNull($this->decorator->__set("key", "foo"));
    }

    public function testShouldPassMagicIsset()
    {
        $this->object->key = true; 
        $this->assertTrue($this->decorator->__isset("key"));
    }

    public function testShouldPassMagicUnset()
    {
        $this->object->key = true; 
        $this->decorator->__unset("key");
        $this->assertFalse(isset($this->object->key));
    }

    public function testMagicUnsetShouldReturnNull()
    {
        $this->assertNull($this->decorator->__unset("key"));
    }

    public function testShouldPassMagicGetOnCacheMiss()
    {
        $this->prepareCacheMiss();
        $this->object->key = "foo";
        $this->assertEquals("foo", $this->decorator->__get("key"));
    }

    public function testShouldResponseWithCacheOnMagicGet()
    {
        $this->prepareCacheHit("bar");
        $this->object->key = "foo";
        $this->assertEquals("bar", $this->decorator->__get("key"));
    }

    public function testShouldStoreValueInCacheAfterMissOnMagicGet()
    {
        $this->prepareCacheMiss();

        $this->cache
            ->expects($this->once())
            ->method("set")
            ->with($this->anything(), "foo");

        $this->object->key = "foo";
        $this->assertEquals("foo", $this->decorator->__get("key"));
    }

    public function testShouldSilentlyCatchExceptionFromCacheSet()
    {
        $this->prepareCacheMiss();

        $this->cache
            ->expects($this->once())
            ->method("set")
            ->will($this->throwException(new \Exception()));

        $this->object->key = "foo";
        $this->assertEquals("foo", $this->decorator->__get("key"));
    }
}
