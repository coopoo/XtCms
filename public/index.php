<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014/6/26
 * @Time: 19:54
 * @QQ: 259522
 * @FileName: index.php
 */
ini_set('display_errors', true);
error_reporting(E_ALL);

if (PHP_VERSION < '5.4.0') {
    exit('PHP Version is to Low!');
}
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();