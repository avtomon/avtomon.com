<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 29.04.14
 * Time: 16:28
 */

trait Classinfo
{
    public function getNativeProperty ($name)
    {
        if (isset($this->$name))
        {
            return $this->$name;
        }
        elseif (isset(self::$$name))
        {
            return self::$$name;
        }
        else
            throw new SuperclassException("Свойства $name нет в классе");
    }

    public function setNativeProperty ($name, $value)
    {
        if (isset($this->$name))
            $this->$name = $value;
        elseif (isset(self::$$name))
            self::$$name = $value;
    }

    public function getClassName ()
    {
        return __CLASS__;
    }
}

?>