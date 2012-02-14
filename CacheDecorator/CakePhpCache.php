<?php

/**
 * Bridge between the static Cache component of CakePHP and the CacheInterface.
 *
 * @author Michael Contento <michaelcontento@gmail.com>
 * @see    https://github.com/michaelcontento/CacheDecorator
 */
class CakePhpCache implements CacheInterface
{
    /**
     * @var string
     */
    private $config;

    /**
     * @param string $config
     * @return CakePhpCache
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
            throw new CacheException("Cache miss for key '$key'.");
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
