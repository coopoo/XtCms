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
use XtUser\Options\UserModuleOptionsAwareInterFace;
use XtUser\Service\UserModuleOptionsTrait;

class RoleTable extends AbstractBaseTableGateway implements UserModuleOptionsAwareInterFace
{
    use UserModuleOptionsTrait;

    public function init()
    {
        $this->table = $this->userModuleOptions->getRoleTable();
        $this->addDateTimeStrategy('modify_time');
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
        return $this->insertOrUpdate($data, $id);
    }


} 