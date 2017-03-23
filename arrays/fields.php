<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 14.04.14
 * Time: 12:32
 */

$fields = [
    'Test::test' =>
    [
        'new_job_info_speciality_id' =>
        [
            'type' => 'integer',
            'from' => 1,
            'is_required' => true
        ],
        'socworker_id' =>
        [
            'type' => 'integer',
            'from' => 1,
            'is_required' => true
        ]
    ],

    'User::register' =>
    [
        'login' =>
        [
            'type' => 'string',
            'is_required' => true
        ],
        'hash' =>
            [
                'type' => 'string',
                'is_required' => true
            ],
        'avatar' =>
            [
                'type' => 'string',
                'is_required' => false
            ],
        'thumb' =>
            [
                'type' => 'string',
                'is_required' => false
            ],
        'is_active' =>
            [
                'type' => 'string',
                'regexp' => '/[01]{1}/i',
                'is_required' => false
            ],
        'socials' =>
            [
                'type' => 'string',
                'is_required' => false
            ],
        'email' =>
            [
                'type' => 'string',
                'is_required' => true
            ],
        'level' =>
            [
                'type' => 'string',
                'is_required' => false
            ],
        'last_update_user_id' =>
            [
                'type' => 'integer',
                'from' => 0,
                'is_required' => true
            ],
        'interests' =>
            [
                'type' => 'array',
                'is_required' => false
            ],
    ],

    'User::saveUserWithInterests' =>
        [
            'login' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'hash' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'avatar' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'thumb' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'is_active' =>
                [
                    'type' => 'string',
                    'regexp' => '/[01]{1}/i',
                    'is_required' => false
                ],
            'socials' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'email' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'level' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'last_update_user_id' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ],
            'interests' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
        ],

    'User::saveUser' =>
        [
            'login' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'hash' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'avatar' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'thumb' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'is_active' =>
                [
                    'type' => 'string',
                    'regexp' => '/[01]{1}/i',
                    'is_required' => false
                ],
            'socials' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'email' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'level' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'last_update_user_id' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ]
        ],

    'User::checkUser' =>
        [
            'login' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'email' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ]
        ],

    'User::confirmationEmail' =>
        [
            'email_hash' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ]
        ],

    'User::getUserListSQL' =>
        [
            'limit' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ],
            'offset' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ]
        ],

    'User::getUserList' =>
        [
            'limit' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ],
            'offset' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ]
        ],

    'User::deactivateUser' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'User::activateUser' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'User::getUserByEmail' =>
        [
            'email' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ]
        ],

    'Post::newPostSQL' =>
        [
            'type' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'title' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'brief' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'text' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'is_active' =>
                [
                    'type' => 'string',
                    'regexp' => '/[01]{1}/i',
                    'is_required' => false
                ],
            'eng_title' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'eng_brief' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'eng_text' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'last_update_user_id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ],
            'tags' =>
                [
                    'type' => 'string|array',
                    'is_required' => true
                ]
        ],

    'Post::updatePostSQL' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ],
            'type' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'title' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'brief' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'text' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'is_active' =>
                [
                    'type' => 'string',
                    'regexp' => '/[01]{1}/i',
                    'is_required' => false
                ],
            'eng_title' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'eng_brief' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'eng_text' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'last_update_datetime' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ],
            'last_update_user_id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ],
            'tags' =>
                [
                    'type' => 'string|array',
                    'is_required' => false
                ]
        ],

    'Post::newPost' =>
        [
            'type' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'title' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'brief' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'text' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'is_active' =>
                [
                    'type' => 'string',
                    'regexp' => '/[01]{1}/i',
                    'is_required' => false
                ],
            'eng_title' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'eng_brief' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'eng_text' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'last_update_user_id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ],
            'tags' =>
                [
                    'type' => 'array|string',
                    'is_required' => false
                ]
        ],

    'Post::updatePost' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ],
            'type' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'title' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'brief' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'text' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'is_active' =>
                [
                    'type' => 'string',
                    'regexp' => '/[01]{1}/i',
                    'is_required' => false
                ],
            'eng_title' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'eng_brief' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'eng_text' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'last_update_datetime' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ],
            'last_update_user_id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ],
            'tags' =>
                [
                    'type' => 'array|string',
                    'is_required' => false
                ]
        ],

    'Post::delPost' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Post::getAdminPostSQL' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Post::getAdminPostType' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Post::getAdminPostListSQL' =>
        [
            'limit' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ],
            'offset' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ]
        ],

    'Post::getAdminPostList' =>
        [
            'limit' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ],
            'offset' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ]
        ],

    'Post::getAdminPost' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Post::deactivatePost' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Post::activatePost' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Post::clearPostTags' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Post::getRusPostListSQL' =>
        [
            'limit' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ],
            'offset' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ],
            'type' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ]
        ],

    'Post::getRusPostList' =>
        [
            'limit' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ],
            'offset' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ],
            'type' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ]
        ],

    'Post::getPageCountByType' =>
        [
            'type' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ]
        ],

    'Post::getRusPostsFromTagSQL' =>
        [
            'limit' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ],
            'offset' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ],
            'tag_id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Post::getRusPostsFromTag' =>
        [
            'limit' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ],
            'offset' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ],
            'tag_id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Post::getRusPostSQL' =>
        [
            'type' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Post::getRusPost' =>
        [
            'type' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Project::getAdminProject' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Project::deactivateProject' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Project::activateProject' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Project::newProject' =>
        [
            'title' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'demo' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'download' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'documentation' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'description' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'current_version' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'applied' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'last_update_user_id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ],
            'eng_title' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'eng_documentation' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'eng_description' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ]
        ],

    'Project::updateProject' =>
        [
            'id' =>
                [
                    'type' => 'integet',
                    'from' => 1,
                    'is_required' => true
                ],
            'title' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'demo' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'download' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'documentation' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'description' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'current_version' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'applied' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'last_update_user_id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ],
            'last_update_datetime' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ],
            'eng_title' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'eng_documentation' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'eng_description' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ]
        ],

    'Project::getRusProjectList' =>
        [
            'limit' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ],
            'offset' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ]
        ],
    'Project::getRusProject' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Comment::getAdminCommentsSQL' =>
        [
            'limit' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ],
            'offset' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ]
        ],

    'Comment::getAdminComments' =>
        [
            'limit' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ],
            'offset' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ]
        ],

    'Comment::deactivateComment' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Comment::activateComment' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Comment::getPostComments' =>
        [
            'post_id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Comment::newComment' =>
        [
            'post_id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ],
            'text' =>
                [
                    'type' => 'string',
                    'is_required' => true,
                    'is_sanitize' => true
                ],
            'user_id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ],
            'from_id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => false
                ],
            'last_update_user_id' =>
                [
                    'type' => 'integer',
                    'from' => 0,
                    'is_required' => true
                ]
        ],

    'Tag::deactivateTag' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Tag::activateTag' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ]
        ],

    'Tag::newTag' =>
        [
            'tag' =>
                [
                    'type' => 'string',
                    'is_required' => true
                ],
            'in_interests' =>
                [
                    'type' => 'string',
                    'regexp' => '/[01]{1}/i',
                    'is_required' => false
                ]
        ],

    'Tag::updateTag' =>
        [
            'id' =>
                [
                    'type' => 'integer',
                    'from' => 1,
                    'is_required' => true
                ],
            'tag' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'in_interests' =>
                [
                    'type' => 'string',
                    'regexp' => '/[01]{1}/i',
                    'is_required' => false
                ]
        ],

    'Tag::getTagsFromType' =>
        [
            'type' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ]
        ],

    'Config::editConfig' =>
        [
            'MAIL_LANG' =>
                [
                    'type' => 'string',
                    'is_required' => false,
                    'regexp' => '/\w{2}/i'
                ],
            'MAIL_CHARSET' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'MAIL_HOST' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'MAIL_USERNAME' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'MAIL_PASSWORD' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'MAIL_PORT' =>
                [
                    'type' => 'integer',
                    'is_required' => false
                ],
            'MAIL_FROM' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'MAIL_FROM_NAME' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'MAIL_REPLYTO_ADDRESS' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'MAIL_REPLYTO_NAME' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'RSS_DOMAIN' =>
                [
                    'type' => 'string',
                    'is_required' => false,
                    'regexp' => '/^http(s)*:\/\/[\w\d-_]+?\.\w{2,}/i'
                ],
            'RSS_LANG' =>
                [
                    'type' => 'string',
                    'is_required' => false,
                    'regexp' => '/\w{2}/i'
                ],
            'RSS_COPYRIGHT' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'RSS_MANAGING_EDITION' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'RSS_TTL' =>
                [
                    'type' => 'integer',
                    'is_required' => false
                ],
            'RSS_IMAGE_URL' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'RSS_IMAGE_TITLE' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'RSS_IMAGE_LINK' =>
                [
                    'type' => 'string',
                    'is_required' => false,
                    'regexp' => '/^http(s)*:\/\/[\w\d-_]+?\.\w{2,}?(\/.*)*/i'
                ],
            'RSS_IMAGE_WIDTH' =>
                [
                    'type' => 'integer',
                    'is_required' => false
                ],
            'RSS_IMAGE_HEIGHT' =>
                [
                    'type' => 'integer',
                    'is_required' => false
                ],
            'SESSION_TTL' =>
                [
                    'type' => 'integer',
                    'is_required' => false
                ],
            'FILE_MAX_SIZE' =>
                [
                    'type' => 'integer',
                    'is_required' => false
                ],
            'ADMIN_TABLE_ROW_COUNT' =>
                [
                    'type' => 'integer',
                    'is_required' => false
                ],
            'USER_POSTLIST_COUNT' =>
                [
                    'type' => 'integer',
                    'is_required' => false
                ],
            'SESSION_NAME' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'SESSION_DOMAIN' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'ULOGIN_ID' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'ULOGIN_DISPLAY' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'ULOGIN_PROVIDERS' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ],
            'ULOGIN_REDIRECT' =>
                [
                    'type' => 'string',
                    'is_required' => false
                ]
        ]

];