<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-03
 * @Time: 15:04
 * @QQ: 259522
 * @FileName: DbOptionsAwareInterface.php
 */

namespace XtDb\Options;


interface DbOptionsAwareInterface
{
    public function setDbOptions(DbOptions $dbOptions);

    public function getDbOptions();
} 