<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 13:14
 * @QQ: 259522
 * @FileName: Module.php
 */

namespace XtUser;


use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerPluginProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\ModuleManager\Feature\FormElementProviderInterface;
use Zend\ModuleManager\Feature\InputFilterProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;


class Module implements AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface,
    ControllerPluginProviderInterface,
    ControllerProviderInterface,
    FormElementProviderInterface,
    BootstrapListenerInterface,
    InputFilterProviderInterface,
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
        $eventManager->attach($application->getServiceManager()->get('XtUser\Listener\AuthenticationListener'));
        $eventManager->attach($application->getServiceManager()->get('XtUser\Listener\ChangePasswordListener'));
        $eventManager->attach($application->getServiceManager()->get('XtUser\Listener\LoginListener'));
        $eventManager->attach($application->getServiceManager()->get('XtUser\Listener\RegisterListener'));
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
                'XtUser\Model\UserTable' => 'XtUser\Model\UserTable',
                'XtUser\Model\UserLoggerTable' => 'XtUser\Model\UserLoggerTable',
                'XtUser\Model\UserDetailTable' => 'XtUser\Model\UserDetailTable',
                'XtUser\Service\Authenticate' => 'XtUser\Service\AuthenticateInvokable',
                'XtUser\Model\RoleTable' => 'XtUser\Model\RoleTable',
                'XtUser\Listener\AuthenticationListener' => 'XtUser\Listener\AuthenticationListener',
                'XtUser\Listener\ChangePasswordListener' => 'XtUser\Listener\ChangePasswordListener',
                'XtUser\Listener\LoginListener' => 'XtUser\Listener\LoginListener',
                'XtUser\Listener\RegisterListener' => 'XtUser\Listener\RegisterListener',
            ],
            'factories' => [
                'XtUser\Service\UserModuleOptions' => 'XtUser\Service\UserModuleOptionsFactory',
            ],
            'initializers' => [
                'XtUser\Service\UserModuleOptionsInitializer'
            ],
            'aliases' => [
                'Zend\Authentication\AuthenticationService' => 'XtUser\Service\Authenticate',
            ],
        ];
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getControllerPluginConfig()
    {
        return [
            'invokables' => [
                'Authentication' => 'XtUser\Controller\Plugin\AuthenticationPlugin',
                'UserTable' => 'XtUser\Controller\Plugin\UserTablePlugin',
                'FormElementManager' => 'XtUser\Controller\Plugin\FormElementManagerPlugin',
                'InputFilterManager' => 'XtUser\Controller\Plugin\InputFilterManagerPlugin'
            ]
        ];
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to seed
     * such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getControllerConfig()
    {
        return [
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
                'XtUser\Form\LoginForm' => 'XtUser\Form\LoginForm',
                'XtUser\Form\RegisterForm' => 'XtUser\Form\RegisterForm',
                'XtUser\Form\EditForm' => 'XtUser\Form\EditForm',
                'XtUser\Form\DetailForm' => 'XtUser\Form\DetailForm',
                'XtUser\Form\ChangePasswordForm' => 'XtUser\Form\ChangePasswordForm',
                'XtUser\Form\RoleForm' => 'XtUser\Form\RoleForm',
            ]
        ];
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getInputFilterConfig()
    {
        return [
            'invokables' => [
                'XtUser\InputFilter\ChangePasswordInputFilter' => 'XtUser\InputFilter\ChangePasswordInputFilter',
                'XtUser\InputFilter\DetailInputFilter' => 'XtUser\InputFilter\DetailInputFilter',
                'XtUser\InputFilter\EditInputFilter' => 'XtUser\InputFilter\EditInputFilter',
                'XtUser\InputFilter\LoginInputFilter' => 'XtUser\InputFilter\LoginInputFilter',
                'XtUser\InputFilter\RegisterInputFilter' => 'XtUser\InputFilter\RegisterInputFilter',
                'XtUser\InputFilter\RoleInputFilter' => 'XtUser\InputFilter\RoleInputFilter',
                'XtUser\InputFilter\UserInputFilter' => 'XtUser\InputFilter\UserInputFilter'
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
            'XtBase',
            'XtBootstrap'
        ];
    }

} 