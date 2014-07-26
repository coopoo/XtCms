<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-24
 * @Time: 11:08
 * @QQ: 259522
 * @FileName: LoginListener.php
 */

namespace XtUser\Listener;


use XtUser\Event\UserEvent;
use XtUser\Model\UserModel;
use Zend\Authentication\Result;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Validator\EmailAddress;

class LoginListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->getSharedManager()->attach('*', UserEvent::USER_LOGIN_PRE, [$this, 'setIdentityKey'], 9999);
        $this->listeners[] = $events->getSharedManager()->attach('*', UserEvent::USER_LOGIN_PRE, [$this, 'checkStatus'], 1000);
        $this->listeners[] = $events->getSharedManager()->attach('*', UserEvent::USER_LOGIN_PRE, [$this, 'checkPasswordErrorCount']);
        $this->listeners[] = $events->getSharedManager()->attach('*', UserEvent::USER_LOGIN_FAIL, array($this, 'setPasswordFailErrorCount'));
        $this->listeners[] = $events->getSharedManager()->attach('*', UserEvent::USER_LOGIN_POST, array($this, 'resetPasswordFailErrorCount'));
        $this->listeners[] = $events->getSharedManager()->attach('*', UserEvent::USER_LOGIN_POST, array($this, 'saveLogger'));
    }

    /**
     * 设置登陆使用email还是username
     * @param EventInterface $event
     */
    public function setIdentityKey(EventInterface $event)
    {
        $userEntity = $event->getUserEntity();
        if ((new EmailAddress())->isValid($userEntity->getUsername())) {
            $event->setIdentityKey('email');
        }

    }

    public function checkPasswordErrorCount(EventInterface $event)
    {
        $userEntity = $event->getUserEntity();
        $serviceLocator = $event->getServiceLocator();
        $userTable = $serviceLocator->get(UserModel::USER_TABLE_CLASS);
        $userModuleOptions = $event->getUserModuleOptions();
        $tableUserEntity = $userTable->getOneByColumn($userEntity->getUserName(), $event->getIdentityKey());
        if ($tableUserEntity && $tableUserEntity->getErrorCount() >= $userModuleOptions->getPasswordFailLimit()) {

            // 计算需要等待时间
            $waitTime = $userModuleOptions->getPasswordFailTime() - (time() - $tableUserEntity->getLastErrorTime());

            // 等待小于0 则重置
            if ($waitTime <= 0) {
                $tableUserEntity->setErrorCount(0);
                $tableUserEntity->setLastErrorTime(0);
                $userTable->save($tableUserEntity);
            } else {
                $event->getForm()->get('username')->setMessages(array(sprintf(UserModel::PASSWORD_FAIL_COUNT_MESSAGE, $userModuleOptions->getPasswordFailLimit(), $waitTime)));
                return false;
            }
        }
    }

    public function checkStatus(EventInterface $event)
    {
        $userEntity = $event->getUserEntity();
        $serviceLocator = $event->getServiceLocator();
        $userTable = $serviceLocator->get(UserModel::USER_TABLE_CLASS);

        $tableUserEntity = $userTable->getOneByColumn($userEntity->getUserName(), $event->getIdentityKey());
        if ($tableUserEntity && (int)$tableUserEntity->getStatus() !== UserModel::ALLOW_STATUS) {
            $event->getForm()->get('username')->setMessages(array(UserModel::NOT_ALLOWED_STATUS_MESSAGE));
            return false;
        }
    }

    /**
     * 设置密码错误登陆次数
     * @param EventInterface $event
     */
    public function setPasswordFailErrorCount(EventInterface $event)
    {
        $code = (int)$event->getLoginFailCode();

        if ($code === ResulT::FAILURE_CREDENTIAL_INVALID) {
            $usernameOrEmail = $event->getUserEntity()->getUsername();
            $serviceLocator = $event->getServiceLocator();
            $userTable = $serviceLocator->get(UserModel::USER_TABLE_CLASS);
            $userEntity = $userTable->getOneByColumn($usernameOrEmail, $event->getIdentityKey());
            $errorCount = (int)$userEntity->getErrorCount();
            $userModuleOptions = $event->getUserModuleOptions();
            if ($errorCount < (int)$userModuleOptions->getPasswordFailLimit()) {
                $userEntity->setErrorCount(++$errorCount);
                $userEntity->setLastErrorTime(time());
                $userTable->save($userEntity);
            }
        }
    }

    public function resetPasswordFailErrorCount(EventInterface $event)
    {
        $userEntity = $event->getUserEntity();
        $userEntity->setErrorCount(0);
        $userEntity->setLastErrorTime(0);

        $serviceLocator = $event->getServiceLocator();
        $userTable = $serviceLocator->get(UserModel::USER_TABLE_CLASS);
        $userTable->save($userEntity);
    }

    public function saveLogger(EventInterface $event)
    {
        $userEntity = $event->getUserEntity();
        $serviceLocator = $event->getServiceLocator();
        $userLoggerTable = $serviceLocator->get(UserModel::USER_LOGGER_TABLE_CLASS);
        $userLoggerTable->save($userEntity);
    }

}