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
            'home' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        '__NAMESPACE__' => 'XtUser\Controller',
                        'controller' => 'User',
                        'action' => 'index'
                    ]
                ]
            ],
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
            ],
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
            ],
            'Xt_Role' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/role[/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                        'page' => '[0-9]+'
                    ],
                    'defaults' => [
                        '__NAMESPACE__' => 'XtUser\Controller',
                        'controller' => 'Role',
                        'action' => 'index'
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '[:action[/:id]].html',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[a-zA-Z0-9_-]*',
                                'page' => '[0-9]+'
                            ],
                            'defaults' => [
                                '__NAMESPACE__' => 'XtUser\Controller',
                                'controller' => 'Role',
                                'action' => 'index'
                            ]
                        ]
                    ],
                    'page' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '[:action[/page/:page]].html',
                            'constraints' => [
                                'page' => '[0-9]+'
                            ],
                            'defaults' => [
                                '__NAMESPACE__' => 'XtUser\Controller',
                                'controller' => 'Role',
                                'action' => 'index'
                            ]
                        ]
                    ]
                ]
            ],
        ]
    ],
    'view_manager' => [
        'template_map' => [

        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ]
    ],
    'Xt_user' => [
        'disabled_register' => false,
        'disabled_login' => false,
        'table' => 'xt_user',
        'authentication_storage' => 'Zend\Authentication\Storage\Session',
        'password_fail_limit' => 5,
        'password_fail_time' => 3600,
        'remember_me' => 1440
    ]
];