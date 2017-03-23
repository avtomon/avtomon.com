<?php
/**
 * Created by PhpStorm.
 * User: Bond
 * Date: 03.04.14
 * Time: 9:32
 */

class SuperclassException extends Exception { }

abstract class Superclass
{
    public function __construct ()
    {
        static::setNativeProperty('db', MShell::create());
    }

    public function __call ($name, array $data = [])
    {
        $property_value = static::getNativeProperty($name);
        if ($data)
        {
            if (count($data) > 1)
            {
                $expire = $data[1];
                $data = $data[0];
            }
            elseif (is_array($data[0]))
            {
                $data = $data[0];
                $expire = Registry::getMethodExpire(static::getClassName() . '::' . $name);
            }
            else
            {
                $expire = $data[0];
                $data = [];
            }

            if (count($data))
            {
                if (isset($date[1]))
                {
                    if (is_array($property_value) && count($data) == count($property_value))
                    {
                        $n = count($data);
                        for ($i = 0; $i < $n; $i++)
                        {
                            $property_value[$i] = Service::sql($property_value[$i], $data[$i]);
                        }
                    }
                    else
                    {
                        throw new SuperclassException('Количество запросов не соответствует количеству массивов параметров');
                    }
                }
                else
                {
                    $property_value = Service::sql($property_value, $data);
                }
            }
        }
        //print_r($property_value);
        //print_r($data);
        $result = static::getNativeProperty('db')->getValue($property_value, $data, $expire);
        //print_r($result);
        return $result ? $result : false;
    }

}