<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-06-29
 * @Time: 13:53
 * @QQ: 259522
 * @FileName: Module.php
 */

namespace XtDb;


use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements ConfigProviderInterface,
    AutoloaderProviderInterface,
    ServiceProviderInterface,
    BootstrapListenerInterface,
    DependencyIndicatorInterface
{
    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        $application = $e->getApplication();
        $eventManager = $application->getEventManager();
        $sharedManager = $eventManager->getSharedManager();
        $sharedManager->attach(__NAMESPACE__, 'dispatch', function ($e) {
            $controller = $e->getTarget();
            // 判断controller 加载不同的layout
            $controller->layout('layout/admin');
        }, 100);

    }

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
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }


    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return [
            'invokables' => [
                'XtDb\Model\DbBackAndRestore' => 'XtDb\Model\DbBackAndRestore',
            ],
            'factories' => [
                'XtDb\Service\DbOptionsFactory' => 'XtDb\Service\DbOptionsFactory'
            ],
            'initializers' => [
                'XtDb\Service\DbOptionsInitializer'
            ]
        ];
    }

    /**
     * Expected to return an array of modules on which the current one depends on
     *
     * @return array
     */
    public function getModuleDependencies()
    {
        return [
            'XtBase'
        ];
    }

} 