<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 17:04
 * @QQ: 259522
 * @FileName: UserTablePlugin.php
 */

namespace XtUser\Table;


use XtUser\Entity\UserEntity;

/**
 * Class UserTable
 * @package XtUser\Model
 */
class UserTable extends AbstractUserTable
{
    /**
     *
     */
    public function init()
    {
        $this->table = $this->userModuleOptions->getUserTable();
        $this->addDateTimeStrategy(['register_time', 'last_error_time']);
    }

    /**
     * @param UserEntity $userEntity
     * @return int
     */
    public function save(UserEntity $userEntity)
    {
        $id = (int)$userEntity->getId();
        $users = $this->resultSetPrototype->getHydrator()->extract($userEntity);
        $userData = array_filter((array)$users, function ($value) {
            return $value !== null;
        });
        unset($userData['remember_me']);
        return $this->insertOrUpdate($userData, $id);
    }

    /**
     * @param $id
     * @return array|\ArrayObject|null
     */
    public function getUserById($id)
    {
        $id = (int)$id;
        $userEntity = $this->getOneByColumn($id);
        $userDetail = $this->getUserDetailTable()->getOneByColumn($id);
        if ($userDetail) {
            $userEntity->setUserDetail($userDetail);
        }
        $userEntity->setRole($this->getRoleById($id));

        $userLogger = $this->getUserLoggerTable()->getLastLoggerById($id, 1)->current();
        if ($userLogger) {
            $userEntity->setUserLogger($userLogger);
        }
        return $userEntity;
    }

    /**
     * @param $id
     * @return array|null
     */
    public function getRoleById($id)
    {
        $userRoles = $this->getUserRoleTable()->getByColumn($id);
        if (0 < $userRoles->count()) {
            $RoleArray = [];
            foreach ($userRoles as $userRole) {
                $RoleArray[] = $this->getRoleTable()->getOneByColumn($userRole->getRoleId());
            }
        }
        return !empty($RoleArray) ? $RoleArray : null;
    }

    /**
     * @param $id
     * @param int $times
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getUserLogger($id, $times = 15)
    {
        return $this->getUserLoggerTable()->getLastLoggerById($id, $times);
    }


    /**
     * @param $id
     */
    public function deleteUserById($id)
    {
        $id = (int)$id;
        try {
            $this->getConnection()->beginTransaction();
            $this->getUserDetailTable()->deleteByColumn($id);
            $this->deleteByColumn($id);
            $this->getUserLoggerTable()->deleteByColumn($id);
            $this->getConnection()->commit();
        } catch (\Exception $e) {
            $this->getConnection()->rollback();
        }
        return;
    }


} 