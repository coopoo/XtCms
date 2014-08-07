<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-06
 * @Time: 9:37
 * @QQ: 259522
 * @FileName: UserController.php
 */

namespace XtAdmin\Controller;


use XtUser\Event\UserEvent;
use XtUser\Model\UserModel;
use XtUser\Table\UserTable;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class UserController
 * @package XtAdmin\Controller
 */
class UserController extends AbstractActionController
{
    /**
     * @var null|UserTable
     */
    protected $userTable = null;
    protected $succeed = false;

    /**
     * @return \Zend\Http\Response
     */
    public function indexAction()
    {
        return $this->redirect()->toRoute('Xt_Admin/user/page', ['action' => 'list']);
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function addAction()
    {
        $form = $this->FormElementManager()->get('XtUser\Form\RegisterForm');
        $form->get('submit')->setValue('添加用户');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $inputFilter = $this->InputFilterManager()->get('XtUser\InputFilter\RegisterInputFilter');
            $form->setInputFilter($inputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $userEntity = $form->getData();
                $userEvent = new UserEvent();
                $userEvent->setUserEntity($userEntity)->setForm($form)->setServiceLocator($this->getServiceLocator());
                $eventManager = $this->getEventManager();
                $responseCollection = $eventManager->trigger(UserEvent::USER_REGISTER_PRE, $this, $userEvent, function ($response) {
                    return $response === false;
                });
                if ($responseCollection->last() !== false) {
                    try {
                        $this->getUserTable()->save($userEntity);
                        $this->succeed = true;
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                    }
                    if ($this->succeed) {
                        return $this->redirect()->toRoute();
                    }
                }
            }
        }
        return ['form' => $form];
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function editAction()
    {
        $id = (int)$this->params('id', null);
        $userEntity = $this->UserTable()->getOneByColumn($id);
        if (!$userEntity) {
            return $this->redirect()->toRoute();
        }
        $form = $this->FormElementManager()->get('XtUser\Form\UserExpandForm');
        $form->bind($userEntity);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $inputFilter = $this->InputFilterManager()->get('XtUser\InputFilter\UserExpandInputFilter');
            $form->setInputFilter($inputFilter($userEntity));
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getUserTable()->save($userEntity);
                    $this->succeed = true;
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
                if ($this->succeed) {
                    return $this->redirect()->toRoute();
                }
            }
        }
        return ['form' => $form];
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function reBuildPasswordAction()
    {
        $id = (int)$this->params('id', null);
        $userEntity = $this->UserTable()->getOneByColumn($id);
        if (!$userEntity) {
            return $this->redirect()->toRoute();
        }
        $form = $this->FormElementManager()->get('XtUser\Form\reBuildPasswordForm');
        $form->bind($userEntity);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $inputFilter = $this->InputFilterManager()->get('XtUser\InputFilter\reBuildPasswordInputFilter');
            $form->setInputFilter($inputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $userEntity->setId($id);
                $userEvent = new UserEvent();
                $userEvent->setUserEntity($userEntity)->setServiceLocator($this->getServiceLocator());
                $eventManager = $this->getEventManager();
                $responseCollection = $eventManager->trigger(UserEvent::REBUILD_PASSWORD_PRE, $this, $userEvent, function ($response) {
                    return $response === false;
                });
                if ($responseCollection->last() !== false) {
                    try {
                        $this->getUserTable()->save($userEntity);
                        $this->succeed = true;
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                    }
                    if ($this->succeed) {
                        return $this->redirect()->toRoute();
                    }
                }
            }
        }
        return ['form' => $form];
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function showAction()
    {
        $id = (int)$this->params('id', null);
        $userEntity = $this->getUserTable()->getUserById($id);
        if (!$userEntity) {
            return $this->redirect()->toRoute();
        }
        return ['user' => $userEntity];
    }

    /**
     * @return array
     */
    public function listAction()
    {
        $page = (int)$this->params('page', 1);
        $users = $this->getUserTable()->getPaginator($page);
        return ['users' => $users];
    }

    /**
     * @return \Zend\Http\Response
     */
    public function deleteAction()
    {
        $id = (int)$this->params('id', null);
        $this->getUserTable()->deleteUserById($id);
        return $this->redirect()->toRoute();
    }

    /**
     * @return \Zend\Http\Response
     */
    public function enabledAction()
    {
        $id = (int)$this->params('id', null);
        $this->getUserTable()->enabledById($id);
        return $this->redirect()->toRoute();
    }

    /**
     * @return \Zend\Http\Response
     */
    public function disabledAction()
    {
        $id = (int)$this->params('id', null);
        $this->getUserTable()->disabledById($id);
        return $this->redirect()->toRoute();
    }

    /**
     * @return array|object
     */
    protected function getUserTable()
    {
        if (!$this->userTable) {
            $this->userTable = $this->getServiceLocator()->get(UserModel::USER_TABLE_CLASS);
        }
        return $this->userTable;

    }
} 