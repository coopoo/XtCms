<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-14
 * @Time: 14:58
 * @QQ: 259522
 * @FileName: PermissionController.php
 */

namespace XtAuth\Controller;


use Zend\Mvc\Controller\AbstractActionController;

class PermissionController extends AbstractActionController implements CrudControllerInterface
{
    const DEFAULT_ROUTE = '';
    const PAGE_ROUTE = 'Xt_Permission/page';

    public function indexAction()
    {
        return $this->redirect()->toRoute(static::PAGE_ROUTE, ['action' => 'list']);
    }

    public function addAction()
    {
        // TODO: Implement addAction() method.
    }

    public function editAction()
    {
        // TODO: Implement editAction() method.
    }

    public function listAction()
    {
        // TODO: Implement listAction() method.
    }

    public function deleteAction()
    {
        // TODO: Implement deleteAction() method.
    }

} 