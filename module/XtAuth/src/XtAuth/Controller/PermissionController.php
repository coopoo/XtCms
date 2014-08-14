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
    protected $permissionTable;
    public function indexAction()
    {
        return $this->redirect()->toRoute(static::PAGE_ROUTE, ['action' => 'list']);
    }

    public function addAction()
    {
        $form = $this->FormElementManager()->get('XtAuth\Form\PermissionForm');
        $form->get('submit')->setValue('增加权限');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $inputFilter = $this->InputFilterManager()->get('XtAuth\InputFilter\PermissionInputFilter');
            $form->setInputFilter($inputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getPermissionTable()->save($form->getData());
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
        $permission = $this->getPermissionTable()->getOneByColumn($id);
        if (!$permission) {
            $this->redirect()->toRoute();
        }
        $form = $this->FormElementManager()->get('XtAuth\Form\PermissionForm');
        $form->get('submit')->setValue('编辑角色');
        $form->bind($permission);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $inputFilter = $this->InputFilterManager()->get('XtAuth\InputFilter\PermissionInputFilter');
            $form->setInputFilter($inputFilter($permission));
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getPermissionTable()->save($form->getData());
                } catch (\Exception $e) {
                    echo $e->getMessage();
                    return ['form' => $form];
                }
                $this->redirect()->toRoute();
            }
        }
        return ['form' => $form];
    }

    public function listAction()
    {
        $page = $this->params('page', 1);
        $permissions = $this->getPermissionTable()->getPaginator($page);
        return ['permissions' => $permissions];
    }

    public function deleteAction()
    {
        $id = (int)$this->params('id', null);
        $this->getPermissionTable()->deleteByColumn($id);
        return $this->redirect()->toRoute();
    }

    protected function getPermissionTable()
    {
        if (!$this->permissionTable) {
            $this->permissionTable = $this->getServiceLocator()->get('XtAuth\Table\PermissionTable');
        }
        return $this->permissionTable;
    }
} 