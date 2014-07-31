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

namespace XtUser\Model;


use XtBase\Table\AbstractBaseTableGateway;
use XtTool\Tool\IpAddress;
use XtUser\Entity\ResourceEntity;

class ResourceTable extends AbstractBaseTableGateway
{
    public function init()
    {
        $this->table = UserModel::ResourceTable();
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


} 