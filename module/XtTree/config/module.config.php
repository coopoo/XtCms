<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-15
 * @Time: 9:25
 * @QQ: 259522
 * @FileName: module.config.php
 */
return [
    'router' => [
        'routes' => [
            'XtTree-admin' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/tree',
                    'defaults' => [
                        '__NAMESPACE__' => 'XtTree\Controller',
                        'controller' => 'Test',
                        'action' => 'index'
                    ]
                ]
            ]
        ]
    ]
];