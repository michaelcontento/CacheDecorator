<?php

namespace CacheDecorator;

use Engine\Adapter;

/**
 * Very simple caching decorator for all PHP objects.
 *
 * @author Michael Contento <michaelcontento@gmail.com>
 * @see    https://github.com/michaelcontento/CacheDecorator
 */
class Decorator 
{
    /**
     * @var string
     */
    private $prefix = '';

    /**
     * @var Adapter 
     */
    private $cache;

    /**
     * @var mixed
     */
    private $object;

    /**
     * @var string
     */
    private $objectClassName;

    /**
     * @param mixed $object
     * @param Adapter $cache
     * @param string $prefix
     * @return Decorator
     */
    public function __construct($object, $cache, $prefix = "") 
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException(
                "Object argument must be a valid object instance"
            );
        }
    
        if (!is_string($prefix)) {
            throw new \InvalidArgumentException(
                "Prefix argument must be a string."
            );
        }

        $this->object = $object;
        $this->objectClassName = get_class($object);
        $this->cache = $cache;
        $this->prefix = $prefix;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return string
     */
    private function getCacheKey($name, array $arguments) 
    {
        $elements = array(
            $this->objectClassName,
            $name,
            md5(serialize($arguments))
        );
    
        if (!empty($this->prefix)) {
            array_unshift($elements, $this->prefix);
        }

        return implode("_", $elements);
    }

    /**
     * @param string $key
     * @param \Closure $callback
     * @return mixed
     */
    private function readThroughCache($key, \Closure $callback) 
    {
        try {
            $result = $this->cache->get($key);
        } catch (Engine\Exception $e) {
            $result = $callback(); 

            try {
                $this->cache->set($key, $result);
            } catch (\Exception $e) {
            }
        }

        return $result;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments) 
    {
        $object = $this->object;
        return $this->readThroughCache(
            $this->getCacheKey($name, $arguments), 
            function() use ($object, $name, $arguments) {
                return call_user_func_array(
                    array($object, $name),
                    $arguments
                );
            }
        );
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name) 
    {
        $object = $this->object;
        return $this->readThroughCache(
            $this->getCacheKey("__get", array($name)), 
            function() use ($object, $name) {
                return $object->$name; 
            }
        );
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value) 
    {
        $object = $this->object;
        return $this->readThroughCache(
            $this->getCacheKey("__set", array($name)), 
            function() use ($object, $name, $value) {
                $object->$name = $value; 
            }
        );
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function __isset($name) 
    {
        $object = $this->object;
        return $this->readThroughCache(
            $this->getCacheKey("__isset", array($name)), 
            function() use ($object, $name) {
                return isset($object->$name);
            }
        );
    }

    /**
     * @param string $name
     * @return void
     */
    public function __unset($name) 
    {
        $object = $this->object;
        return $this->readThroughCache(
            $this->getCacheKey("__unset", array($name)), 
            function() use ($object, $name) {
                unset($object->$name);
            }
        );
    }
}
