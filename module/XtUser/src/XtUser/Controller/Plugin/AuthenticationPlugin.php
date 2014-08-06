<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-23
 * @Time: 16:12
 * @QQ: 259522
 * @FileName: AuthenticationPlugin.php
 */

namespace XtUser\Controller\Plugin;


use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class AuthenticationPlugin extends AbstractPlugin implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function __invoke()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('XtAuth\Service\Authenticate');
    }
} 