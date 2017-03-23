<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 02.06.14
 * Time: 12:14
 */

trait Singleton
{
    private static $instance = false;

    public function create ()
    {
        if (!self::$instance)
        {
            $class = __CLASS__;
            self::$instance = new $class();
        }
        return self::$instance;
    }

    private function __construct ()
    {
        if (!self::$db)
            try
            {
                self::$db = MShell::create();
            }
            catch (Exception $e)
            {
                throw $e;
            }
    }

} 