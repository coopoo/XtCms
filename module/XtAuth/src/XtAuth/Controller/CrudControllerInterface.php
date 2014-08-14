<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-14
 * @Time: 14:59
 * @QQ: 259522
 * @FileName: CrudControllerInterface.php
 */

namespace XtAuth\Controller;


interface CrudControllerInterface
{
    public function indexAction();

    public function addAction();

    public function editAction();

    public function listAction();

    public function deleteAction();
} 