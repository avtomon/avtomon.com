<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 22.08.14
 * Time: 10:45
 */

class UserException extends Exception { }

class User extends Superclass
{
    use Classinfo;

    private static $db = false;

    public function logout ()
    {
        unset($_SESSION['User']);
        unset($_SESSION['methods']);
        unset($this);
        return true;
    }

    public function auth (array & $data)
    {
        $sql = "SELECT
                        id,
                        login,
                        level,
                        email,
                        thumb
                     FROM
                        users
                     WHERE
                        hash = :hash";
        if (isset($data['hash']))
        {
            $result = self::$db->getValue($sql, $data);
            if ($result)
            {
                unset($_SESSION['methods']);
                unset($_SESSION['User']);
                return $result;
            }
            else
            {
                return false;
            }
        }
    }

    public function socialAuth (string & $token)
    {
        $user = json_decode(file_get_contents("http://ulogin.ru/token.php?token=$token&host=" . SITE_HOST), true);
        if (is_array($user))
        {
            $result = $this->getUserByEmail(['email' => $user['email']]);
            if (!is_array($result))
            {
                $udata = ['login'        => $user['nickname'],
                          'hash'         => substr(md5(md5(time()) + microtime()), 0, 8),
                          'thumb'        => isset($user['photo']) ? $user['photo'] : NULL,
                          'socials'      => '"' . $user['network'] . '"=>"' . $user['profile'] . '"',
                          'email'        => $user['email'],
                          'last_update_user_id' => 0,
                          'sex'          => isset($user['sex']) ? $user['sex'] : NULL,
                          'avatar'       => isset($user['photo_big']) ? $user['photo_big'] : NULL,
                          'city'         => isset($user['city']) ? $user['city'] : NULL,
                          'country'      => isset($user['country']) ? $user['country'] : NULL,
                          'birthdate'    => isset($user['bdate']) ? $user['bdate'] : NULL,
                          'name'         => isset($user['first_name']) ? $user['first_name'] : NULL,
                          'surname'      => isset($user['last_name']) ? $user['last_name'] : NULL,
                          'phone_number' => isset($user['phone']) ? $user['phone'] : NULL,
                          'social_id'    => isset($user['uid']) ? $user['uid'] : NULL,
                          'social_name'  => isset($user['network']) ? $user['network'] : NULL];
                try
                {
                    $result = $this->register();
                }
                catch (UserException $e)
                {
                    if ($e->getMessage() == 'Пользователь с таким логином или почтой уже существует')
                    {
                        $udata['login'] = $udata['email'];
                        $result = $this->register();
                    }
                }
            }
            $_SESSION['User'] = $result[0];
        }
    }

    private $checkUser = "SELECT
                           id,
                           login,
                           level,
                           email,
                           thumb
                         FROM
                           users
                         WHERE
                           login = :login
                             OR
                           email = :email";

