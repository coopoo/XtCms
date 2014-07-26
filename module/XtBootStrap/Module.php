<?php
/**
 * @Created by PhpStorm.
 * @Project: xt
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-06-12
 * @Time: 18:10
 * @QQ: 259522
 * @FileName: Module.php
 */

namespace XtBootstrap;


use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;

class Module implements AutoloaderProviderInterface,
    ViewHelperProviderInterface
{
    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__)
                ]
            ]
        ];
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getViewHelperConfig()
    {
        return [
            'invokables' => [
                'XtBootstrapForm' => 'XtBootstrap\Form\View\Helper\BootstrapForm',
                'XtBootstrapFormRow' => 'XtBootstrap\Form\View\Helper\BootstrapFormRow',
                'XtBootstrapFormElementErrors' => 'XtBootstrap\Form\View\Helper\BootstrapFormElementErrors',
            ]
        ];
    }

} 