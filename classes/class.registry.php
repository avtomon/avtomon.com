<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 11.04.14
 * Time: 14:49
 */

class RegistryException extends Exception { }

class Registry
{
    private static $paths = false;

    private static $fields = false;

    private static $properties = false;

    private static $pages = false;

    private static $methods = false;

    private static $exts = false;

    private static $mimes = false;

    private static $tables = false;

    private static $redirects = false;

    /**
     * Корректный доступ к свойству
     *
     * @param $name
     * @return mixed
     * @throws RegistryException
     */
    private static function getGlobal(string $name)
    {
        if (!is_array(self::$$name) || !self::$$name)
        {
            if (!isset($_SESSION[$name]))
            {
                require_once($_SERVER['DOCUMENT_ROOT'].'/arrays/'.$name.'.php');
                if (isset($$name))
                {
                    self::$$name = $$name;
                    $_SESSION[$name] = $$name;
                }
                else
                    throw new RegistryException("Валидирующий массив $name не найден");
            }
            else
            {
                self::$$name = $_SESSION[$name];
            }
        }
        return self::$$name;
    }

    /**
     * Проверка расширения файла на возможность загрузки
     *
     * @param $ext_name
     * @return bool
     */
    public static function validateFileExt (string & $ext_name)
    {
        $exts = self::getGlobal('exts');
        if (isset($exts[$ext_name]) && $exts[$ext_name])
            return true;
        else
            return false;
    }

