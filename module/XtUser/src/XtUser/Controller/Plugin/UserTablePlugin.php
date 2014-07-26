<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-23
 * @Time: 14:54
 * @QQ: 259522
 * @FileName: UserTablePlugin.php
 */

namespace XtUser\Controller\Plugin;


use XtUser\Model\UserModel;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;


class UserTablePlugin extends AbstractPlugin implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    protected $userTable;

    public function __invoke()
    {
        return $this->getUserTable();
    }

    public function getUserTable()
    {
        if (!$this->userTable) {
            $this->userTable = $this->getServiceLocator()->getServiceLocator()->get(UserModel::USER_TABLE_CLASS);
        }
        return $this->userTable;
    }
} 