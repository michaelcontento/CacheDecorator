<?php

namespace CacheDecorator\Engine;

use Cake;

/**
 * Bridge between the static Cache component of CakePHP and the CacheInterface.
 *
 * @author Michael Contento <michaelcontento@gmail.com>
 * @see    https://github.com/michaelcontento/CacheDecorator
 */
class CakePhp implements Adapter 
{
    /**
     * @var string
     */
    private $config;

    /**
     * @param string $config
     * @return CakePhp
     */
    public function __construct($config = null) 
    {
        $this->config = $config;
    }

    /** 
     * @param string $key
     * @return mixed
     */
    public function get($key) 
    {
        $result = Cake::read($key, $this->config);
        if ($result === false) {
            throw new Exception("Cache miss for key '$key'.");
        }

        return $result;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value) 
    {
        Cake::write($key, $value, $this->config);
    }
}