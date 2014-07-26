<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-23
 * @Time: 15:03
 * @QQ: 259522
 * @FileName: FormElementManagerPlugin.php
 */

namespace XtUser\Controller\Plugin;


use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class FormElementManagerPlugin extends AbstractPlugin implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    protected $formElementManager;

    public function __invoke()
    {
        return $this->getFormElementManager();
    }

    protected function getFormElementManager()
    {
        if (!$this->formElementManager) {
            $this->formElementManager = $this->getServiceLocator()->getServiceLocator()->get('formElementManager');
        }
        return $this->formElementManager;
    }
} 