<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-28
 * @Time: 14:16
 * @QQ: 259522
 * @FileName: RoleController.php
 */

namespace XtUser\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;

class RoleController extends AbstractActionController
{
    protected $roleTable;

    public function addAction()
    {
        $form = $this->FormElementManager()->get('XtUser\Form\RoleForm');
        $form->get('submit')->setValue('增加角色');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getRoleTable()->save($form->getData());
                } catch (\Exception $e) {
                    echo $e->getMessage();
                    return ['form' => $form];
                }
                $this->redirect()->toRoute();
            }
        }

        return ['form' => $form];
    }

    public function editAction()
    {
        $id = $this->params('id');
        $role = $this->getRoleTable()->getOneByColumn($id);
        $form = $this->FormElementManager()->get('XtUser\Form\RoleForm');
        $form->get('submit')->setValue('编辑角色');
        $form->bind($role);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getRoleTable()->save($form->getData());
                } catch (\Exception $e) {
                    echo $e->getMessage();
                    return ['form' => $form];
                }
                $this->redirect()->toRoute();
            }
        }
        return ['form' => $form];
    }

    public function deleteAction()
    {
    }

    public function indexAction()
    {
        return $this->redirect()->toRoute('Xt_Role/page', ['action' => 'list']);
    }

    public function listAction()
    {
        $page = $this->params('page', 1);
        $roles = $this->getRoleTable()->getPaginator();
        $roles->setCurrentPageNumber($page);
        return ['roles' => $roles];
    }

    protected function getRoleTable()
    {
        if (!$this->roleTable) {
            $this->roleTable = $this->getServiceLocator()->get('XtUser\Model\RoleTable');
        }
        return $this->roleTable;
    }
} 