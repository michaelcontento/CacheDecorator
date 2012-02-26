<?php

namespace CacheDecorator\Engine;

/**
 * Interface all cached must fulfill to work with CacheDecorator.
 *
 * @author Michael Contento <michaelcontento@gmail.com>
 * @see    https://github.com/michaelcontento/CacheDecorator
 */
interface Adapter
{
    /**
     * @throws Exception on cache miss 
     * @param string $key
     * @return mixed
     */
    public function get($key);

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value);
}
