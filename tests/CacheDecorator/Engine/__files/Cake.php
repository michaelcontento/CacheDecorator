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
