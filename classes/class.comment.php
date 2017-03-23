<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 08.08.14
 * Time: 16:33
 */

class CommentException extends Exception { }

class Comment extends Superclass
{
    use Classinfo;

    private static $db = false;

    private $getPostComments = "SELECT
                                 c.id,
                                 c.text AS comment_text,
                                 c.datetime,
                                 c.user_id,
                                 c.from_id,
                                 u.login,
                                 u.thumb,
                                (CASE
                                   WHEN
                                     c.from_id IS NOT NULL
                                   THEN
                                     ''
                                   ELSE
                                     'no_display'
                                 END) AS is_show
                               FROM
                                 comments AS c
                               LEFT JOIN
                                 users AS u
                                 ON
                                   u.id = c.user_id
                               WHERE
                                 post_id = :post_id
                                   AND
                                 c.is_active = true
                               ORDER BY
                                 c.id";

    private $getAdminCommentsSQL = "SELECT
                                     c.id,
                                     c.text,
                                     c.datetime,
                                     c.is_active::text,
                                     u.login
                                   FROM
                                     comments AS c
                                   LEFT JOIN
                                     users AS u
                                     ON
                                       u.id = c.user_id
                                   ORDER BY
                                     id DESC
                                   LIMIT
                                     :limit
                                   OFFSET
                                     :offset";

    public function getAdminComments (array & $data)
    {
        $data['offset'] = ($data['offset'] - 1) * $data['limit'];

        $result = $this->getAdminCommentsSQL($data);
        if (is_array($result))
        {
            return $result;
        }
        else
        {
            throw new CommentException('Не удалось получить список комментариев');
        }
    }

    private $getUserComments = "SELECT
                                  c.*,
                                  f.login
                                FROM
                                  comments
                                LEFT JOIN
                                  users AS f
                                  ON
                                    f.id = c.from_id
                                WHERE
                                  user_id = :user_id
                                    AND
                                  is_active = true
                                ORDER BY
                                  datetime
                                LIMIT
                                  :limit
                                OFFSET
                                  :offset";

    private $newComment =  "INSERT INTO
                             comments
                             ([fields])
                            VALUES
                              [expression]
                            RETURNING
                              id,
                              text AS comment_text,
                              datetime,
                              from_id,
                              (CASE
                                   WHEN
                                     from_id IS NOT NULL
                                   THEN
                                     ''
                                   ELSE
                                     'no_display'
                                 END) AS is_show";

    private $updateComment = "UPDATE
                           comments
                         SET
                           [expression]
                         WHERE
                           id = :id
                         RETURNING
                            [fields]";

    private $delComment = "DELETE FROM
                           comments
                        WHERE
                          id = :id
                        RETURNING
                          id";

    private $activateComment = "UPDATE
                                  comments
                                SET
                                  is_active = true
                                WHERE
                                  id = :id
                                RETURNING
                                  id";

    private $deactivateComment = "UPDATE
                                      comments
                                    SET
                                      is_active = false
                                    WHERE
                                      id = :id
                                    RETURNING
                                      id";

    private $getAdminPageCount = "SELECT
                                    ceil(count(*)::real/10::real) AS page_count
                                  FROM
                                    comments";

} 