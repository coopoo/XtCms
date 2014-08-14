<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 13:15
 * @QQ: 259522
 * @FileName: module.config.php
 */
return [
    'router' => [
        'routes' => [
            'Xt_User' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/user[/:action[/:id]].html',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        '__NAMESPACE__' => 'XtUser\Controller',
                        'controller' => 'User',
                        'action' => 'index'
                    ]
                ]
            ], //end user
            'Xt_User_Center' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/user-center[/:action[/:id]].html',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        '__NAMESPACE__' => 'XtUser\Controller',
                        'controller' => 'UserCenter',
                        'action' => 'index'
                    ]
                ]
            ], //end user center

        ]
    ],
    'view_manager' => [
        'template_map' => [

        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ]
    ],
    'Xt_User' => [
        'user_table' => 'user',
        'detail_table' => 'user_detail',
        'logger_table' => 'user_logger',
        'role_table' => 'role',
        'resource_table' => 'resource',
        'permission_table' => 'permission',
        'user_role_table' => 'user_role',
        'role_permission_table' => 'role_permission',
        'disabled_login' => false,
        'disabled_register' => false,
        'password_fail_limit' => 5,
        'password_fail_time' => 3600,
        'credential_column' => 'user_password',
        'credential_type' => 'md5(CONCAT(?,uniqid))',
        'login_captcha' => false,
        'register_captcha' => true,
        'table_pre' => '',//会覆盖全局变量,慎用
    ]
];