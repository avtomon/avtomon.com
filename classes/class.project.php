<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 08.08.14
 * Time: 16:52
 */

class ProjectException extends Exception { }

class Project extends Superclass
{
    use Classinfo;

    private static $db = false;

    private $getRusProjectList = "SELECT
                                    id,
                                    title,
                                    demo,
                                    download,
                                    documentation,
                                    brief
                                  FROM
                                    projects
                                  WHERE
                                    is_active = true
                                  ORDER BY
                                    id
                                  LIMIT
                                    :limit
                                  OFFSET
                                    :offset";

    private $getBannerRusProjectList = "SELECT
                                            id,
                                            title,
                                            demo,
                                            download,
                                            documentation,
                                            brief
                                          FROM
                                            projects
                                          WHERE
                                            is_active = true
                                          ORDER BY
                                            id
                                          LIMIT
                                            7";

    private $getAdminProjectList = "SELECT
                                    id,
                                    title,
                                    is_active::text
                                  FROM
                                    projects
                                  ORDER BY
                                    id DESC";

    private $getEngProjectList = "SELECT
                                    id,
                                    eng_title,
                                    demo,
                                    download,
                                    eng_documentation,
                                    eng_description,
                                    applied,
                                  FROM
                                    projects
                                  WHERE
                                    is_active = true
                                  ORDER BY
                                    id
                                  LIMIT
                                    :limit
                                  OFFSET
                                    :offset";

    private $getEngProject =     "SELECT
                                    id,
                                    eng_title,
                                    demo,
                                    download,
                                    eng_documentation,
                                    eng_description,
                                    applied
                                  FROM
                                    projects
                                  WHERE
                                    is_active = true
                                      AND
                                    id = :id";

    private $getRusProject =     "SELECT
                                    id,
                                    title,
                                    demo,
                                    download,
                                    documentation,
                                    description
                                  FROM
                                    projects
                                  WHERE
                                    is_active = true
                                      AND
                                    id = :id";

    private $getAdminProject =     "SELECT
                                    id,
                                    title,
                                    demo,
                                    download,
                                    documentation,
                                    description,
                                    current_version,
                                    applied,
                                    eng_title,
                                    eng_documentation,
                                    eng_description,
                                    is_active::text
                                  FROM
                                    projects
                                  WHERE
                                    id = :id";

    private $newProject =  "INSERT INTO
                             projects
                             ([fields])
                            VALUES
                              [expression]
                            RETURNING
                              id";

    private $updateProject = "UPDATE
                               projects
                             SET
                               [expression]
                             WHERE
                               id = :id
                             RETURNING
                                id";

    private $delProject = "DELETE FROM
                               projects
                            WHERE
                              id = :id
                            RETURNING
                              id";

    private $activateProject = "UPDATE
                                  projects
                                SET
                                  is_active = true
                                WHERE
                                  id = :id
                                RETURNING
                                  id";

    private $deactivateProject = "UPDATE
                                      projects
                                    SET
                                      is_active = false
                                    WHERE
                                      id = :id
                                    RETURNING
                                      id";

    private $getAdminPageCount = "SELECT
                                    ceil(count(*)::real/20::real) AS page_count
                                  FROM
                                    projects";

    private $getPageCount = "SELECT
                                    ceil(count(*)::real/7::real) AS page_count
                                  FROM
                                    projects
                                  WHERE
                                    is_active = true";

    private $getProjectsToRSS = "SELECT
                                   CONCAT('" . RSS_DOMAIN . "/projects/', id) AS url,
                                   floor(extract(epoch FROM now())) AS date
                                  FROM
                                    projects
                                  WHERE
                                    is_active = true";

}