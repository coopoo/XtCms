<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-06
 * @Time: 9:39
 * @QQ: 259522
 * @FileName: module.config.php
 */
return [
    'router' => [
        'routes' => [
            'Xt_Admin' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/admin[/]',
                    'defaults' => [
                        '__NAMESPACE__' => 'XtAdmin\Controller',
                        'controller' => 'Index',
                        'action' => 'index'
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'user' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => 'user',
                            'defaults' => [
                                '__NAMESPACE__' => 'XtAdmin\Controller',
                                'controller' => 'User',
                                'action' => 'index'
                            ]
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'default' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => '/[:action[/:id]].html',
                                    'constraints' => [
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id' => '[a-zA-Z0-9_-]+'
                                    ]
                                ]
                            ], //end user default
                            'page' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => '[/:action[/page/:page]].html',
                                    'constraints' => [
                                        'action' => 'list',
                                        'page' => '[0-9]+'
                                    ]
                                ]
                            ]//end user page
                        ]//end user child
                    ], //end admin user
                ]//end admin child
            ], //end admin
        ]
    ],
    'view_manager' => [
        'template_map' => [

        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ]
    ],
];