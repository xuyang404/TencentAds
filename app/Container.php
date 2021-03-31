<?php


namespace LanHai\TencentAds;


class Container
{
    public  static $binds;
    public  static $instances;

    public static function bind($abstract, $concrete) {
        if ($concrete instanceof \Closure) {
            self::$binds[$abstract] = $concrete;
        }else {
            self::$instances[$abstract] = $concrete;
        }
    }

    public static function make($abstract, $parameters = []) {
        if (isset(self::$instances[$abstract])) {
            return self::$instances[$abstract];
        }

        return call_user_func_array(self::$binds[$abstract], $parameters);
    }

    /**
     * @return mixed
     */
    public static function getBinds()
    {
        return self::$binds;
    }

    /**
     * @return mixed
     */
    public static function getInstances()
    {
        return self::$instances;
    }
}