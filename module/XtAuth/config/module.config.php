<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-05
 * @Time: 15:50
 * @QQ: 259522
 * @FileName: module.config.php
 */
return [
    'router' => [
        'routes' => [
            'Xt_Role' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/role[/]',
                    'defaults' => [
                        '__NAMESPACE__' => 'XtAuth\Controller',
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
                                'id' => '[a-zA-Z0-9_-]*'
                            ]
                        ]
                    ], //end role default
                    'page' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '[:action[/page/:page]].html',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'page' => '[0-9]+'
                            ]
                        ]
                    ]//end role page
                ]//end role child
            ], //end role

            'Xt_Resource' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/resource[/]',
                    'defaults' => [
                        '__NAMESPACE__' => 'XtAuth\Controller',
                        'controller' => 'Resource',
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
                                'id' => '[a-zA-Z0-9_-]*'
                            ]
                        ]
                    ], //end Resource default
                    'page' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '[:action[/page/:page]].html',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'page' => '[0-9]+'
                            ]
                        ]
                    ]//end Resource page
                ]//end Resource child
            ], //end Resource
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