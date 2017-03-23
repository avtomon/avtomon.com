<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 30.04.14
 * Time: 13:21
 */

$pages = [
    'admin' =>
        [
            'file' => ADMIN_PATH . '/index.html',
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 120
        ],

    'admin/index' =>
        [
            'file' => ADMIN_PATH . '/index.html',
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 120
        ],

    'register' =>
        [
            'file' => PUBLIC_PATH . '/register.html',
            'level' => [LEVELS[1]],
            'expire' => 120
        ],

    'confirm_email\/(.+)' =>
        [
            'file' => PUBLIC_PATH . '/confirm_email.html',
            'level' => [LEVELS[2]],
            'expire' => 120,
            'methods' =>
                [
                    'class' => 'User',
                    'method' => 'confirmationEmail',
                    'method_params' =>
                        [
                            'email_hash' => '$0'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => '.parent'
                ]
        ],

    'admin/posts' =>
        [
            'file' => ADMIN_PATH . '/posts.html',
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'methods' =>
                [
                    'class' => 'Post',
                    'method' => 'getAdminPostList',
                    'method_params' =>
                        [
                            'limit' => ADMIN_TABLE_ROW_COUNT,
                            'offset' => 1
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => '.parent.clone'
                ]
        ],

    'admin\/posts\/page\/(\d{1,3})' =>
        [
            'file' => ADMIN_PATH . '/posts.html',
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'methods' =>
                [
                    'class' => 'Post',
                    'method' => 'getAdminPostList',
                    'method_params' =>
                        [
                            'limit' => ADMIN_TABLE_ROW_COUNT,
                            'offset' => '$0'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => '.parent.clone'
                ],
            'redirect' => '/error/404'
        ],

    'admin/tags' =>
        [
            'file' => ADMIN_PATH . '/tags.html',
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'methods' =>
                [
                    'class' => 'Tag',
                    'method' => 'getAdminTags',
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => '.parent.clone'
                ]
        ],

    'admin/comments' =>
        [
            'file' => ADMIN_PATH . '/comments.html',
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'methods' =>
                [
                    'class' => 'Comment',
                    'method' => 'getAdminCommentsSQL',
                    'method_params' =>
                        [
                            'limit' => ADMIN_TABLE_ROW_COUNT,
                            'offset' => 1
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => '.parent.clone'
                ]
        ],

    'admin\/comments\/page\/(\d{1,3})' =>
        [
            'file' => ADMIN_PATH . '/comments.html',
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'methods' =>
                [
                    'class' => 'Comment',
                    'method' => 'getAdminComments',
                    'method_params' =>
                        [
                            'limit' => ADMIN_TABLE_ROW_COUNT,
                            'offset' => '$0'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => '.parent.clone'
                ],
            'redirect' => '/error/404'
        ],

    'admin/users' =>
        [
            'file' => ADMIN_PATH . '/users.html',
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'methods' =>
                [
                    'class' => 'User',
                    'method' => 'getUserListSQL',
                    'method_params' =>
                        [
                            'limit' => ADMIN_TABLE_ROW_COUNT,
                            'offset' => 0
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => '.parent.clone'
                ]
        ],

    'admin\/users\/page\/(\d{1,3})' =>
        [
            'file' => ADMIN_PATH . '/users.html',
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'methods' =>
                [
                    'class' => 'User',
                    'method' => 'getUserList',
                    'method_params' =>
                        [
                            'limit' => ADMIN_TABLE_ROW_COUNT,
                            'offset' => '$0'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => '.parent.clone'
                ],
            'redirect' => '/error/404'
        ],

    'admin\/posts\/(\d{1,5})' =>
        [
            'file' => ADMIN_PATH . '/edit_post.html',
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'methods' =>
                [
                    'class' => 'Post',
                    'method' => 'getAdminPost',
                    'method_params' =>
                    [
                        'id' => '$0'
                    ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => '.content.parent'
                ],
            'redirect' => '/error/404'
        ],

    'admin/posts/add' =>
        [
            'file' => ADMIN_PATH . '/new_post.html',
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 120
        ],


    'admin/projects' =>
        [
            'file' => ADMIN_PATH . '/projects.html',
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'methods' =>
                [
                    'class' => 'Project',
                    'method' => 'getAdminProjectList',
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => '.parent.clone'
                ]
        ],

    'admin\/projects\/(\d{1,5})' =>
        [
            'file' => ADMIN_PATH . '/edit_project.html',
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'methods' =>
                [
                    'class' => 'Project',
                    'method' => 'getAdminProject',
                    'method_params' =>
                        [
                            'id' => '$0'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => '.content.parent'
                ],
            'redirect' => '/error/404'
        ],

    'admin/projects/add' =>
        [
            'file' => ADMIN_PATH . '/new_project.html',
            'level' => [LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 120
        ],

    'admin/config' =>
        [
            'file' => ADMIN_PATH . '/config.html',
            'level' => [LEVELS[5]]
        ],

    'login' =>
        [
            'file' => PUBLIC_PATH . '/login.html',
            'level' => [LEVELS[1]],
            'expire' => 120,
            'methods' =>
                [
                    [
                        'class' => 'Config',
                        'method' => 'getULoginConfig',
                        'get_instance' => true,
                        'static_method' => false,
                        'parent' => '.ulogin.parent'
                    ]
                ]
        ],

    'index' =>
        [
            'file' => PUBLIC_PATH . '/index.html',
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 120,
            'methods' =>
                [
                    [
                        'class' => 'Post',
                        'method' => 'getRusPostList',
                        'method_params' =>
                            [
                                'limit' => USER_POSTLIST_COUNT,
                                'offset' => 1,
                                'type' => 'Новость'
                            ],
                        'get_instance' => false,
                        'static_method' => false,
                        'parent' => '.news.parent.clone'
                    ],
                    [
                        'class' => 'Post',
                        'method' => 'getRusPostList',
                        'method_params' =>
                            [
                                'limit' => USER_POSTLIST_COUNT,
                                'offset' => 1,
                                'type' => 'Статья'
                            ],
                        'get_instance' => false,
                        'static_method' => false,
                        'parent' => '.article.parent.clone'
                    ]
                ]
        ],

    'news' =>
        [
            'file' => PUBLIC_PATH . '/news.html',
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 120,
            'methods' =>
                [
                    'class' => 'Post',
                    'method' => 'getRusPostList',
                    'method_params' =>
                        [
                            'limit' => USER_POSTLIST_COUNT,
                            'offset' => 1,
                            'type' => 'Новость'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => '.content3baseblock.parent.clone'
                ]
        ],

    'news\/page\/(\d{1,3})' =>
        [
            'file' => PUBLIC_PATH . '/news.html',
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'methods' =>
                [
                    'class' => 'Post',
                    'method' => 'getRusPostList',
                    'method_params' =>
                        [
                            'limit' => USER_POSTLIST_COUNT,
                            'offset' => '$0',
                            'type' => 'Новость'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => '.content3baseblock.parent.clone'
                ],
            'redirect' => '/error/404'
        ],

    'news\/(\d{1,3})' =>
        [
            'file' => PUBLIC_PATH . '/post.html',
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 120,
            'methods' =>
                [
                    'class' => 'Post',
                    'method' => 'getRusPost',
                    'method_params' =>
                        [
                            'id' => '$0',
                            'type' => 'Новость'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => 'html'
                ],
            'redirect' => '/error/404'
        ],

    'tag\/(\d{1,3})' =>
        [
            'file' => PUBLIC_PATH . '/posts_from_tags.html',
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'methods' =>
                [
                    'class' => 'Post',
                    'method' => 'getRusPostsFromTag',
                    'method_params' =>
                        [
                            'limit' => USER_POSTLIST_COUNT,
                            'offset' => 1,
                            'tag_id' => '$0'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => 'html'
                ],
            'redirect' => '/error/404'
        ],

    'tag\/(\d{1,3})\/page\/(\d{1,3})' =>
        [
            'file' => PUBLIC_PATH . '/posts_from_tags.html',
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'methods' =>
                [
                    'class' => 'Post',
                    'method' => 'getRusPostsFromTag',
                    'method_params' =>
                        [
                            'limit' => USER_POSTLIST_COUNT,
                            'offset' => '$1',
                            'tag_id' => '$0'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => 'html'
                ],
            'redirect' => '/error/404'
        ],

    'articles' =>
        [
            'file' => PUBLIC_PATH . '/articles.html',
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 120,
            'methods' =>
                [
                    'class' => 'Post',
                    'method' => 'getRusPostList',
                    'method_params' =>
                        [
                            'limit' => USER_POSTLIST_COUNT,
                            'offset' => 1,
                            'type' => 'Статья'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => '.content3baseblock.parent.clone'
                ]
        ],

    'articles\/(\d{1,3})' =>
        [
            'file' => PUBLIC_PATH . '/post.html',
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 120,
            'methods' =>
                [
                    'class' => 'Post',
                    'method' => 'getRusPost',
                    'method_params' =>
                        [
                            'id' => '$0',
                            'type' => 'Статья'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => 'html'
                ],
            'redirect' => '/error/404'
        ],

    'articles\/page\/(\d{1,3})' =>
        [
            'file' => PUBLIC_PATH . '/articles.html',
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'methods' =>
                [
                    'class' => 'Post',
                    'method' => 'getRusPostList',
                    'method_params' =>
                        [
                            'limit' => USER_POSTLIST_COUNT,
                            'offset' => '$0',
                            'type' => 'Статья'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => '.content3baseblock.parent.clone'
                ],
            'redirect' => '/error/404'
        ],

    'views' =>
        [
            'file' => PUBLIC_PATH . '/views.html',
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 120,
            'methods' =>
                [
                    'class' => 'Post',
                    'method' => 'getRusPostList',
                    'method_params' =>
                        [
                            'limit' => USER_POSTLIST_COUNT,
                            'offset' => 1,
                            'type' => 'Мнение'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => '.content3baseblock.parent.clone'
                ]
        ],

    'views\/(\d{1,3})' =>
        [
            'file' => PUBLIC_PATH . '/post.html',
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 120,
            'methods' =>
                [
                    'class' => 'Post',
                    'method' => 'getRusPost',
                    'method_params' =>
                        [
                            'id' => '$0',
                            'type' => 'Мнение'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => 'html'
                ],
            'redirect' => '/error/404'
        ],

    'views\/page\/(\d{1,3})' =>
        [
            'file' => PUBLIC_PATH . '/views.html',
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'methods' =>
                [
                    'class' => 'Post',
                    'method' => 'getRusPostList',
                    'method_params' =>
                        [
                            'limit' => USER_POSTLIST_COUNT,
                            'offset' => '$0',
                            'type' => 'Мнение'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => '.content3baseblock.parent.clone'
                ],
            'redirect' => '/error/404'
        ],

    'projects' =>
        [
            'file' => PUBLIC_PATH . '/projects.html',
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 120,
            'methods' =>
                [
                    'class' => 'Project',
                    'method' => 'getRusProjectList',
                    'method_params' =>
                        [
                            'limit' => USER_POSTLIST_COUNT,
                            'offset' => 1
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => 'table.parent.clone'
                ]
        ],

    'projects\/(\d{1,3})' =>
        [
            'file' => PUBLIC_PATH . '/project.html',
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'expire' => 120,
            'methods' =>
                [
                    'class' => 'Project',
                    'method' => 'getRusProject',
                    'method_params' =>
                        [
                            'id' => '$0'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => 'html'
                ],
            'redirect' => '/error/404'
        ],

    'projects\/page\/(\d{1,3})' =>
        [
            'file' => PUBLIC_PATH . '/projects.html',
            'level' => [LEVELS[1], LEVELS[2], LEVELS[3], LEVELS[4], LEVELS[5]],
            'methods' =>
                [
                    'class' => 'Project',
                    'method' => 'getRusProjectList',
                    'method_params' =>
                        [
                            'limit' => USER_POSTLIST_COUNT,
                            'offset' => '$0'
                        ],
                    'get_instance' => false,
                    'static_method' => false,
                    'parent' => 'table.parent.clone'
                ],
            'redirect' => '/error/404'
        ]

];