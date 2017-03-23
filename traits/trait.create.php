<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 28.04.14
 * Time: 17:35
 */

trait create
{
    private static $instance = false;

    public static function create (array $data = array ())
    {
        if (!self::$instance)
        {
            if (isset(self::$db) && !self::$db)
            {
                self::$db = _PDO::create();
            }
            $class_name = __CLASS__;
            self::$instance = new $class_name($data);
        }
        return self::$instance;
    }
}