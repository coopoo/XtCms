<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-14
 * @Time: 16:42
 * @QQ: 259522
 * @FileName: AbstractAuthForm.php
 */

namespace XtAuth\Form;


use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Class AbstractAuthForm
 * @package XtAuth\Form
 */
abstract class AbstractAuthForm extends Form implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    /**
     * @var \XtAuth\Table\ResourceTable
     */
    protected $resourceTable;

    /**
     * @var \XtAuth\Table\PermissionTable
     */
    protected $permissionTable;

    /**
     * @return \XtAuth\Table\ResourceTable
     */
    protected function getResourceTable()
    {
        if (!$this->resourceTable) {
            $this->resourceTable = $this->getServiceLocator()
                ->getServiceLocator()
                ->get('XtAuth\Table\ResourceTable');
        }
        return $this->resourceTable;
    }


    /**
     * @return \XtAuth\Table\PermissionTable
     */
    protected function getPermissionTable()
    {
        if (!$this->permissionTable) {
            $this->permissionTable = $this->getServiceLocator()
                ->getServiceLocator()
                ->get('XtAuth\Table\PermissionTable');
        }
        return $this->permissionTable;
    }
} 