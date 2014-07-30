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


use XtUser\InputFilter\RoleInputFilter;
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
        $form = $this->FormElementManager()->get('XtUser\Form\RoleForm');
        $form->get('submit')->setValue('增加角色');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter(new RoleInputFilter());
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
        $form = $this->FormElementManager()->get('XtUser\Form\RoleForm');
        $form->get('submit')->setValue('编辑角色');
        $form->bind($role);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter(new RoleInputFilter($role));
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getRoleTable()->save($form->getData());
                } catch (\Exception $e) {
                    echo $e->getMessage();
                    return ['form' => $form];
                }
                $this->redirect()->toRoute();
            } else {
                var_dump($form->getMessages());
            }
        }
        return ['form' => $form];
    }

    /**
     * @return \Zend\Http\Response
     */
    public function deleteAction()
    {
        $id = (int)$this->params('id');
        $this->getRoleTable()->deleteByColumn($id);
        return $this->redirect()->toRoute();
    }

    /**
     * @return \Zend\Http\Response
     */
    public function enabledAction()
    {
        $id = (int)$this->params('id');
        $this->getRoleTable()->enabledById($id);
        return $this->redirect()->toRoute();
    }

    /**
     * @return \Zend\Http\Response
     */
    public function disabledAction()
    {
        $id = (int)$this->params('id');
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
            $this->roleTable = $this->getServiceLocator()->get('XtUser\Model\RoleTable');
        }
        return $this->roleTable;
    }
} 