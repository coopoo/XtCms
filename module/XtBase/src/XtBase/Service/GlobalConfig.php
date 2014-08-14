<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-14
 * @Time: 13:46
 * @QQ: 259522
 * @FileName: GlobalConfig.php
 */

namespace XtBase\Service;


class GlobalConfig
{
    protected static $tablePre;

    /**
     * @return mixed
     */
    public static function getTablePre()
    {
        return self::$tablePre;
    }

    /**
     * @param mixed $tablePre
     *
     * @return $this;
     */
    public static function setTablePre($tablePre)
    {
        self::$tablePre = $tablePre;
    }


} 