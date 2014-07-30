<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-30
 * @Time: 13:48
 * @QQ: 259522
 * @FileName: InputFilterManagerPlugin.php
 */

namespace XtUser\Controller\Plugin;


use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class InputFilterManagerPlugin extends AbstractPlugin implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function __invoke()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('inputFilterManager');
    }

} 