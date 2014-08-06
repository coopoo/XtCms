<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-05
 * @Time: 15:49
 * @QQ: 259522
 * @FileName: Module.php
 */

namespace XtAuth;


use Zend\EventManager\EventInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\FormElementProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements AutoloaderProviderInterface,
    ConfigProviderInterface,
    BootstrapListenerInterface,
    ServiceProviderInterface,
    FormElementProviderInterface,
    InputFilterProviderInterface
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
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        $application = $e->getApplication();
        $eventManager = $application->getEventManager();
        $eventManager->attach($application->getServiceManager()->get('XtAuth\Listener\AuthenticationListener'));
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
                'XtAuth\Listener\AuthenticationListener' => 'XtAuth\Listener\AuthenticationListener',
                'XtAuth\Authentication\Storage\DbSession' => 'XtAuth\Authentication\Storage\DbSession',
                'XtAuth\Service\Authenticate' => 'XtAuth\Service\AuthenticateInvokable',
                'XtAuth\Table\RoleTable' => 'XtAuth\Table\RoleTable',
                'XtAuth\Table\ResourceTable' => 'XtAuth\Table\ResourceTable',
            ],
            'aliases' => [
                'Zend\Authentication\AuthenticationService' => 'XtAuth\Service\Authenticate',
            ],
            'initializers' => [
                'XtUser\Service\UserModuleOptionsInitializer'
            ]
        ];
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getFormElementConfig()
    {
        return [
            'invokables' => [
                'XtAuth\Form\RoleForm' => 'XtAuth\Form\RoleForm',
                'XtAuth\Form\ResourceForm' => 'XtAuth\Form\ResourceForm',
            ]
        ];
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'invokables' => [
                'XtAuth\InputFilter\RoleInputFilter' => 'XtAuth\InputFilter\RoleInputFilter',
                'XtAuth\InputFilter\ResourceInputFilter' => 'XtAuth\InputFilter\ResourceInputFilter'
            ],
            'initializers' => [
                'XtUser\Service\UserModuleOptionsInitializer'
            ]
        ];
    }

} 