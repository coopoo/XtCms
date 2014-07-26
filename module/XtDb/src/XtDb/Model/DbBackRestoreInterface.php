<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-06-27
 * @Time: 11:15
 * @QQ: 259522
 * @FileName: DbBackRestoreInterface.php
 */

namespace XtDb\Model;


interface DbBackRestoreInterface
{
    /**
     * @return mixed
     */
    public function back();

    /**
     * @param string $file
     * @return mixed
     */
    public function restore($file);

    /**
     * @return mixed
     */
    public function getDbState();

    /**
     * @param string|array $table
     * @return mixed
     */
    public function truncateTable($table);

    /**
     * @param string|array $table
     * @return mixed
     */
    public function dropTable($table);

    /**
     * @param string $file
     * @return mixed
     */
    public function unlink($file);

} 