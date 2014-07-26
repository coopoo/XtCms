<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-23
 * @Time: 13:05
 * @QQ: 259522
 * @FileName: UserCenterController.php
 */

namespace XtUser\Controller;

use XtUser\Entity\UserDetailEntity;
use XtUser\Model\UserDetailTable;
use XtUser\InputFilter\EditInputFilter;
use XtUser\Model\UserModel;
use Zend\Mvc\Controller\AbstractActionController;

class UserCenterController extends AbstractActionController
{
    /**
     * @var UserDetailTable
     */
    protected $userDetailTable;

    public function indexAction()
    {
        //$user = $this->UserTable()->getUserById($this->getLoginUserId());
        $this->UserTable()->deleteUserById(2);

        $userLogger = $this->UserTable()->getUserLogger($this->Authentication()->getUserId());
        return ['userLogger' => $userLogger];
    }

    public function editAction()
    {
        $form = $this->FormElementManager()->get('XtUser\Form\EditForm');
        $userEntity = $this->UserTable()->getOneByColumn($this->Authentication()->getUserId());
        $form->bind($userEntity);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->setInputFilter(new EditInputFilter($userEntity));
            if ($form->isValid()) {
                try {
                    $this->UserTable()->save($userEntity);
                } catch (\Exception $e) {
                    return ['form' => $form];
                }
                return $this->redirect()->toRoute(null, ['action' => 'edit']);
            }
        }
        return ['form' => $form];
    }

    public function detailAction()
    {
        $userId = $this->Authentication()->getUserId();
        $form = $this->FormElementManager()->get('XtUser\Form\DetailForm');
        $userDetail = ($this->getUserDetailTable()->getOneByColumn($userId)) ?: new UserDetailEntity();
        $form->bind($userDetail);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $userDetail->setUserId($userId);
                try {
                    $this->getUserDetailTable()->save($userDetail);
                } catch (\Exception $e) {
                    echo $e->getMessage();
                    return ['form' => $form];
                }
                return $this->redirect()->toRoute();
            }
        }
        return ['form' => $form];
    }

    public function changePasswordAction()
    {
        $userId = $this->Authentication()->getUserId();
        $form = $this->FormElementManager()->get('XtUser\Form\ChangePasswordForm');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isVolid()) {
                var_dump($form->getData());
            } else {
                var_dump($form->getMessages());
            }
        }
        return ['form' => $form];
    }

    protected function getUserDetailTable()
    {
        if (!$this->userDetailTable) {
            $this->userDetailTable = $this->getServiceLocator()->get(UserModel::USER_DETAIL_TABLE_CLASS);
        }
        return $this->userDetailTable;
    }
} 