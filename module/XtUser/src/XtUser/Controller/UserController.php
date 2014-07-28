<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 13:07
 * @QQ: 259522
 * @FileName: UserController.php
 */

namespace XtUser\Controller;

use XtUser\Event\UserEvent;
use XtUser\Model\UserModel;
use XtUser\Options\UserModuleOptionsAwareInterFace;
use XtUser\Service\UserModuleOptionsTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\Session\SessionManager;

/**
 * Class UserController
 * @package XtUser\Controller
 */
class UserController extends AbstractActionController implements UserModuleOptionsAwareInterFace
{
    use UserModuleOptionsTrait;

    /**
     *
     */
    public function indexAction()
    {
    }

    /**
     * @return \Zend\Http\Response
     */
    public function disabledLoginAction()
    {
        if (!$this->userModuleOptions->isDisabledLogin()) {
            return $this->redirect()->toRoute(null, ['action' => 'login']);
        }
    }

    /**
     * @return array
     */
    public function loginAction()
    {
        if ($this->userModuleOptions->isDisabledLogin()) {
            return $this->redirect()->toRoute(null, ['action' => 'disabledLogin']);
        }
        $authenticate = $this->Authentication();
        if ($authenticate->isAlive()) {
            return $this->redirect()->toRoute(UserModel::USER_CENTER_ROUTE);
        }
        $form = $this->FormElementManager()->get('XtUser\Form\LoginForm');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $userEntity = $form->getData();
                $userEvent = new UserEvent();
                $userEvent->setUserEntity($userEntity)->setForm($form)->setServiceLocator($this->getServiceLocator())->setUserModuleOptions($this->userModuleOptions);
                $eventManager = $this->getEventManager();
                $responseCollection = $eventManager->trigger(UserEvent::USER_LOGIN_PRE, $this, $userEvent, function ($response) {
                    return $response === false;
                });
                if ($responseCollection->last() !== false) {
                    $authenticate->setUserEvent($userEvent);
                    if ($userEntity->getRememberMe() === 1) {
                        $authenticate->getStorage()->getSessionManager()->rememberMe();
                    }
                    if ($authenticate->isValid()) {
                        $userEvent->setUserEntity($authenticate->getUserEntity());
                        $eventManager->trigger(UserEvent::USER_LOGIN_POST, $this, $userEvent);
                        $container = new Container('redirect');
                        $routeMatch = $container->offsetGet('routeMatch');
                        if (!$routeMatch) {
                            return $this->redirect()->toRoute('home');
                        }
                        $container->offsetSet('routeMatch', null);
                        return $this->redirect()->toRoute($routeMatch->getMatchedRouteName(), $routeMatch->getParams());
                    }

                    $element = $form->get('username');
                    $element->setMessages(array_merge($element->getMessages(), $authenticate->getResult()->getMessages()));

                    // 设置登陆错误号
                    $userEvent->setLoginFailCode($authenticate->getResult()->getCode());
                    $eventManager->trigger(UserEvent::USER_LOGIN_FAIL, $this, $userEvent);
                }
            }
        }

        return ['form' => $form];
    }

    /**
     * @return \Zend\Http\Response
     */
    public function disabledRegisterAction()
    {
        if (!$this->userModuleOptions->isDisabledRegister()) {
            return $this->redirect()->toRoute(null, ['action' => 'register']);
        }
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function registerAction()
    {
        if ($this->userModuleOptions->isDisabledRegister()) {
            return $this->redirect()->toRoute(null, ['action' => 'disabledRegister']);
        }
        $form = $this->FormElementManager()->get('XtUser\Form\RegisterForm');
        $request = $this->getRequest();
        if ($request->isPost()) {
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
                        $this->UserTable()->save($userEntity);
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                        return ['form' => $form];
                    }
                    return $this->redirect()->toRoute(null, ['action' => 'login']);
                }
            }
        }
        return ['form' => $form];
    }

    public function findPasswordAction()
    {

    }

    /**
     * @return \Zend\Http\Response
     */
    public function logoutAction()
    {
        if ($this->Authentication()->hasIdentity()) {
            $this->Authentication()->clearIdentity();
        }
        return $this->redirect()->toRoute(null, ['action' => 'login']);
    }

} 