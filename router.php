<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 11.04.14
 * Time: 13:50
 */

session_start();

spl_autoload_register( function ($class)
{
    if (file_exists('classes/class.'.strtolower($class).'.php'))
        require_once 'classes/class.'.strtolower($class).'.php';
    elseif (file_exists('traits/trait.'.strtolower($class).'.php'))
        require_once 'traits/trait.'.strtolower($class).'.php';
}, true);

$c = Config::create();

class SystemException extends Exception { }

class ExitException extends Exception { }

try
{
    if (isset($_POST['token']))
    {
        $u = new User();
        $u->socialAuth($_POST['token']);
    }
    if (!isset($_SESSION['User']['level']))
    {
        unset($_SESSION['User']);
        $_SESSION['User']['level'] = LEVELS[1];
    }
    if (isset($_SESSION['User']['login']))
    {
        setcookie('login', $_SESSION['User']['login'], time() + 1800, '/', SESSION_DOMAIN);
    }
    else
    {
        setcookie('login', '', time() - 3600, '/', SESSION_DOMAIN);
    }
    if (isset($_REQUEST['page']))
    {
        Registry::getPage($_REQUEST['page']);
    }
    else
    {
        Registry::executeAjax();
    }
    fastcgi_finish_request();
    $cache = MShell::create();
    $cache->commit();
}
catch (ExitException $e)
{

}
catch (Throwable $e)
{
    Service::errorFinish($e);
}