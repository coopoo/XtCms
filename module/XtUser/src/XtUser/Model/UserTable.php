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

namespace XtUser\Model;


use XtBase\Table\AbstractBaseTableGateway;
use XtUser\Entity\UserEntity;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Class UserTable
 * @package XtUser\Model
 */
class UserTable extends AbstractBaseTableGateway implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var UserDetailTable
     */
    protected $userDetailTable;
    /**
     * @var UserLoggerTable
     */
    protected $userLoggerTable;

    /**
     *
     */
    public function init()
    {
        $this->table = UserModel::TABLE;
    }

    /**
     * @param UserEntity $userEntity
     * @return int
     */
    public function save(UserEntity $userEntity)
    {
        $id = (int)$userEntity->getId();
        $userData = array_filter((array)$this->resultSetPrototype->getHydrator()->extract($userEntity), function ($value) {
            return $value !== null;
        });
        unset($userData['remember_me']);
        if (!$id) {
            return $this->insert($userData) ? $this->lastInsertValue : 0;
        }
        if ($this->getOneByColumn($id)) {
            return $this->update($userData, [$this->primaryKey => $id]);
        }
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
        $userLogger = $this->getUserLoggerTable()->getLastLoggerById($id, 1)->current();
        if ($userLogger) {
            $userEntity->setUserLogger($userLogger);
        }
        return $userEntity;
    }

    public function getUserLogger($id, $times = 15)
    {
        return $this->getUserLoggerTable()->getLastLoggerById($id, $times);
    }

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