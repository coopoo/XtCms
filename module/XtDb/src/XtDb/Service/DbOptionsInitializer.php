<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-03
 * @Time: 15:22
 * @QQ: 259522
 * @FileName: DbOptionsInitializer.php
 */

namespace XtDb\Service;


use XtDb\Options\DbOptionsAwareInterface;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DbOptionsInitializer implements InitializerInterface
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
        // TODO: Implement initialize() method.
        if ($instance instanceof DbOptionsAwareInterface) {
            $dbOptions = $serviceLocator->get('XtDb\Service\DbOptionsFactory');
            $instance->setDbOptions($dbOptions);
        }
    }

} 