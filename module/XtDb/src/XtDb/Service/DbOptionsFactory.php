<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-03
 * @Time: 14:59
 * @QQ: 259522
 * @FileName: DbOptionsFactory.php
 */

namespace XtDb\Service;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DbOptionsFactory implements FactoryInterface
{
    protected $configKey = 'XtDb';
    protected $optionsClass = 'XtDb\Options\DbOptions';

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $options = isset($config[$this->configKey]) ? $config[$this->configKey] : [];
        return new $this->optionsClass($options);
    }

} 