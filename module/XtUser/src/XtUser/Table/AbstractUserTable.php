<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-06
 * @Time: 10:23
 * @QQ: 259522
 * @FileName: AbstractUserTable.php
 */

namespace XtUser\Table;


use XtBase\Table\AbstractBaseTableGateway;
use XtUser\Options\UserModuleOptionsAwareInterFace;
use XtUser\Service\UserModuleOptionsTrait;

class AbstractUserTable extends AbstractBaseTableGateway implements UserModuleOptionsAwareInterFace
{
    use UserModuleOptionsTrait;
} 