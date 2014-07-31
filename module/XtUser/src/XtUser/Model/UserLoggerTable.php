<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-24
 * @Time: 13:13
 * @QQ: 259522
 * @FileName: UserLoggerTable.php
 */

namespace XtUser\Model;


use XtBase\Table\AbstractBaseTableGateway;
use XtTool\Tool\IpAddress;
use XtUser\Entity\UserEntity;
use Zend\Db\Sql\Select;

/**
 * Class UserLoggerTable
 * @package XtUser\Model
 */
class UserLoggerTable extends AbstractBaseTableGateway
{
    /**
     *
     */
    public function init()
    {
        $this->table = UserModel::LoggerTable();
        $this->primaryKey = 'user_id';
        $this->addDateTimeStrategy('login_time');
    }

    /**
     * @param UserEntity $userEntity
     */
    public function save(UserEntity $userEntity)
    {
        $data = [
            $this->primaryKey => $userEntity->getId(),
            'login_time' => time(),
            'login_ip' => IpAddress::getIp()
        ];
        $this->insert($data);
    }

    public function getLastLoggerById($id, $times = 15)
    {
        $id = (int)$id;
        return $this->select(function (Select $select) use ($id, $times) {
            $select->where([$this->primaryKey => $id]);
            $select->order(['login_time' => 'DESC'])->limit($times);
        });
    }


} 