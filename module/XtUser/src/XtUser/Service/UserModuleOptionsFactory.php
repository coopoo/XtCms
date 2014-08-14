<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 13:19
 * @QQ: 259522
 * @FileName: UserModuleOptionsFactory.php
 */

namespace XtUser\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserModuleOptionsFactory implements FactoryInterface
{
    protected $userModuleOptionsKey = 'Xt_User';
    protected $userModuleOptionClass = 'XtUser\Options\UserModuleOptions';
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $userOptions = (isset($config[$this->userModuleOptionsKey])) ? $config[$this->userModuleOptionsKey] : [];
        return new $this->userModuleOptionClass($userOptions);
    }


} 