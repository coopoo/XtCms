<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014/6/26
 * @Time: 18:58
 * @QQ: 259522
 * @FileName: global.php
 */
return [
    'db' => [
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=xtcms;host=localhost',
        'driver_options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ]
    ],
    'service_manager' => [
        'factories' => [
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ]
    ]
];