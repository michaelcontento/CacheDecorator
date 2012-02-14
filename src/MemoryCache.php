<?php

/**
 * Very simple in-memory cache.
 *
 * @author Michael Contento <michaelcontento@gmail.com>
 * @see    https://github.com/michaelcontento/CacheDecorator
 */
class MemoryCache implements CacheInterface
{
    /**
     * @var array
     */
    private $store = array();

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key) 
    {
        if (!array_key_exists($key, $this->store)) {
            throw new CacheException("Cache miss for key '$key'.");
        }

        return $this->store[$key];
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value)
    {
        $this->store[$key] = $value;
    }
}
