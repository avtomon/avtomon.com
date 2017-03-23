<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 08.08.14
 * Time: 14:34
 */

class PostException extends Exception { }

class Post extends Superclass
{
    use Classinfo;

    private static $db = false;

    private $getRSSPosts = "SELECT
                              p.id,
                              title,
                             (CASE
                                WHEN
                                  type = 'Новость'
                                THEN
                                  CONCAT('" . RSS_DOMAIN . "/news/', p.id)
                                WHEN
                                  type = 'Статья'
                                THEN
                                  CONCAT('" . RSS_DOMAIN . "/articles/', p.id)
                                WHEN
                                  type = 'Мнение'
                                THEN
                                  CONCAT('" . RSS_DOMAIN . "/views/', p.id)
                              END) AS link,
                              '" . RSS_MANAGING_EDITION . "' AS author,
                              brief AS description,
                              date AS pubDate,
                              'Все публикации " . RSS_DOMAIN . "' AS source_title,
                              json_agg(t) AS category
                            FROM
                              posts AS p
                            LEFT JOIN
                              post_tag AS pt
                              ON
                                pt.post_id = p.id
                            LEFT JOIN
                              tags AS t
                              ON
                                pt.tag_id = t.id
                                  AND
                                t.is_active = true
                            WHERE
                              p.is_active = true
                            GROUP BY
                              p.id,
                              title,
                              brief,
                              type,
                              date
                            ORDER BY
                              p.id DESC";

    private $getRSSNews = "SELECT
                              p.id,
                              title,
                              CONCAT('" .RSS_DOMAIN . "/news/', p.id) AS link,
                              '" . RSS_MANAGING_EDITION . "' AS author,
                              brief AS description,
                              date AS pubDate,
                              'Все новости " . RSS_DOMAIN . "' AS source_title,
                              json_agg(t) AS category
                            FROM
                              posts AS p
                            LEFT JOIN
                              post_tag AS pt
                              ON
                                pt.post_id = p.id
                            LEFT JOIN
                              tags AS t
                              ON
                                pt.tag_id = t.id
                                  AND
                                t.is_active = true
                            WHERE
                              type = 'Новость'
                                AND
                              p.is_active = true
                            GROUP BY
                              p.id,
                              title,
                              brief,
                              date
                            ORDER BY
                              id DESC";

    private $getRSSArticles = "SELECT
                                  p.id,
                                  title,
                                  CONCAT('" . RSS_DOMAIN . "/articles/', p.id) AS link,
                                  '" . RSS_MANAGING_EDITION . "' AS author,
                                  brief AS description,
                                  date AS pubDate,
                                  'Все статьи " . RSS_DOMAIN . "' AS source_title,
                                  json_agg(t) AS category
                                FROM
                                  posts AS p
                                LEFT JOIN
                                  post_tag AS pt
                                  ON
                                    pt.post_id = p.id
                                LEFT JOIN
                                  tags AS t
                                  ON
                                    pt.tag_id = t.id
                                      AND
                                    t.is_active = true
                                WHERE
                                  type = 'Статья'
                                    AND
                                  p.is_active = true
                                GROUP BY
                                  p.id,
                                  title,
                                  brief,
                                  date
                                ORDER BY
                                  id DESC";

    private $getRSSViews = "SELECT
                              p.id,
                              title,
                              CONCAT('" . RSS_DOMAIN . "/views/', p.id) AS link,
                              '" . RSS_MANAGING_EDITION . "' AS author,
                              brief AS description,
                              date AS pubDate,
                              'Все мнения " . RSS_DOMAIN . "' AS source_title,
                              json_agg(t) AS category
                            FROM
                              posts AS p
                            LEFT JOIN
                              post_tag AS pt
                              ON
                                pt.post_id = p.id
                            LEFT JOIN
                              tags AS t
                              ON
                                pt.tag_id = t.id
                                  AND
                                t.is_active = true
                            WHERE
                              type = 'Мнение'
                                AND
                              p.is_active = true
                            GROUP BY
                              p.id,
                              title,
                              brief,
                              date
                            ORDER BY
                              id DESC";

