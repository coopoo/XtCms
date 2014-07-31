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
use XtUser\Event\UserEvent;
use XtUser\Model\UserDetailTable;
use XtUser\Model\UserModel;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class UserCenterController
 * @package XtUser\Controller
 */
class UserCenterController extends AbstractActionController
{
    /**
     * @var UserDetailTable
     */
    protected $userDetailTable;

    /**
     * @return array
     */
    public function indexAction()
    {
        //$user = $this->UserTable()->getUserById($this->getLoginUserId());
        $this->UserTable()->deleteUserById(2);

        $userLogger = $this->UserTable()->getUserLogger($this->Authentication()->getUserId());
        return ['userLogger' => $userLogger];
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function editAction()
    {
        $form = $this->FormElementManager()->get('XtUser\Form\EditForm');
        $userEntity = $this->UserTable()->getOneByColumn($this->Authentication()->getUserId());
        $form->bind($userEntity);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $inputFilter = $this->InputFilterManager()->get('XtUser\InputFilter\EditInputFilter');
            $form->setInputFilter($inputFilter($userEntity));
            $form->setData($request->getPost());
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

    /**
     * @return array|\Zend\Http\Response
     */
    public function detailAction()
    {
        $userId = $this->Authentication()->getUserId();
        $form = $this->FormElementManager()->get('XtUser\Form\DetailForm');
        $userDetail = ($this->getUserDetailTable()->getOneByColumn($userId)) ?: new UserDetailEntity();
        $form->bind($userDetail);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $inputFilter = $this->InputFilterManager()->get('XtUser\InputFilter\DetailInputFilter');
            $form->setInputFilter($inputFilter());
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

    /**
     * @return array|\Zend\Http\Response
     */
    public function changePasswordAction()
    {
        $userId = $this->Authentication()->getUserId();
        $form = $this->FormElementManager()->get('XtUser\Form\ChangePasswordForm');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $inputFilter = $this->InputFilterManager()->get('XtUser\InputFilter\ChangePasswordInputFilter');
            $form->setInputFilter($inputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $userEntity = $form->getData();
                $userEntity->setId($userId);
                $userEvent = new UserEvent();
                $userEvent->setUserEntity($userEntity)->setServiceLocator($this->getServiceLocator());
                $eventManager = $this->getEventManager();
                $responseCollection = $eventManager->trigger(UserEvent::USER_CHANGE_PASSWORD_PRE, $this, $userEvent, function ($response) {
                    return $response === false;
                });
                if ($responseCollection->last() !== false) {
                    try {
                        $this->UserTable()->save($userEntity);
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                        return ['form' => $form];
                    }
                    return $this->redirect()->toRoute();
                }
            }
        }
        return ['form' => $form];
    }

    /**
     * @return UserDetailTable
     */
    protected function getUserDetailTable()
    {
        if (!$this->userDetailTable) {
            $this->userDetailTable = $this->getServiceLocator()->get(UserModel::USER_DETAIL_TABLE_CLASS);
        }
        return $this->userDetailTable;
    }
} 