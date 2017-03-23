<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 11.04.14
 * Time: 15:09
 */

//require_once $_SERVER['DOCUMENT_ROOT'] . PHPMAILER_PATH . 'PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

class ServiceException extends Exception { }

class Service
{
    /**
     * Функция загрузки массива файлов на сервер
     *
     * @param array $files - массив файлов, которые прислала форма и мета-информация о них
     * @return mixed|string
     * @throws Exception
     */
    public static function saveFiles(array & $files)
    {
        $saveFile = function (array & $file, string & $file_path, string & $upload_path, $index = -1)
        {
            if ($index == -1)
            {
                $file = [$file];
                $index = 0;
            }

            $fn = & $file['name'][$index];
            $tn = & $file['tmp_name'][$index];
            $fe = & $file['error'][$index];
            $fs = & $file['size'][$index];

            if (!$fe && $fs)
            {
                if (isset($file_path))
                {
                    $name_array = explode(".", $fn);
                    $ext = end($name_array);
                    $new_name = preg_replace('/[\s,\/:;\?!*&^%#@$|<>~`]/i', '', str_replace(' ', '_', str_replace($ext, '', $fn) . microtime(true)));
                    //$mime_type = Registry::validateFileMimeType($tn);
                    if (Registry::validateFileExt($ext) /*&& $mime_type*/ && filesize($tn) <= (1048576 * FILE_MAX_SIZE) && is_uploaded_file($tn))
                    {
                        $new_name = "$new_name.".$ext;
                        $path = $file_path . '/' . $new_name;
                        $f = file_get_contents($tn);
                        if (file_put_contents($upload_path . $new_name, $f))
                        {
                            return $path;
                        }
                        else
                            throw new ServiceException("Файл $fn не был корректно сохранен");
                    }
                    else
                    {
                        unlink($tn);
                        throw new ServiceException('Файл не может быть сохранен на сервере. Проверьте тип, расширение и размер файла (размер не должен превышать' . FILE_MAX_SIZE . 'байт) и обратитесь к администратору системы');
                    }
                }
                else
                    throw new ServiceException("Не найден путь для файла $fn");
            }
            else
                throw new  ServiceException("Файл $fn не был корректно загружен из формы");
        };

        $result = [];
        foreach ($files as $field => & $file)
        {
            $file_path = Registry::getFilePath($field);
            $upload_path = $_SERVER['DOCUMENT_ROOT'] . $file_path . '/';
            $fn = & $file['name'];
            if (is_array($fn))
            {
                $count = count($fn);
                for ($i = 0; $i < $count; $i++)
                {
                    if ($fn[$i])
                    {
                        $result[$field][] = $saveFile($file, $file_path, $upload_path, $i);
                    }
                }
            }
            else
            {
                $result[$field] = $saveFile($file, $file_path, $upload_path);
            }
        }
        unset($file);
        return $result;
    }

    /**
     * Корректное завешение скрипта с ошибкой
     *
     * @param Throwable $e
     * @throws MShellException
     * @throws ServiceException
     */
    public static function errorFinish (Throwable & $e)
    {
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        fastcgi_finish_request();
        $cache = MShell::create();
        $cache->rollback();
        if (in_array(get_class($e), ['Error', 'TypeError', 'ParseError', 'AssertionError']))
        {
            self::writeErrorLog($e);
        }
        else
        {
            self::writeExceptionLog($e);
        }
    }