    private $getRusPostListSQL = "SELECT
                                 p.id,
                                 title,
                                 brief,
                                 date,
                                 json_agg(t) AS tags
                               FROM
                                 posts AS p
                               LEFT JOIN
                                 (SELECT
                                    pt.post_id,
                                    t.tag,
                                    t.id AS tag_id
                                  FROM
                                    post_tag AS pt
                                  RIGHT JOIN
                                    tags AS t
                                  ON
                                    t.id = pt.tag_id
                                  WHERE
                                    t.is_active = true) AS t
                               ON
                                 t.post_id = p.id
                               WHERE
                                 type = :type
                                   AND
                                 p.is_active = true
                               GROUP BY
								 p.id,
                                 title,
                                 brief,
                                 date
                               ORDER BY
                                 id DESC
                               LIMIT
                                 :limit
                               OFFSET
                                 :offset";

    public function getRusPostList (array & $data)
    {
        $data['offset'] = ($data['offset'] - 1) * $data['limit'];

        $result = $this->getRusPostListSQL($data);
        if (is_array($result))
        {
            foreach ($result as $key => & $value)
            {
                $value['tags'] = $value['tags'] != '[null]' ? json_decode($value['tags'], true) : null;
            }
            unset($value);
        }
        return $result;
    }

    private $getAdminPostListSQL = "SELECT
                                     id,
                                     title,
                                     type,
                                     date,
                                     is_active::text
                                   FROM
                                     posts
                                   ORDER BY
                                     id DESC
                                   LIMIT
                                     :limit
                                   OFFSET
                                     :offset";

    public function getAdminPostList (array & $data)
    {
        $data['offset'] = ($data['offset'] - 1) * $data['limit'];

        $result = $this->getAdminPostListSQL($data);
        if (is_array($result))
        {
            return $result;
        }
        else
        {
            throw new PostException('Не удалось получить список постов');
        }
    }

    private $getEngPostList = "SELECT
                                 id,
                                 eng_title,
                                 eng_brief,
                                 date
                               FROM
                                 posts
                               WHERE
                                 type = :type
                                   AND
                                 is_active = true
                               ORDER BY
                                 id DESC
                               LIMIT
                                 :limit
                               OFFSET
                                 :offset";