    /**
     * Проверка mime-типа файла
     *
     * @param $filename
     * @return bool
     */
    public static function validateFileMimeType (string & $filename)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $filename);
        $mimes = self::getGlobal('mimes');
        if (isset($mimes[$mime_type]) && $mimes[$mime_type])
            return $mime_type;
        else
            return false;
    }

    /**
     * Возвращает путь к директории с заданным типом файлов
     *
     * @param $file_type
     * @return mixed
     * @throws RegistryException
     */
    public static function getFilePath (string & $file_type)
    {
        $paths = self::getGlobal('paths');
        if (isset($paths['files'][$file_type]) && $paths['files'][$file_type])
            return $paths['files'][$file_type];
        else
            throw new RegistryException ("Для поля $file_type не задан путь сохранения");
    }

    /**
     * Возвращает путь к указанному лог-файлу
     *
     * @param $type - тип логируемых данных
     * @return mixed
     * @throws RegistryException
     */
    public static function getLogPath (string $type)
    {
        $paths = self::getGlobal('paths');
        if (isset($paths['logs'][$type]) && $paths['logs'][$type])
            return $paths['logs'][$type];
        else
        {
            throw new RegistryException ("Для типа события $type не задан лог");
        }
    }

    /**
     * Проверить массив входных данных метода
     *
     * @param $params - входные данные
     * @param $method - идентификатор вида Класс::метод, уникально идентифицирующий набор валидирующих данных
     * @return mixed
     * @throws RegistryException
     */
    public static function getAllValidateFields (array $params, $method)
    {
        $check = self::getGlobal('fields');
        $check = isset($check[$method]) ? $check[$method] : [];
        $validate = function (array & $params) use (& $method, & $check)
        {
            if (isset($check) && $check)
            {
                if ($arr = array_diff_key($check, $params))
                {
                    foreach ($arr as $key => & $value)
                    {
                        if ($value['is_required'])
                            throw new RegistryException ("Неверный набор данных, не хватает поля $key для метода $method");
                    }
                    unset($value);
                }
                foreach ($params as $key => & $value)
                {
                    if (isset($check[$key]) && is_array($check[$key]))
                    {
                        $k = & $check[$key];
                        if (isset($k['is_sanitize']) && $k['is_sanitize'])
                        {
                            self::antiXSS($value, COMMENT_ALLOWED_TAGS);
                        }
                        else
                        {
                            self::antiXSS($value, ADMIN_ALLOWED_TAGS);
                        }
                        if ($value !== '' && $value !== NULL)
                        {
                            self::validateValue($key, $value, $k);
                        }
                        else
                        {
                            if ($k['is_required'])
                            {
                                throw new RegistryException ("Обязательное поле $key пустое для метода $method");
                            }
                            else
                            {
                                $value = NULL;
                            }
                        }
                        unset($k);
                    }
                    else
                    {
                        unset($params[$key]);
                    }
                }
                unset($value);
            }
            return $params;
        };

        if (array_keys($params) === range(0, count($params) - 1))
        {
            foreach ($params as $key => & $seq)
            {
                $validate($seq);
            }
            unset($seq);
        }
        else
        {
            $validate($params);
        }
        return $params;
    }

    /**
     * Проверить заданное значение параметра метода
     *
     * @param $key   - имя параметра
     * @param $value - значение параметра
     * @param $check - набор валидирующих параметров
     * @return bool
     * @throws RegistryException
     */
    private static function validateValue (string & $key, $value, array & $check)
    {
        $checkType = function ($checktype) use (& $value, & $check)
        {
            $starttype = gettype($value);
            if ($starttype != $checktype)
            {
                $tmp = $value;
                @settype($value, $checktype);
                @settype($value, $starttype);
                if ($tmp != $value)
                    return 0;
            }
            $t = & $check['type'];
            if ($t == 'string' && isset($check['regexp']) && !$regexp_result = preg_match($check['regexp'], $value))
            {
                throw new RegistryException("Значение $value не соответствует регулярному выражению {$check['regexp']}");
            }
            elseif (($t == 'integer' || $t == 'float') && (isset($check['from']) || isset($check['to'])))
            {
                if (isset($check['from']) && $value < $check['from'])
                    throw new RegistryException("Значение $value меньше нижнего лимита значения");
                if (isset($check['to']) && $value > $check['to'])
                    throw new RegistryException("Значение $value больше верхнего лимита значения");
            }
            return 1;
        };
        if (isset($check['type']))
        {
            $check['type'] = explode('|', $check['type']);
            $i = 0;
            foreach ($check['type'] as & $type)
            {
                if ($i = $checkType($type))
                {
                    break;
                }
            }
            unset($type);
            if ($i === 0)
            {
                $type = gettype($value);
                throw new RegistryException("Тип данных поля $key($type) не соответствует требуемому типу");
            }
        }
    }

    /**
     * Достать информацию о запрашиваемой странице
     *
     * @param $page_name
     * @return mixed
     * @throws ExitException
     * @throws RegistryException
     */
    public static function getPageInfo (string & $page_name)
    {
        $pages = self::getGlobal('pages');
        if (isset($pages[$page_name]) && is_array($pages[$page_name]))
        {
            $pageinfo = & $pages[$page_name];
            if (isset($pageinfo['file']))
            {
                return $pageinfo;
            }
            else
            {
                throw new RegistryException("Не найдено имя файла шаблона для страницы $page_name");
            }
        }
        else
        {
            foreach ($pages as $key => & $value)
            {
                if ((strpos($key, '\/') !== false) && preg_match('/'.$key.'/i', $page_name))
                {
                    $value['return'] = $key;
                    return $value;
                }
            }
            header('Location: /error/404');
            throw new ExitException();
        }
    }

    /**
     * Достать информацию об уровне доступа метода
     *
     * @param $method
     * @return bool
     */
    public static function getMethodAccessLevel (string $method)
    {
        $access_array = self::getGlobal('methods');
        if (isset($access_array[$method]['level']) && is_array($access_array[$method]['level']))
        {
            return $access_array[$method]['level'];
        }
        else
        {
            return [];
        }
    }

    public static function getMethodRelatedPages (string $method)
    {
        $access_array = self::getGlobal('methods');
        if (isset($access_array[$method]['pages']) && is_array($access_array[$method]['pages']))
        {
            return $access_array[$method]['pages'];
        }
        else
        {
            return false;
        }
    }

    /**
     * Дополнительная информация о методе
     *
     * @param $method
     * @return bool
     */
    public static function getMethodAccessData (string $method)
    {
        $access_array = self::getGlobal('methods');
        if (isset($access_array[$method]) && is_array($access_array[$method]))
        {
            return $access_array[$method];
        }
        else
        {
            return false;
        }
    }

    /**
     * Возвращает заявлнное время кэширования результата метода
     *
     * @param $method
     * @return bool
     */
    public static function getMethodExpire (string $method)
    {
        $access_array = self::getGlobal('methods');
        if (isset($access_array[$method]['expire']))
        {
            return $access_array[$method]['expire'];
        }
        else
        {
            return false;
        }
    }

    /**
     * Возвращает набор параметром по умолчанию для метода
     *
     * @param $method
     * @return array
     */
    public static function getAdditionalParams (string $method)
    {
        $access_array = self::getGlobal('methods');
        if (isset($access_array[$method]['additional_params']) && is_array($access_array[$method]['additional_params']))
        {
            return $access_array[$method]['additional_params'];
        }
        else
        {
            return [];
        }
    }

    /**
     * Возвращает адрес страницы по умолчанию для данного уровня доступа
     *
     * @param $level
     * @return mixed
     * @throws RegistryException
     */
    public static function defaultUserRedirect (string $level)
    {
        $redirects = self::getGlobal('redirects');
        if (isset($redirects[$level]) && $redirects[$level])
        {
            return $redirects[$level];
        }
        else
        {
            throw new RegistryException('Нет подходящего редиректа для данного типа пользователя');
        }
    }

    /**
     * Фильтрует некоторые HTML-теги в данных пришедших от клиента
     *
     * @param $str
     * @return mixed
     */
    public static function antiXSS (& $data, string $allowed)
    {
        $regexp1 = "/<\s*($allowed)\s*.*?>/i";
        $regexp2 = "/<\s*\/*\s*(?!(?:$allowed|\/))\s*.*?>/i";

        $sanitize = function (string & $str) use ($regexp1, $regexp2)
        {
            $str = preg_replace($regexp1, '<$1>', $str);

            preg_match_all($regexp2, $str, $matches);

            foreach ($matches[0] as $m)
            {
                $str = str_replace($m, htmlentities($m, ENT_QUOTES), $str);
            }
        };

        if (is_array($data))
        {
            foreach ($data as & $v)
            {
                $sanitize($v);
            }
            unset($v);
        }
        elseif ($data)
        {
            $sanitize($data);
        }
        return $data;
    }

    public static function getPage(string & $url)
    {
        if (in_array($url, ['login', 'register']) && $_SESSION['User']['level'] != LEVELS[1])
        {
            header('Location: /');
            throw new ExitException();
        }
        $source = self::getPageInfo($url);
        $is_granted = false;
        if (isset($source['level']) && is_array($source['level']))
        {
            if (in_array($_SESSION['User']['level'], $source['level']))
            {
                $is_granted = true;
            }
        }
        else
        {
            throw new RegistryException('Не найден массив уровней доступа для метода страницы ' . $url);
        }
        if (!$is_granted)
        {
            if ($_SESSION['User']['level'] == LEVELS[1])
            {
                header('Location: /login?referer=' . $url);
                throw new ExitException();
            }
            else
            {
                header('Location: /error/403');
                throw new ExitException();
            }
        }
        if (isset($source['methods']) && $source['methods'])
        {
            $methods = & $source['methods'];
            if (isset($source['expire']) && $source['expire'])
            {
                $expire = & $source['expire'];
                $cache = MShell::create();
                $html = $cache->getHTML($url);
                if ($html)
                {
                    echo $html;
                    throw new ExitException();
                }
            }
            $page = new SkaTpl($source['file']);
            if (!isset($methods[0]))
            {
                $methods = [$methods];
            }
            foreach ($methods as & $method)
            {
                if (isset($method['method_params']) && is_array($method['method_params']))
                {
                    $mp = & $method['method_params'];
                    if (isset($source['return']) && $source['return'])
                    {
                        Service::applyParams(Service::splitParams($url, $source['return']), $mp);
                    }
                }
                if (in_array($_SESSION['User']['level'], self::getMethodAccessLevel($method['class'].'::'.$method['method'])))
                {
                    if (!isset($method['parent']))
                    {
                        $parent = false;
                    }
                    else
                    {
                        $parent = & $method['parent'];
                    }
                    $response = Service::executeRequest($method);
                    if (is_array($response))
                    {
                        $page->parseResponse($response, $parent);
                    }
                    else
                    {
                        if (isset($source['redirect']))
                        {
                            header("Location: {$source['redirect']}");
                            throw new ExitException();
                        }
                    }
                }
            }
            $html = $page->getTemplate();
            echo $html;
            fastcgi_finish_request();
            if (isset($expire) && $expire)
            {
                $cache = MShell::create();
                $cache->saveHTML($html, $url, $expire);
            }
        }
        else
        {
            echo file_get_contents($source['file']);
        }
    }

    public static function executeAjax ()
    {
        if (isset($_REQUEST['JSONData']))
        {
            $source = json_decode($_REQUEST['JSONData'], true);
        }
        else
        {
            foreach ($_REQUEST as $key => & $value)
            {
                if (!in_array($key, ['class', 'method', 'get_instance', 'static_method', 'pagecache_flush', 'method_params']))
                {
                    $_REQUEST['method_params'][$key] = $value;
                    unset($_REQUEST[$key]);
                }
            }
            unset($value);
            $source = $_REQUEST;
        }
        if (in_array($_SESSION['User']['level'], self::getMethodAccessLevel($source['class'].'::'.$source['method'])))
        {
            if (isset($_FILES) && is_array($_FILES) && count($_FILES))
            {
                if  ($_FILES[array_keys($_FILES)[0]]['name'])
                {
                    $files = Service::saveFiles($_FILES);
                    if ($files && !isset($source['method_params']))
                    {
                        $source['method_params'] = [];
                    }
                    $source['method_params'] += $files;
                }
            }
            $result = Service::executeRequest($source);
            echo json_encode(['success' => $result], JSON_UNESCAPED_UNICODE);
            fastcgi_finish_request();
            if (isset($source['pagecache_flush']) && $source['pagecache_flush'])
            {
                $cache = MShell::create();
                $cache->delHTML($_SERVER['REQUEST_URI']);
                if ($keys = self::getMethodRelatedPages($source['class'].'::'.$source['method']))
                {
                    $cache->delHTMLs($keys);
                }
            }
            unset($source['pagecache_flush']);
            return true;
        }
        else
        {
            if ($_SESSION['User']['level'] == LEVELS[1])
            {
                echo json_encode(['redirect' => '/login'], JSON_UNESCAPED_UNICODE);
            }
            else
            {
                echo json_encode(['error' => 'Недостаточно прав для исполнения метода'], JSON_UNESCAPED_UNICODE);
            }
            return false;
        }
    }

}