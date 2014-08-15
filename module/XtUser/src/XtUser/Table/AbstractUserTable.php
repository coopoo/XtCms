<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-06
 * @Time: 10:23
 * @QQ: 259522
 * @FileName: AbstractUserTable.php
 */

namespace XtUser\Table;


use XtBase\Table\AbstractBaseTableGateway;
use XtUser\Model\UserModel;
use XtUser\Options\UserModuleOptionsAwareInterFace;
use XtUser\Service\UserModuleOptionsTrait;

class AbstractUserTable extends AbstractBaseTableGateway implements UserModuleOptionsAwareInterFace
{
    use UserModuleOptionsTrait;
    /**
     * @var UserDetailTable
     */
    protected $userDetailTable;
    /**
     * @var UserLoggerTable
     */
    protected $userLoggerTable;

    protected $userRoleTable;

    protected $roleTable;

    /**
     * @return array|object
     */
    protected function getUserDetailTable()
    {
        if (!$this->userDetailTable) {
            $this->userDetailTable = $this->serviceLocator->get(UserModel::USER_DETAIL_TABLE_CLASS);
        }
        return $this->userDetailTable;
    }

    /**
     * @return UserLoggerTable
     */
    protected function getUserLoggerTable()
    {
        if (!$this->userLoggerTable) {
            $this->userLoggerTable = $this->serviceLocator->get(UserModel::USER_LOGGER_TABLE_CLASS);
        }
        return $this->userLoggerTable;
    }

    /**
     * @return array|object
     */
    protected function getUserRoleTable()
    {
        if (!$this->userRoleTable) {
            $this->userRoleTable = $this->serviceLocator->get(UserModel::USER_ROLE_TABLE_CLASS);
        }
        return $this->userRoleTable;
    }

    protected function getRoleTable()
    {
        if (!$this->roleTable) {
            $this->roleTable = $this->serviceLocator->get(UserModel::ROLE_TABLE_CLASS);
        }
        return $this->roleTable;
    }

} 