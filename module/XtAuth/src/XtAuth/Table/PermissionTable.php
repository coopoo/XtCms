<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-08
 * @Time: 10:32
 * @QQ: 259522
 * @FileName: PermissionTable.php
 */

namespace XtAuth\Table;


use XtAuth\Entity\PermissionEntity;
use XtTool\Tool\IpAddress;
use XtUser\Table\AbstractUserTable;

class PermissionTable extends AbstractUserTable
{
    public function init()
    {
        $this->table = $this->getUserModuleOptions()->getPermissionTable();
        $this->addDateTimeStrategy(['modify_time']);
    }

    public function save(PermissionEntity $permissionEntity)
    {
        $data = [
            'name' => $permissionEntity->getName(),
            'action' => $permissionEntity->getAction(),
            'resource_id' => $permissionEntity->getResourceId(),
            'modify_time' => time(),
            'modify_ip' => IpAddress::getIp()
        ];
        $id = (int)$permissionEntity->getId();
        return $this->insertOrUpdate($data, $id);
    }
} 