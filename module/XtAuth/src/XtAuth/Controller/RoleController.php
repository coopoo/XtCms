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

namespace XtAuth\Controller;


use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class RoleController
 * @package XtUser\Controller
 */
class RoleController extends AbstractActionController
{
    /**
     * @var
     */
    protected $roleTable;

    /**
     * @return array
     */
    public function addAction()
    {
        $form = $this->FormElementManager()->get('XtAuth\Form\RoleForm');
        $form->get('submit')->setValue('增加角色');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $inputFilter = $this->InputFilterManager()->get('XtAuth\InputFilter\RoleInputFilter');
            $form->setInputFilter($inputFilter());
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

    /**
     * @return array
     */
    public function editAction()
    {
        $id = $this->params('id');
        $role = $this->getRoleTable()->getOneByColumn($id);
        if (!$role) {
            $this->redirect()->toRoute();
        }
        $form = $this->FormElementManager()->get('XtAuth\Form\RoleForm');
        $form->get('submit')->setValue('编辑角色');
        $form->bind($role);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $inputFilter = $this->InputFilterManager()->get('XtAuth\InputFilter\RoleInputFilter');
            $form->setInputFilter($inputFilter($role));
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

    /**
     * @return \Zend\Http\Response
     */
    public function deleteAction()
    {
        $id = (int)$this->params('id', null);
        $this->getRoleTable()->deleteByColumn($id);
        return $this->redirect()->toRoute();
    }

    /**
     * @return \Zend\Http\Response
     */
    public function enabledAction()
    {
        $id = (int)$this->params('id', null);
        $this->getRoleTable()->enabledById($id);
        return $this->redirect()->toRoute();
    }

    /**
     * @return \Zend\Http\Response
     */
    public function disabledAction()
    {
        $id = (int)$this->params('id', null);
        $this->getRoleTable()->disabledById($id);
        return $this->redirect()->toRoute();
    }

    /**
     * @return \Zend\Http\Response
     */
    public function indexAction()
    {
        return $this->redirect()->toRoute('Xt_Role/page', ['action' => 'list']);
    }

    /**
     * @return array
     */
    public function listAction()
    {
        $page = $this->params('page', 1);
        $roles = $this->getRoleTable()->getPaginator($page);
        return ['roles' => $roles];
    }

    /**
     * @return array|object
     */
    protected function getRoleTable()
    {
        if (!$this->roleTable) {
            $this->roleTable = $this->getServiceLocator()->get('XtAuth\Table\RoleTable');
        }
        return $this->roleTable;
    }
} 