<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-08
 * @Time: 10:33
 * @QQ: 259522
 * @FileName: RolePermissionTable.php
 */

namespace XtAuth\Table;


use XtUser\Table\AbstractUserTable;

class RolePermissionTable extends AbstractUserTable
{
    public function init()
    {
        $this->table = $this->getUserModuleOptions()->getRolePermissionTable();
        $this->primaryKey = 'role_id';
    }
} 