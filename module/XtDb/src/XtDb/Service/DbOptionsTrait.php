<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-03
 * @Time: 15:07
 * @QQ: 259522
 * @FileName: DbOptionsTrait.php
 */

namespace XtDb\Service;


use XtDb\Options\DbOptions;

trait DbOptionsTrait
{
    /**
     * @var DbOptions
     */
    protected $dbOptions;

    /**
     * @param DbOptions $dbOptions
     */
    public function setDbOptions(DbOptions $dbOptions)
    {
        $this->dbOptions = $dbOptions;
    }

    /**
     * @return mixed
     */
    public function getDbOptions()
    {
        return $this->dbOptions;
    }

} 