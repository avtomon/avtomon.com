<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 15.07.2016
 * Time: 11:25
 */

class ConfigException extends Exception { }

class Config
{
    private static $instance = null;
    private $conf = null;

    public static function create ()
    {
        $i = & self::$instance;
        if (!$i)
        {
            $i = new Config();
            $i->loadConfig();
        }
        return $i;
    }

    private function __construct ()
    {

    }

    private function loadConfig ()
    {
        $c = & $this->conf;
        if (!isset($_SESSION['config']) || !is_array($_SESSION['config']))
        {
            $_SESSION['config'] = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/conf.ini', true, INI_SCANNER_RAW);
        }
        if (is_array($_SESSION['config']))
        {
            $c = $_SESSION['config'];
            foreach ($c as $k => & $v)
            {
                if (is_array($v))
                {
                    foreach ($v as $name => & $value)
                    {
                        define($name, $value);
                    }
                    unset($value);
                }
            }
            unset($v);
        }
        return $this->conf;
    }

    public function getConfig ()
    {
        return $this->conf;
    }

    public function saveConfig ()
    {
        $_SESSION['config'] = $this->conf;
        $tmp = '';
        foreach ($this->conf as $section_name => & $section)
        {
            $tmp .= "[$section_name]\r\n";
            foreach ($section as $k => & $v)
            {
                if (!is_array($v))
                {
                    $tmp .= "$k = $v\r\n";
                }
                else
                {
                    foreach ($v as $k2 => & $v2)
                    {
                        $tmp .= "$k" . "[$k2] = $v2\r\n";
                    }
                    unset($v2);
                }
            }
            unset($v);
            $tmp .= "\r\n";
        }
        unset($section);
        if (file_put_contents('conf.ini', $tmp) === false)
        {
            throw new ConfigException('Не удалось записать файл конфигурации');
        }
        else
        {
            return true;
        }
    }

    private function setConst ($name, $value, $section = null)
    {
        $setter = function (string $name, $value, string $section = null)
        {
            uopz_undefine($name);
            define($name, $value);
            if ($section && isset($this->conf[$section]))
            {
                $this->conf[$section][$name] = $value;
            }
            else
            {
                foreach ($this->conf as $section_name => & $section)
                {
                    if (isset($section[$name]))
                    {
                        $section[$name] = $value;
                    }
                }
                unset($section);
            }
            return true;
        };

        if (is_array($name) && is_array($value))
        {
            if (count($name) == count($value) && ($section ? is_array($section) && count($name) == count($section) : true))
            {
                foreach ($name as $k => & $v)
                {
                    if (!$setter($v, $value[$k], $section ? $section[$k] : null))
                    {
                        throw new ConfigException("Не удалось записать значение для дерективы $v. Возможно, такой директивы не существует");
                    }
                }
                unset($v);
                return true;
            }
            else
            {
                throw new ConfigException('Количества элеметтов массивов не равны');
            }
        }
        else
        {
            if (!$setter($name, $value, $section))
            {
                throw new ConfigException("Не удалось записать значение для дерективы $name. Возможно, такой директивы не существует");
            }
            else
            {
                return true;
            }
        }
    }

    public function editConfig (array & $data)
    {
        $keys = array_keys($data);
        $values = array_values($data);
        $this->setConst($keys, $values);
        return $this->saveConfig();
    }
    
    public function getConfigToEditor ()
    {
        $tmp = [];
        foreach ($this->conf as $section_name => & $section)
        {
            if (!in_array($section_name, ['database', 'memcached', 'system', 'levels']))
            {
                $tmp = array_merge($tmp, $section);
            }
        }
        unset($section, $tmp['RSS_IMAGE_URL']);

        return $tmp;
    }

    public function getULoginConfig ()
    {
        $l = & $this->conf['ulogin'];
        $result = ['ulogin_id' => $l['ULOGIN_ID'],
                   'ulogin_url' => "display={$l['ULOGIN_DISPLAY']};fields=email,nickname;optional=phone,first_name,photo_big,photo,bdate,city,sex,last_name,country;sort=default;providers={$l['ULOGIN_PROVIDERS']};redirect_uri=;callback={$l['ULOGIN_REDIRECT']}"];
        return $result;
    }
}