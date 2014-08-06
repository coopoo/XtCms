<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-29
 * @Time: 16:30
 * @QQ: 259522
 * @FileName: ResourceController.php
 */

namespace XtAuth\Controller;


use Zend\Mvc\Controller\AbstractActionController;

class ResourceController extends AbstractActionController
{
    protected $resourceTable;

    public function indexAction()
    {
        return $this->redirect()->toRoute('Xt_Resource/page', ['action' => 'list']);
    }

    public function addAction()
    {
        $form = $this->FormElementManager()->get('XtAuth\Form\ResourceForm');
        $form->get('submit')->setValue('增加资源');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $inputFilter = $this->InputFilterManager()->get('XtAuth\InputFilter\ResourceInputFilter');
            $form->setInputFilter($inputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getResourceTable()->save($form->getData());
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
        $resource = $this->getResourceTable()->getOneByColumn($id);
        $form = $this->FormElementManager()->get('XtAuth\Form\ResourceForm');
        $form->get('submit')->setValue('编辑资源');
        $form->bind($resource);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $inputFilter = $this->InputFilterManager()->get('XtAuth\InputFilter\ResourceInputFilter');
            $form->setInputFilter($inputFilter($resource));
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getResourceTable()->save($form->getData());
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
        $id = (int)$this->params('id');
        $this->getResourceTable()->deleteByColumn($id);
        return $this->redirect()->toRoute();
    }

    public function listAction()
    {
        $page = $this->params('page', 1);
        $resources = $this->getResourceTable()->getPaginator($page);
        return ['resources' => $resources];
    }

    protected function getResourceTable()
    {
        if (!$this->resourceTable) {
            $this->resourceTable = $this->getServiceLocator()->get('XtAuth\Table\ResourceTable');
        }
        return $this->resourceTable;
    }
} 