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


use XtUser\Table\AbstractUserTable;

class PermissionTable extends AbstractUserTable
{
    public function init()
    {
        $this->table = $this->getUserModuleOptions()->getPermissionTable();
    }
} 