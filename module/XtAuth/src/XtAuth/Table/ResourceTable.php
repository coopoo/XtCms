<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-31
 * @Time: 19:48
 * @QQ: 259522
 * @FileName: ResourceTable.php
 */

namespace XtAuth\Table;


use XtTool\Tool\IpAddress;
use XtAuth\Entity\ResourceEntity;
use XtUser\Table\AbstractUserTable;

class ResourceTable extends AbstractUserTable
{

    public function init()
    {
        $this->table = $this->userModuleOptions->getResourceTable();
        $this->addDateTimeStrategy(['modify_time']);
    }

    public function save(ResourceEntity $resourceEntity)
    {
        $data = [
            'name' => $resourceEntity->getName(),
            'action' => $resourceEntity->getAction(),
            'modify_time' => time(),
            'modify_ip' => IpAddress::getIp()
        ];
        $id = (int)$resourceEntity->getId();
        return $this->insertOrUpdate($data, $id);
    }

    public function getResourceList()
    {
        $resultSet = $this->fetchAll();
        $resourceList = [];
        foreach ($resultSet as $resource) {
            $resourceList[$resource->getId()] = $resource->getName();
        }
        return $resourceList;
    }
} 