    private $newPostSQL = "WITH post AS (INSERT INTO
                                           posts
                                           ([fields:not(tags)])
                                         VALUES
                                           [expression:not(tags)]
                                         RETURNING
                                           id,
                                           type),
                                           
                                      tags AS (INSERT INTO
                                        post_tag
                                      SELECT
                                        p.id,
                                        t.t
                                      FROM
                                        post AS p,
                                        unnest(:tags::int[]) AS t
                                      RETURNING
                                        *)
                                        
                                      SELECT
                                        id
                                      FROM 
                                        post";

    private $updatePostSQL = "WITH pt AS (INSERT INTO
                                           post_tag
                                         SELECT
                                           :id,
                                           t.t
                                         FROM
                                           unnest(:tags::int[]) AS t
                                         ON CONFLICT 
                                          (post_id,
                                           tag_id) 
                                           DO NOTHING
                                         RETURNING
                                            *)
                                            
                                         UPDATE
                                           posts
                                         SET
                                           [expression:not(tags)]
                                         WHERE
                                           id = :id
                                         RETURNING
                                           id";

    public function newPost (array & $data)
    {
        if (!isset($data['tags']) || !Service::createPSQLArray($data['tags']))
        {
            $data['tags'] = '{}';
        }
        $result = $this->newPostSQL(Registry::getAllValidateFields($data, 'Post::newPostSQL'));
        if (is_array($result))
        {
            $rss = new RSS();
            $rss->createAllRSS();
            $rss->switchType($data['type']);
            $rss->createRSSFromLiteralType($data['type']);

            Service::buildSitemap();

            return $result;
        }
        else
        {
            throw new PostException('Не удалось сохранить пост');
        }
    }

    public function updatePost (array & $data)
    {
        self::$db->beginTransaction();
        $this->clearPostTags(Registry::getAllValidateFields($data, 'Post::clearPostTags'));
        if (!isset($data['tags']) || !Service::createPSQLArray($data['tags']))
        {
            $data['tags'] = '{}';
        }
        $result = $this->updatePostSQL(Registry::getAllValidateFields($data, 'Post::updatePostSQL'));
        if (is_array($result))
        {
            $rss = new RSS();
            $rss->createAllRSS();
            $rss->switchType($data['type']);
            $rss->createRSSFromLiteralType($data['type']);

            Service::buildSitemap();

            self::$db->delHTMLs(["news/{$data['id']}", "articles/{$data['id']}", "views/{$data['id']}"]);

            return $result;
        }
        else
        {
            throw new PostException('Не удалось сохранить пост');
        }
    }

    private $clearPostTags = "DELETE FROM
                                post_tag
                              WHERE
                                post_id = :id
                              RETURNING
                                *";

    private $addTagsToPost = "INSERT INTO
                                post_tag
                                ([fields])
                              VALUES
                                [expression]
                              RETURNING
                                [fields]";

    private $delTagsFromPost = "DELETE FROM
                                       post_tag
                                    WHERE
                                      post_id = :post_id
                                        AND
                                      tag_id = :tag_id
                                    RETURNING
                                      id";

    private $delPostSQL = "DELETE FROM
                           posts
                        WHERE
                          id = :id
                        RETURNING
                          id,
                          type";

    public function delPost (array & $data)
    {
        $result = $this->delPostSQL($data);
        if (is_array($result))
        {
            $rss = new RSS();
            $rss->createAllRSS();
            $rss->switchType($result[0]['type']);
            $rss->createRSSFromLiteralType($result[0]['type']);

            Service::buildSitemap();
        }
        else
        {
            throw new PostException('Не удалось удалить пост');
        }
    }

    private $getRusPostsFromTagSQL = "SELECT
										t.id,
                                        'Посты по тегу «' || t.tag || '»' AS tag,
										json_agg(p) AS posts
                                       FROM
                                         tags AS t
                                       LEFT JOIN 
                                         (SELECT
                                            id AS post_id,
                                            title,
                                            brief,
                                            date,
											type,
											(CASE
                                                  WHEN
                                                    type = 'Новость'
                                                  THEN
                                                    '/news/' || id
                                                  WHEN
                                                    p.type = 'Статья'
                                                  THEN
                                                    '/articles/' || id
                                                  WHEN
                                                    p.type = 'Мнение'
                                                  THEN
                                                    '/views/' || id
                                                END) AS href,
                                            pt.tag_id AS t_id,
											json_agg(t) AS tags
                                          FROM
                                            posts AS p
                                          LEFT JOIN
                                            (SELECT
                                                t.id AS tag_id,
                                                tag,
                                                post_id
                                              FROM
                                                tags AS t
                                              LEFT JOIN
                                                post_tag AS pt
                                                ON
                                                  pt.tag_id = t.id
                                              WHERE
                                                is_active) AS t
                                            ON
                                              t.post_id = p.id
                                          LEFT JOIN
                                            post_tag AS pt
                                            ON
                                              pt.post_id = p.id
                                                AND 
                                              pt.tag_id = :tag_id
                                           WHERE
                                             p.is_active
                                               AND
                                             pt.tag_id IS NOT NULL
										   GROUP BY
											 p.id,
											 pt.tag_id
										   ORDER BY
										     p.id DESC
										   LIMIT
										     :limit
										   OFFSET
										     :offset) AS p
										 ON
										   t.id = p.t_id
									   WHERE
									     t.id = :tag_id
									   GROUP BY
										 t.id,
                                         t.tag,
                                         p.type";

    public function getRusPostsFromTag (array & $data)
    {
        $data['offset'] = ($data['offset'] - 1) * $data['limit'];

        $result = $this->getRusPostsFromTagSQL($data);
        if (is_array($result))
        {
            $result[0]['posts'] = $result[0]['posts'] != '[null]' ? json_decode($result[0]['posts'], true) : null;
        }
        return $result;
    }

    private $getEngPostsFromTag = "SELECT
                                     p.id,
                                     p.eng_title,
                                     p.eng_brief,
                                     p.date
                                   FROM
                                     posts AS p
                                   LEFT JOIN
                                     post_tag AS pt
                                     ON
                                       pt.post_id = p.id
                                   WHERE
                                     p.type = :type
                                       AND
                                     p.is_active = true
                                       AND
                                     pt.tag_id = :tag_id
                                   ORDER BY
                                     p.id DESC
                                   LIMIT
                                     :limit
                                   OFFSET
                                     :offset";

    private $getRusPostSQL = "SELECT
                             p.id,
                             brief,
                             title,
                            (CASE
                               WHEN
                                 type = 'Новость'
                               THEN
                                 'Новости'
                               WHEN
                                 type = 'Статья'
                               THEN
                                 'Статьи'
                               WHEN
                                 type = 'Мнение'
                               THEN
                                 'Мнения'
                             END) AS type,
                             p.text,
                             date,
                             json_agg(t) AS tags,
                             c.comment_count
                           FROM
                             posts AS p
                           LEFT JOIN
                             post_tag AS pt
                             ON
                             pt.post_id = p.id
                           LEFT JOIN LATERAL
                             (SELECT
                                 id AS tmp_id,
                                (CASE
                                   WHEN
                                     type = 'Новость'
                                   THEN
                                     '/news/tag/' || id
                                   WHEN
                                     type = 'Статья'
                                   THEN
                                     '/articles/tag/' || id
                                   WHEN
                                     type = 'Мнение'
                                   THEN
                                     '/views/tag/' || id
                                 END) AS tag_id,
                                tag
                              FROM
                                tags) AS t
                             ON
                               pt.tag_id = t.tmp_id
                           CROSS JOIN
                             (SELECT
                                count(*) AS comment_count
                              FROM
                                comments AS c
                              WHERE
                                post_id = :id) AS c
                           WHERE
                             p.id = :id
                               AND
                             type = :type
                               AND
                             p.is_active = true
                           GROUP BY
                             p.id,
                             title,
                             type,
                             p.text,
                             date,
                             c.comment_count";

    public function getRusPost (array & $data)
    {
        $result = $this->getRusPostSQL($data);
        if (is_array($result))
        {
            $result[0]['tags'] = $result[0]['tags'] != '[null]' ? json_decode($result[0]['tags'], true) : null;
            /*if ($_SESSION['User']['level'] == LEVELS[1])
            {
                $result[0]['send'] = 'no_display';
            }*/
        }

        return $result;
    }

    private $getAdminPostSQL = "SELECT
                                 p.id,
                                 title,
                                 brief,
                                 text,
                                 date,
                                 eng_title,
                                 eng_brief,
                                 eng_text,
                                 json_agg(t) AS tags,
                                 is_active::text
                               FROM
                                 posts AS p
                               CROSS JOIN
                                 (SELECT
                                    id,
                                    tag,
                                   (CASE
                                      WHEN
                                        pt.tag_id IS NOT NULL
                                      THEN
                                        'selected'
                                    END) AS status
                                  FROM
                                    tags AS t
                                  LEFT JOIN
                                    post_tag AS pt
                                    ON
                                      pt.tag_id = t.id
                                        AND
                                      pt.post_id = :id
                                   WHERE
                                     is_active
                                   ORDER BY id) AS t
                               WHERE
                                 p.id = :id
                               GROUP BY
                                 p.id,
                                 title,
                                 brief,
                                 text,
                                 date";

    private $getAdminPostType = "SELECT
                                 json_agg(t) AS types
                               FROM
                                 posts AS p
                               CROSS JOIN
                                (SELECT
                                   pg_enum.enumlabel AS posttype,
                                   (CASE
                                      WHEN
                                        p.id IS NOT NULL
                                      THEN
                                        'selected'
                                    END) AS status
                                 FROM
                                   pg_type
                                 JOIN
                                   pg_enum
                                   ON
                                     pg_enum.enumtypid = pg_type.oid
                                LEFT JOIN
                                  posts AS p
                                  ON
                                    p.type::text = pg_enum.enumlabel
                                      AND
                                    p.id = :id
                                WHERE
                                  pg_type.typname = 'posttype') AS t
                               WHERE
                                 p.id = :id";

    public function getAdminPost (array & $data)
    {
        $result = $this->getAdminPostSQL($data);
        if (is_array($result))
        {
            if ($result[0]['tags'])
            {
                $result[0]['tags'] = json_decode($result[0]['tags'], true);
            }
        }
        else
        {
            throw new PostException('Не удалось получить данные о посте');
        }
        $result2 = $this->getAdminPostType($data);
        if (is_array($result2))
        {
            if ($result2[0]['types'])
            {
                $result[0]['types'] = json_decode($result2[0]['types'], true);
            }
            return $result;
        }
        else
        {
            throw new PostException('Не удалось получить данные о типе поста');
        }
    }

    private $getEngPost = "SELECT
                             p.id,
                             eng_title,
                             eng_brief,
                             eng_text,
                             date,
                             json_agg(t) AS tags
                           FROM
                             posts AS p
                           LEFT JOIN
                             post_tag AS pt
                             ON
                             pt.post_id = p.id
                           LEFT JOIN
                             tags AS t
                             ON
                               pt.tag_id = t.id
                           WHERE
                             p.id = :id";

    private $activatePostSQL = "UPDATE
                                  posts
                                SET
                                  is_active = true
                                WHERE
                                  id = :id
                                RETURNING
                                  id,
                                  type";

    private $deactivatePostSQL = "UPDATE
                                      posts
                                    SET
                                      is_active = false
                                    WHERE
                                      id = :id
                                    RETURNING
                                      id,
                                      type";

    public function activatePost (array & $data)
    {
        $result = $this->activatePostSQL($data);
        if (is_array($result))
        {
            $rss = new RSS();
            $rss->createAllRSS();
            $rss->switchType($result[0]['type']);
            $rss->createRSSFromLiteralType($result[0]['type']);

            Service::buildSitemap();

            return $result;
        }
        else
        {
            throw new PostException('Не удалось активировать пост');
        }
    }

    public function deactivatePost (array & $data)
    {
        $result = $this->deactivatePostSQL($data);
        if (is_array($result))
        {
            $rss = new RSS();
            $rss->createAllRSS();
            $rss->switchType($result[0]['type']);
            $rss->createRSSFromLiteralType($result[0]['type']);

            Service::buildSitemap();

            return $result;
        }
        else
        {
            throw new PostException('Не удалось скрыть пост');
        }
    }

    private $getAdminPageCount = "SELECT
                                    ceil(count(*)::real/20::real) AS page_count
                                  FROM
                                    posts";

    private $getPageCount = "SELECT
                               max(p.page_count) AS page_count
                             FROM
                              (SELECT
                                ceil(max(count(*)::real / 7::real) OVER(PARTITION BY type)) AS page_count
                              FROM
                                posts
                              WHERE
                                is_active = true
                                  AND
                                type IN ('Новость', 'Статья')
                              GROUP BY
                                type) AS p";

    private $getPageCountByType = "SELECT
                                    ceil(count(id)::real/7::real) AS page_count
                                  FROM
                                    posts
                                  WHERE
                                    type = :type";

    private $getPostCountByType = "SELECT
                                    count(id) AS count
                                  FROM
                                    posts
                                  WHERE
                                    type IN ('Новость', 'Статья')
                                  GROUP BY 
                                    type
                                   ORDER BY
                                     type";

    private $getPostTypes = "SELECT
                               pg_enum.enumlabel AS posttype
                             FROM
                               pg_type
                             JOIN
                               pg_enum
                               ON
                                 pg_enum.enumtypid = pg_type.oid
                            WHERE
                              pg_type.typname = 'posttype'";

    private $getPostsToRSS = "SELECT
                               (CASE
                                  WHEN
                                    type = 'Новость'
                                  THEN
                                    CONCAT('" . RSS_DOMAIN . "/news/', id)
                                  WHEN
                                    type = 'Статья'
                                  THEN
                                    CONCAT('" . RSS_DOMAIN . "/articles/', id)
                                  WHEN
                                    type = 'Мнение'
                                  THEN
                                    CONCAT('" . RSS_DOMAIN . "/views/', id)
                                END) AS url,
                                date
                              FROM
                                posts
                              WHERE
                                is_active = true";

} 