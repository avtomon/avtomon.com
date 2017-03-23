<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 08.08.14
 * Time: 18:01
 */

class TagException extends Exception { }

class Tag extends Superclass
{
    use Classinfo;

    private static $db = false;

    private $getTags = "SELECT
                          *
                        FROM
                          tags
                        WHERE
                          is_active = true";

    private $getAdminTags = "SELECT
                              t.id,
                              t.tag,
                              t.is_active::text,
                              t.in_interests::text,
                              COUNT(pt.*) AS post_count
                            FROM
                              tags AS t
                            LEFT JOIN
                              post_tag AS pt
                              ON
                                t.id = pt.tag_id
                            GROUP BY
                              t.id,
                              t.tag,
                              t.is_active";

    private $newTag =  "INSERT INTO
                             tags
                             ([fields])
                            VALUES
                              [expression]
                            RETURNING
                              *";

    private $updateTag = "UPDATE
                           tags
                         SET
                           tag = :tag
                         WHERE
                           id = :id
                         RETURNING
                            *";

    private $delTag = "DELETE FROM
                           tag
                        WHERE
                          id = :id
                        RETURNING
                          id";

    private $activateTag = "UPDATE
                                  tags
                                SET
                                  is_active = true
                                WHERE
                                  id = :id
                                RETURNING
                                  id";

    private $deactivateTag       = "UPDATE
                                      tags
                                    SET
                                      is_active = false
                                    WHERE
                                      id = :id
                                    RETURNING
                                      id";

    private $getTagsFromType = "SELECT DISTINCT
                                  t.id,
                                  t.tag
                                FROM
                                  tags AS t
                                LEFT JOIN
                                  post_tag AS pt
                                  ON
                                    pt.tag_id = t.id
								LEFT JOIN
                                  posts AS p
								  ON
									p.id = pt.post_id
                                WHERE
                                  pt.tag_id IS NOT NULL
									AND
								  p.type = :type
									AND
								  p.is_active = true
									AND
								  t.is_active = true";

    private $getTagsToRSS = "SELECT DISTINCT
                                 (CASE
                                    WHEN
                                      p.type = 'Новость'
                                    THEN
                                      CONCAT('" . RSS_DOMAIN . "/news/tag/', t.id)
                                    WHEN
                                      p.type = 'Статья'
                                    THEN
                                      CONCAT('" . RSS_DOMAIN . "/articles/tag/', t.id)
                                    WHEN
                                      p.type = 'Мнение'
                                    THEN
                                      CONCAT('" . RSS_DOMAIN . "/views/tag/', t.id)
                                  END) AS url
                                FROM
                                  tags AS t
                                LEFT JOIN
                                  post_tag AS pt
                                  ON
                                    pt.tag_id = t.id
								LEFT JOIN
                                  posts AS p
								  ON
									p.id = pt.post_id
                                WHERE
                                  pt.tag_id IS NOT NULL
									AND
								  p.is_active = true
									AND
								  t.is_active = true";

    private $getPostsTags = "SELECT
                                  t.tag
                                FROM
                                  tags AS t
                                LEFT JOIN
                                  post_tag AS pt
                                  ON
                                    pt.tag_id = t.id
								LEFT JOIN
                                  posts AS p
								  ON
									p.id = pt.post_id
                                WHERE
                                  pt.tag_id IS NOT NULL
									AND
								  p.is_active = true
									AND
								  t.is_active = true";

} 