<?php

class CacheInterfaceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var CacheInterface
     */
    private $interface;

    public function setUp()
    {
        $this->interface = $this->getMockForAbstractClass("CacheInterface");
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testErrorOnGetWithZeroArguments() 
    {
        $this->interface->get();
    }

    public function testGetRequiresOneArgument()
    {
        $this->interface->get("key");
        $this->assertTrue(true);
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testErrorOnSetWithZeroArguments() 
    {
        $this->interface->set();
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testErrorOnSetWithOnlyOneArgument()
    {
        $this->interface->set("foo");
    }

    public function testSetRequiresTwoArguments()
    {
        $this->interface->set("key", "value");
        $this->assertTrue(true);
    }
}
