<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-08
 * @Time: 20:01
 * @QQ: 259522
 * @FileName: module.config.php
 */
return [
    'router' => [
        'routes' => [
            'Xt_Captcha' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/captcha[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9]*',
                        'id' => '[a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        '__NAMESPACE__' => 'XtCaptcha\Controller',
                        'controller' => 'Index',
                        'action' => 'show'
                    ]
                ]
            ]//end xt_captcha
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