<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 13:33
 * @QQ: 259522
 * @FileName: UserModuleOptionsInitializer.php
 */

namespace XtUser\Service;


use XtUser\Options\UserModuleOptionsAwareInterFace;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserModuleOptionsInitializer implements InitializerInterface
{
    /**
     * Initialize
     *
     * @param $instance
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof UserModuleOptionsAwareInterFace) {
            if ($serviceLocator instanceof ServiceLocatorAwareInterface) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
            $userModuleOptions = $serviceLocator->get('XtUser\Service\UserModuleOptions');
            $instance ->setUserModuleOptions($userModuleOptions);
        }
    }

} 