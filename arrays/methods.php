<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 26.05.14
 * Time: 14:08
 */

$methods = [
    'Test::test' =>
        [
            'level' => 0,
            'session' =>
            [
                'parent' => 'User',
                'data' => array('id', 'name', 'level')
            ],
            'cookie' =>
            [
                'parent' => 'User',
                'data' => array('id', 'name', 'level'),
                'expire' => 3600
            ],
            'expire' => 120,
            'additional_params' =>
                [
                    'developer_id' => isset($_SESSION['User']['id']) ? $_SESSION['User']['id'] : 0,
                    'last_update_user_id' => isset($_SESSION['User']['id']) ? $_SESSION['User']['id'] : 0
                ]
        ],

    'User::auth' =>
        [
            'level' => [LEVELS[1]],
            'session' =>
                [
                    'parent' => 'User',
                    'data' => array('id', 'login', 'level', 'email')
                ]
        ],

    'User::saveUser' =>
        [
            'level' => [LEVELS[1]]
        ],

    'User::saveUserWithInterests' =>
        [
            'level' => [LEVELS[1]]
        ],

    'User::register' =>
        [
            'level' => [LEVELS[1]],
            'additional_params' =>
                [
                    'last_update_user_id' => 0
                ],
            'session' =>
                [
                    'parent' => 'User',
                    'data' => array('id', 'login', 'level', 'email')
                ]
        ],

    'User::getInterests' =>
        [
            'level' => [LEVELS[1]],
            'expire' => 300
        ],

    'User::checkUser' =>
        [
            'level' => [LEVELS[1]],
            'expire' => 300
        ],

    'User::logout' =>
        [
            'level' => [LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]]
        ],

    'User::getUserByEmail' =>
        [
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]]
        ],

    'User::confirmationEmail' =>
        [
            'level' => [LEVELS[2]]
        ],

    'User::getAdminPageCount' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'User::getUserListSQL' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'User::getUserList' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'User::deactivateUser' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]]
        ],

    'User::activateUser' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]]
        ],

    'Post::newPostSQL' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
        ],

    'Post::updatePostSQL' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
        ],

    'Post::newPost' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'additional_params' =>
                [
                    'last_update_user_id' => isset($_SESSION['User']['id']) ? $_SESSION['User']['id'] : 0
                ],
            'pages' => [md5('index'), md5('news'), md5('articles'), md5('views')]
        ],

    'Post::updatePost' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'additional_params' =>
                [
                    'last_update_user_id' => isset($_SESSION['User']['id']) ? $_SESSION['User']['id'] : 0
                ],
            'pages' => [md5('index'), md5('news'), md5('articles'), md5('views')]
        ],

    'Post::delPost' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'pages' => [md5('index'), md5('news'), md5('articles'), md5('views')]
        ],

    'Post::getAdminPostSQL' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::getAdminPostType' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::getAdminPost' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::getAdminPageCount' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::getPageCount' =>
        [
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::getPageCountByType' =>
        [
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::getPostCountByType' =>
        [
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::getAdminPostListSQL' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::getAdminPostList' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::deactivatePost' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'pages' => [md5('index'), md5('news'), md5('articles'), md5('views')]
        ],

    'Post::activatePost' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'pages' => [md5('index'), md5('news'), md5('articles'), md5('views')]
        ],

    'Post::getPostTypes' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::clearPostTags' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]]
        ],

    'Post::getRusPostListSQL' =>
        [
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::getRusPostList' =>
        [
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::getRusPostsFromTagSQL' =>
        [
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::getRusPostsFromTag' =>
        [
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::getRusPostSQL' =>
        [
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::getRusPost' =>
        [
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::getRSSPosts' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::getRSSNews' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::getRSSArticles' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Post::getRSSViews' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Project::getAdminProject' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Project::getAdminProjectList' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Project::deactivateProject' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'pages' => [md5('projects')]
        ],

    'Project::activateProject' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'pages' => [md5('projects')]
        ],

    'Project::newProject' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'additional_params' =>
                [
                    'last_update_user_id' => isset($_SESSION['User']['id']) ? $_SESSION['User']['id'] : 0
                ],
            'pages' => [md5('projects')]
        ],

    'Project::updateProject' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'additional_params' =>
                [
                    'last_update_user_id' => isset($_SESSION['User']['id']) ? $_SESSION['User']['id'] : 0
                ],
            'pages' => [md5('projects')]
        ],

    'Project::getBannerRusProjectList' =>
        [
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Project::getRusProjectList' =>
        [
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Project::getRusProject' =>
        [
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Project::getAdminPageCount' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Project::getPageCount' =>
        [
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Tag::getTags' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Tag::getTagsFromType' =>
        [
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Tag::getAdminTags' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Tag::deactivateTag' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]]
        ],

    'Tag::activateTag' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]]
        ],

    'Tag::newTag' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]]
        ],

    'Tag::updateTag' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]]
        ],

    'Tag::getPostsTags' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]]
        ],

    'Comment::getAdminCommentsSQL' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Comment::getAdminComments' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Comment::getAdminPageCount' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Comment::deactivateComment' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]]
        ],

    'Comment::activateComment' =>
        [
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]]
        ],

    'Comment::getPostComments' =>
        [
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 300
        ],

    'Comment::newComment' =>
        [
            'level' => [LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'additional_params' =>
                [
                    'last_update_user_id' => isset($_SESSION['User']['id']) ? $_SESSION['User']['id'] : 0,
                    'user_id' => isset($_SESSION['User']['id']) ? $_SESSION['User']['id'] : 0
                ]
        ],

    'Config::editConfig' =>
        [
            'level' => [LEVELS[5]]
        ],

    'Config::getConfigToEditor' =>
        [
            'level' => [LEVELS[5]],
            'expire' => 300
        ],

    'Config::getULoginConfig' =>
        [
            'level' => [LEVELS[1]],
            'expire' => 300
        ]
];

?>