<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-24
 * @Time: 14:39
 * @QQ: 259522
 * @FileName: UserDetailTable.php
 */

namespace XtUser\Model;


use XtBase\Table\AbstractBaseTableGateway;
use XtTool\Tool\IpAddress;
use XtUser\Entity\UserDetailEntity;

/**
 * Class UserDetailTable
 * @package XtUser\Model
 */
class UserDetailTable extends AbstractBaseTableGateway
{
    /**
     *
     */
    public function init()
    {
        $this->table = UserModel::DetailTable();
        $this->primaryKey = 'user_id';
    }

    /**
     * @param UserDetailEntity $userDetailEntity
     * @return int
     */
    public function save(UserDetailEntity $userDetailEntity)
    {
        $userId = (int)$userDetailEntity->getUserId();
        $userDetailData = array_filter((array)$this->resultSetPrototype->getHydrator()->extract($userDetailEntity), function ($value) {
            return $value !== null;
        });
        $userDetailData['modify_time'] = time();
        $userDetailData['modify_ip'] = IpAddress::getIp();
        if ($this->getOneByColumn($userId)) {
            return $this->update($userDetailData, [$this->primaryKey => $userId]);
        } else {
            return $this->insert($userDetailData);
        }
    }

} 