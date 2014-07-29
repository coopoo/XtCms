<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-28
 * @Time: 14:43
 * @QQ: 259522
 * @FileName: RoleTable.php
 */

namespace XtUser\Model;


use XtBase\Table\AbstractBaseTableGateway;
use XtTool\Tool\IpAddress;
use XtUser\Entity\RoleEntity;

class RoleTable extends AbstractBaseTableGateway
{
    public function init()
    {
        $this->table = 'xt_role';
    }

    public function save(RoleEntity $roleEntity)
    {
        $data = [
            'name' => $roleEntity->getName(),
            'status' => $roleEntity->getStatus(),
            'modify_time' => time(),
            'modify_ip' => IpAddress::getIp()
        ];
        $id = (int)$roleEntity->getId();
        if ($id == 0) {
            return $this->insert($data) ? $this->lastInsertValue : 0;
        }
        if ($this->getOneByColumn($id)) {
            return $this->update($data, [$this->primaryKey => $id]);
        }
    }
} 