    /**
     * Запись в лог исключений
     *
     * @param Exception $e
     * @throws ServiceException
     */
    public static function writeExceptionLog (Exception & $e)
    {
        if (!file_put_contents($_SERVER['DOCUMENT_ROOT'] . Registry::getLogPath('exception'), date("d.m.Y H:i:s") . ' ' . $e->getMessage(), FILE_APPEND))
            echo json_encode(['error' => 'Лог исключений не был записан'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Запись в debug-лог
     *
     * @param $message
     * @throws ServiceException
     */
    public static function writeDebugLog (string $message)
    {
        if (file_put_contents($_SERVER['DOCUMENT_ROOT'] . Registry::getLogPath('debug'), date("d.m.Y H:i:s") . ' ' . $message, FILE_APPEND))
            throw new ServiceException ('Debug-лог не был записан');
    }

    /**
     * Запись в лог ошибок
     *
     * @param Error $e
     * @throws RegistryException
     * @throws ServiceException
     */
    public static function writeErrorLog (Error & $e)
    {
        if (file_put_contents($_SERVER['DOCUMENT_ROOT'] . Registry::getLogPath('error'), date("d.m.Y H:i:s") . ' ' . $e->getMessage(), FILE_APPEND))
            throw new ServiceException ('Лог ошибок не был записан');
    }

    /**
     * Формирование строки плейсхолдеров для SQL-запросов
     *
     * @param array $data
     * @param string $type
     * @return string
     */
    public static function createPrepareFields (array & $data, string $type = 'insert')
    {
        $string = '';
        if ($type == 'insert')
        {
            if (isset($data[0]) && is_array($data[0]))
            {
                foreach ($data as $index => & $value)
                {
                    $tmp = '';
                    foreach ($value as $k => & $v)
                    {
                        $tmp .= ":$k$index,";
                        $data_tmp[$k . $index] = $v;
                    }
                    unset($v);
                    $string .= '(' . trim($tmp, ',') . '),';
                }
                $string = trim($string, ',');
                $data = $data_tmp;
                unset($data_tmp, $tmp, $value);
            }
            else
            {
                $string = '(:' . implode(',:', $data) . ')';
            }
        }
        else if ($type == 'inline')
        {
            if (isset($data[0]))
            {
                foreach ($data as $index => & $value)
                {
                    $string .= '(' . implode(',', $value) . '),';
                }
                unset($value);
                $string = trim($string, ',');
            }
            else
            {
                $string = '(' . implode(',', $data) . ')';
            }
        }
        else
        {
            foreach (array_keys($data) as $key)
            {
                if ($key !== 'i')
                    $string .= "$key = :$key,";
            }
            $string = trim($string, ',');
        }
        return $string;
    }

    /**
     * Убрать из ответа NULL-значения
     *
     * @param $data
     * @return array
     */
    public static function disableNulls (& $data)
    {
        if (is_array($data))
        {
            $offNull = function (& $value)
            {
                return $value === null ? '' : $value;
            };
            if (isset($data[0]) && is_array($data[0]))
            {
                foreach ($data as $key => & $value)
                {
                    $value = array_map($offNull, $value);
                }
                unset($value);
            }
            else
            {
                $data = array_map($offNull, $data);
            }
        }
        return $data;
    }

    /**
     * Формирование списка полей для SQL-запросов
     *
     * @param array $data
     * @return string
     */
    public static function createSelectString (array $data)
    {
        if (isset($data[0]))
            $data = $data[0];

        return implode(',', array_keys($data));
    }

    /**
     * Разбор SQL-шаблона
     *
     * @param $request
     * @param array $data
     * @return mixed
     */
    public static function sql (string & $request, array & $data)
    {
        $createExpression = function(& $data, $pos) use ($request)
        {
            $i_pos = strripos(substr($request, 0, $pos), 'INSERT');
            $ui_pos = strripos(substr($request, 0, $pos), 'upsert');
            $u_pos = strripos(substr($request, 0, $pos), 'UPDATE');
            if (($i_pos !== false && ($i_pos > $u_pos || $u_pos === false)) || ($ui_pos !== false && ($ui_pos > $u_pos || $u_pos === false)))
            {
                return self::createPrepareFields($data);
            }
            elseif ($u_pos !== false && ($u_pos > $i_pos || $i_pos === false))
            {
                $data2 = $data;
                foreach(array_keys($data) as $key)
                    if (strpos($request, ':' . $key) !== false)
                    {
                        unset($data2[$key]);
                    }
                return self::createPrepareFields($data2, 'update');
            }
        };

        preg_match_all('/\[fields\:not\((.+?)\)\]/i', $request, $match);
        if ($match)
        {
            foreach ($match[0] as $key => & $value)
            {
                if ($value)
                {
                    if (isset($data[0]) && is_array($data[0]))
                    {
                        $new_data = $data[0];
                    }
                    else
                    {
                        $new_data = $data;
                    }
                    $request = str_replace($value, self::createSelectString(array_diff_key($new_data, array_flip(explode(',', str_replace(' ', '',  $match[1][$key]))))), $request);
                }
            }
            unset($value);
        }
        $new_data = [];
        preg_match_all('/\[expression:not\((.+?)\)\]/i', $request, $match, PREG_OFFSET_CAPTURE);
        if ($match)
        {
            foreach ($match[0] as $key => & $value)
            {
                if ($value)
                {
                    if (isset($data[0]) && is_array($data[0]))
                    {
                        foreach($data as $k => & $v)
                        {
                            $new_data[$k] = array_diff_key($v, array_flip(explode(',', str_replace(' ', '',  $match[1][$key][0]))));
                        }
                        unset($v);
                    }
                    else
                    {
                        $new_data = array_diff_key($data, array_flip(explode(',', str_replace(' ', '',  $match[1][$key][0]))));
                    }
                    $request = str_replace($value[0], $createExpression($new_data, $value[1]), $request);
                    $createExpression($data, $value[1], $request);
                    foreach(array_keys($data) as $k)
                        if (strpos($request, ':' . $k) === false)
                        {
                            unset($data[$k]);
                        }
                }
            }
            unset($value);
        }
        if (stripos($request, '[fields]'))
        {
            $request = str_replace('[fields]', self::createSelectString($data), $request);
        }
        if (preg_match_all('/\[expression\]/i', $request, $match, PREG_OFFSET_CAPTURE))
        {
            foreach ($match[0] as $key => & $value)
            {
                if ($value)
                {
                    $request = str_replace('[expression]', $createExpression($data, $value[1]), $request);
                }
            }
            unset($value);
        }

        return $request;
    }

    public static function addDefaultParams (string & $method, array & $data = [])
    {
        $tmp = Registry::getAdditionalParams($method);

        if ($data)
        {
            foreach ($tmp as $key => & $value)
            {
                if ($value != 'outer')
                {
                    $data[$key] = $value;
                }
            }
            unset($value);
        }
        elseif ($tmp)
        {
            $data = $tmp;
        }

        return $data;
    }

    /**
     * Исполнение метода
     *
     * @param array $params
     * @return mixed
     * @throws ServiceException
     */
    public static function executeRequest (array & $params)
    {
        if (!isset($params['class']) || !isset($params['method']) || !$params['class'] || !$params['method'])
        {
            throw new ServiceException('Недостаточно параметров для исполнения запроса. Обязательны параметры "class" и "method"');
        }
        $c = & $params['class'];
        $m = & $params['method'];
        $cm = "$c::$m";
        $expire = Registry::getMethodExpire($cm);

        if (isset($params['method_params']) && is_array($params['method_params']))
        {
            $mp = $params['method_params'] + Registry::getAdditionalParams($cm);
        }
        else
        {
            $mp = Registry::getAdditionalParams($cm);;
        }
        $mp = Registry::getAllValidateFields($mp, $cm);

        $refclass = new ReflectionClass($c);
        if (!$params['static_method'])
        {
            if (isset($params['get_instance']) && $params['get_instance'])
            {
                $create = $refclass->getMethod('create');
                if (isset($params['class_params']))
                    $object = $create->invoke(null, $params['class_params']);
                else
                    $object = $create->invoke(null);
            }
            else
                if (isset($params['class_params']))
                    $object = $refclass->newInstance($params['class_params']);
                else
                    $object = $refclass->newInstance();

            if (isset($mp) && is_array($mp))
            {
                if ($expire)
                {
                    $result = $object->$m($mp, $expire);
                }
                else
                {
                    $result = $object->$m($mp);
                }
            }
            else
            {
                if ($expire)
                    $result = $object->$m([], $expire);
                else
                    $result = $object->$m();
            }
        }
        else
            if (isset($mp) && is_array($mp))
            {
                if ($expire)
                    $result = $c::$m($mp, $expire);
                else
                    $result = $c::$m($mp);
            }
            else
            {
                if ($expire)
                    $result = $c::$m($expire);
                else
                    $result = $c::$m();
            }

        self::disableNulls($result);
        self::setSession($result, $cm);

        return $result;
    }

    /**
     * Установка значений сессий и куки
     *
     * @param array $data
     * @param string $method
     * @return bool
     * @throws ServiceException
     */
    public static function setSession ($data, string & $method)
    {
        if (is_array($data))
        {
            if (isset($data[0]))
                $data = $data[0];

            if ($method_data = Registry::getMethodAccessData($method))
            {
                if (isset($method_data['session']) && isset($method_data['session']['data']) && is_array($method_data['session']['data']))
                {
                    $sessions = & $method_data['session'];
                    $d = & $sessions['data'];
                    if ($diff = array_diff($d, array_keys($data)))
                    {

                    }
                    elseif (isset($sessions['parent']))
                    {
                        $parent = $sessions['parent'];
                        foreach ($d as & $value)
                        {
                            $_SESSION[$parent][$value] = $data[$value];
                        }
                        unset($value);
                    }
                    else
                    {
                        foreach ($d as & $value)
                        {
                            $_SESSION[$value] = $data[$value];
                        }
                        unset($value);
                    }
                    unset($d);
                }
                if (isset($method_data['cookie']) && isset($method_data['cookie']['data']) && is_array($method_data['cookie']['data']))
                {
                    $cookies = & $method_data['cookie'];
                    $d = & $cookies['data'];
                    if ($diff = array_diff($d, array_keys($data)))
                    {

                    }
                    foreach ($d as & $value)
                    {
                        setcookie($value, $data[$value], time() + 3600);
                    }
                    unset($value);
                    unset($d);
                }
            }
        }
    }

    /**
     * Отправка почты
     *
     * @param array $addresses
     * @param string $subject
     * @param string $message
     * @param array $files
     * @return bool
     * @throws phpmailerException
     */
    public static function mailSend (array $addresses, string $subject, string $message, array $files = [])
    {
        $mail = new PHPMailer;
        $mail->setLanguage(MAIL_LANG, $_SERVER['DOCUMENT_ROOT'] . PHPMAILER_PATH . 'language/phpmailer.lang-' . MAIL_LANG . '.php');
        $mail->CharSet = MAIL_CHARSET;
        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PASSWORD;
        $mail->SMTPSecure = MAIL_SMTPSECURE;
        $mail->Port = MAIL_PORT;

        $mail->From = MAIL_FROM;
        $mail->FromName = MAIL_FROM_NAME;
        foreach ($addresses as & $value)
        {
            $mail->addAddress($value);
        }
        unset($value);
        $mail->addReplyTo(MAIL_REPLYTO_ADDRESS, MAIL_REPLYTO_NAME);

        $mail->WordWrap = 50;
        foreach ($files as $file)
        {
            $mail->addAttachment($file);
        }
        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body    = $message;

        if(!$mail->send())
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Получить массив параметров из урла
     *
     * @param $str
     * @return array
     */
    public static function splitParams (string & $str, string & $regex)
    {
        if (preg_match_all("/$regex/ui", $str, $match, PREG_SET_ORDER))
        {
            $match = $match[0];
            unset($match[0]);
            $result = [];
            foreach ($match as & $value)
            {
                $result[] = $value;
            }
            unset($value);

            return $result;
        }
        else
        {
            return false;
        }
    }

    /**
     * Сформировать структуру параметров для метода
     *
     * @param array $params
     * @param array $method_params
     * @return array
     */
    public static function applyParams (array $params, array & $method_params)
    {
        foreach ($method_params as $key => & $value)
        {
            if (preg_match('/^\$(\d)$/', $value, $match))
            {
                $value = isset($params[$match[1]]) ? $params[$match[1]] : false;
            }
        }
        unset($value);
        return $method_params;
    }

    /**
     * Добавить ведущий ноль перед числовым значением из одного знака
     *
     * @param $int
     * @return string
     */
    public static function addZero ($int)
    {
        if ($int < 10)
        {
            return "0$int";
        }
        else
        {
            return $int;
        }
    }

    /**
     * Сформировать Postgres-массив из PHP-массива
     *
     * @param $array
     * @return array
     */
    public static function createPSQLArray (array & $array)
    {
        if ($array)
        {
            if (is_array($array))
            {
                $array = implode(',', $array);
            }
            $array = '{' . $array . '}';
            return $array;
        }
        else
        {
            return false;
        }
    }

    public static function buildSitemap ()
    {
        $date = date('Y-m-d');
        $map = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">
               <url>
                  <loc>http://" . SITE_HOST . "</loc>
                  <lastmod>$date</lastmod>
                  <changefreq>hourly</changefreq>
                  <priority>0.8</priority>
               </url>
               <url>
                  <loc>http://" . SITE_HOST . "/news</loc>
                  <lastmod>$date</lastmod>
                  <changefreq>hourly</changefreq>
                  <priority>0.8</priority>
               </url>
               <url>
                  <loc>http://" . SITE_HOST . "/articles</loc>
                  <lastmod>$date</lastmod>
                  <changefreq>hourly</changefreq>
                  <priority>0.8</priority>
               </url>
               <url>
                  <loc>http://" . SITE_HOST . "/views</loc>
                  <lastmod>$date</lastmod>
                  <changefreq>hourly</changefreq>
                  <priority>0.8</priority>
               </url>
               <url>
                  <loc>http://" . SITE_HOST . "/projects</loc>
                  <lastmod>$date</lastmod>
                  <changefreq>hourly</changefreq>
                  <priority>0.8</priority>
               </url>";

        $p = new Post();

        $createPageMap = function (int & $count, string $type, string & $map) use (& $date)
        {
            for ($i = 2; $i <= $count; $i++)
            {
                $map .= "<url>
                  <loc>http://" . SITE_HOST . "/$type/page/$i</loc>
                  <lastmod>$date</lastmod>
                  <changefreq>hourly</changefreq>
                  <priority>0.8</priority>
               </url>";
            }
        };

        $pc = $p->getPageCountByType(['type' => 'Новость'])[0]['page_count'];
        $createPageMap($pc, 'news', $map);
        $pc = $p->getPageCountByType(['type' => 'Статья'])[0]['page_count'];
        $createPageMap($pc, 'articles', $map);
        $pc = $p->getPageCountByType(['type' => 'Мнение'])[0]['page_count'];
        $createPageMap($pc, 'views', $map);

        $pr = new Project();

        $pc = $pr->getPageCount()[0]['page_count'];
        if ($pc)
            $createPageMap($pc, 'projects', $map);

        $createTagMap = function (array & $tags) use (& $map, & $date)
        {
            foreach ($tags as & $value)
            {
                $map .= "<url>
                  <loc>{$value['url']}</loc>
                  <lastmod>$date</lastmod>
                  <changefreq>hourly</changefreq>
                  <priority>0.3</priority>
               </url>";
            }
            unset($value);
        };

        $t = new Tag();
        $pt = $t->getTagsToRSS();
        if ($pt)
            $createTagMap($pt);

        $createPostsMap = function (array & $posts, string & $map)
        {
            foreach ($posts as & $value)
            {
                $map .= "<url>
                  <loc>{$value['url']}</loc>
                  <lastmod>" . date('Y-m-d', $value['date']) . "</lastmod>
                  <changefreq>daily</changefreq>
                  <priority>0.6</priority>
               </url>";
            }
            unset($value);
        };

        $posts = $p->getPostsToRSS();
        if ($posts)
            $createPostsMap($posts, $map);

        $projects = $pr->getProjectsToRSS();
        if ($projects)
            $createPostsMap($projects, $map);

        $map .= '</urlset>';

        if (!file_put_contents('sitemap.xml', $map))
        {
            throw new ServiceException('Не удалось записать файл sitemap');
        }

    }

}

?>