    public function register (array & $data)
    {
        $user = $this->checkUser(Registry::getAllValidateFields($data, 'User::checkUser'));
        if (is_array($user))
        {
            throw new UserException('Пользователь с таким логином или почтой уже существует');
        }
        else
        {
            if (isset($data['interests']))
            {
                $data['interests'] = json_encode(array_flip($data['interests']), JSON_UNESCAPED_UNICODE);
                $result = $this->saveUserWithInterests(Registry::getAllValidateFields($data, 'User::saveUserWithInterests'));
            }
            else
            {
                $result = $this->saveUser(Registry::getAllValidateFields($data, 'User::saveUser'));
            }
            if (is_array($result))
            {
                unset($_SESSION['methods']);
                unset($_SESSION['User']);
                $confirm_hash = md5($data['hash'] . $data['email']);
                Service::mailSend([$data['email']],
                                   'Добро пожаловать на ' . SITE_HOST,
                                   "Здравствуйте, {$data['login']}.<br>
                                    Вы зарегистрированы на сайте.<br>
                                    Если не сложно, подтвердите ваш адрес электронной почты перейдя по <a href='http://" . SITE_HOST . "/confirm_email/$confirm_hash'>ссылке</a>.");
                return $result;
            }
            else
            {
                throw new UserException('Не удалось сохранить нового пользователя');
            }
        }
    }

    public function passwordRecovery (array & $data)
    {
        $sql = "UPDATE
                  users
                SET
                  hash = :hash
                WHERE
                  lower(email) = lower(:email)
                RETURNING
                  id";
        $new_pass = substr(password_hash(microtime(), PASSWORD_BCRYPT), -8);
        $result = $this->getUserByEmail(['email' => $data['email']]);
        if (is_array($result))
        {
            $data['hash'] = md5($result[0]['login'] . $new_pass);
            $result2 = self::$db->getValue($sql, $data);
            if (is_array($result2))
            {

                if (Service::mailSend([$data['email']], 'Восстановление пароля', "Здравствуйте, {$result[0]['login']}.<br>
                                                                                  Ваш пароль был изменен, новый пароль: $new_pass"))
                {
                    return true;
                }
                else
                {
                    throw new UserException('Не удалось отправить пользователю новый пароль');
                }
            }
            else
            {
                throw new UserException('Неудалось изменить пароль пользователя');
            }
        }
        else
        {
            throw new UserException('Пользователь с данным адресом почты не найден');
        }
    }

    private $getUserInfo = "SELECT
                              u.login,
                              u.avatar,
                              u.regtime,
                              u.socials,
                              COUNT(c.*) AS comment_count
                            FROM
                              users AS u
                            LEFT JOIN
                              comments AS c
                              ON
                                c.user_id = u.id
                            WHERE
                              id = :id
                            GROUP BY
                              u.id";

    private $getUserListSQL = "SELECT
                              u.id,
                              u.login,
                              u.regtime,
                              COUNT(c.*) AS comment_count,
                              u.is_active::text
                            FROM
                              users AS u
                            LEFT JOIN
                              comments AS c
                              ON
                                c.user_id = u.id
                            GROUP BY
                              u.id
                            ORDER BY
                              u.login
                            LIMIT
                              :limit
                            OFFSET
                              :offset";

    public function getUserList (array & $data)
    {
        $data['offset'] = ($data['offset'] - 1) * $data['limit'];

        $result = $this->getUserListSQL($data);
        if (is_array($result))
        {
            return $result;
        }
        else
        {
            throw new UserException('Не удалось получить список пользователей');
        }
    }

    private $saveUser = "INSERT INTO
                           users
                           ([fields])
                         VALUES
                           [expression]
                         RETURNING
                           id,
                           login,
                           level,
                           email,
                           thumb";

    private $saveUserWithInterests = "WITH user_t AS (
                                         INSERT INTO
                                           users
                                           ([fields:not(interests)])
                                         VALUES
                                           [expression:not(interests)]
                                         RETURNING
                                           id,
                                           login,
                                           level,
                                           email,
                                           thumb),
                                           
                                      ut AS (INSERT INTO
                                        user_tag
                                      SELECT
                                        u.id,
                                        value::integer
                                      FROM
                                        user_t AS u,
                                        json_object_keys(:interests) AS value)
                                      
                                      SELECT 
                                        *
                                      FROM
                                        user_t";

    private $updateUser = "UPDATE
                             users
                           SET
                             [expression]
                           WHERE
                             id = :id
                           RETURNING
                             [fields]";

    private $deactivateUser = "UPDATE
                          users
                        SET
                          is_active = false
                        WHERE
                          id = :id
                        RETURNING
                          id";

    private $activateUser = "UPDATE
                              users
                            SET
                              is_active
                            WHERE
                              id = :id
                            RETURNING
                              id";

    private $getInterests = "SELECT
                               id,
                               tag
                             FROM
                               tags
                             WHERE
                               is_active
                                 AND
                               in_interests";

    private $getAdminPageCount = "SELECT
                                    ceil(count(*)::real/20::real) AS page_count
                                  FROM
                                    users";

    private $getUserByEmail = "SELECT
                                    id,
                                    login,
                                    level,
                                    email,
                                    thumb
                                 FROM
                                    users
                                 WHERE
                                    email = :email";

    private $confirmationEmail = "UPDATE
                                    users
                                  SET
                                    is_confirmed_email = true
                                  WHERE
                                    md5(hash || email) = :email_hash
                                      AND 
                                    NOT is_confirmed_email
                                  RETURNING
                                    'Ваш адрес электронной почты успешно подтвержден' AS text";
} 