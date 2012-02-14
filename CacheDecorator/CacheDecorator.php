<?php

/**
 * Very simple caching decorator for all PHP objects.
 *
 * @author Michael Contento <michaelcontento@gmail.com>
 * @see    https://github.com/michaelcontento/CacheDecorator
 */
class CacheDecorator 
{
    /**
     * @var string
     */
    private $prefix = '';

    /**
     * @var CacheInterface 
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
     * @param CacheInterface $cache
     * @param string $prefix
     * @return CacheDecorator
     */
    public function __construct($object, CacheInterface $cache, $prefix = "") 
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException(
                "Object argument must be a valid object instance"
            );
        }
    
        if (!is_string($prefix)) {
            throw new InvalidArgumentException(
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
     * @param Closure $callback
     * @return mixed
     */
    private function readThroughCache($key, Closure $callback) 
    {
        try {
            $result = $this->cache->get($key);
        } catch (CacheException $e) {
            $result = $callback(); 

            try {
                $this->cache->set($key, $result);
            } catch (Exception $e) {
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
        $this->object->$name = $value;
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function __isset($name) 
    {
        return isset($this->object->$name);
    }

    /**
     * @param string $name
     * @return void
     */
    public function __unset($name) 
    {
        unset($this->object->$name);
    }
}
