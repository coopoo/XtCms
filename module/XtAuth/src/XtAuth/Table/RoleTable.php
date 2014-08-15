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

namespace XtAuth\Table;


use XtTool\Tool\IpAddress;
use XtAuth\Entity\RoleEntity;
use XtUser\Table\AbstractUserTable;

/**
 * Class RoleTable
 * @package XtAuth\Table
 */
class RoleTable extends AbstractUserTable
{
    /**
     *
     */
    public function init()
    {
        $this->table = $this->userModuleOptions->getRoleTable();
        $this->addDateTimeStrategy('modify_time');
    }

    /**
     * @param RoleEntity $roleEntity
     * @return int
     */
    public function save(RoleEntity $roleEntity)
    {
        $data = [
            'name' => $roleEntity->getName(),
            'status' => $roleEntity->getStatus(),
            'modify_time' => time(),
            'modify_ip' => IpAddress::getIp()
        ];
        $id = (int)$roleEntity->getId();
        return $this->insertOrUpdate($data, $id);
    }

    /**
     * @return array
     */
    public function fetchAllRole()
    {
        return $this->fetchAll(['status' => static::DEFAULT_ENABLED_STATUS], ['id', 'name'])->toArray();
    }

} 