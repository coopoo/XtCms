<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-06-27
 * @Time: 9:48
 * @QQ: 259522
 * @FileName: AdapterInitializer.php
 */

namespace XtBase\Service;


use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdapterInitializer implements InitializerInterface
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
        if ($instance instanceof AdapterAwareInterface) {
            $instance->setDbAdapter($serviceLocator->get('Zend\Db\Adapter\Adapter'));
        }
    }

} 