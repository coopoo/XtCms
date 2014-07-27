<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-27
 * @Time: 15:52
 * @QQ: 259522
 * @FileName: ChangePasswordListener.php
 */

namespace XtUser\Listener;


use XtTool\Tool\Tool;
use XtUser\Event\UserEvent;
use XtUser\Model\UserModel;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

/**
 * Class ChangePasswordListener
 * @package XtUser\Listener
 */
class ChangePasswordListener implements ListenerAggregateInterface
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
        $this->listeners[] = $events->getSharedManager()->attach('*', UserEvent::USER_CHANGE_PASSWORD_PRE, [$this, 'checkOldPassword']);
        $this->listeners[] = $events->getSharedManager()->attach('*', UserEvent::USER_CHANGE_PASSWORD_PRE, [$this, 'setUniqid']);
        $this->listeners[] = $events->getSharedManager()->attach('*', UserEvent::USER_CHANGE_PASSWORD_PRE, [$this, 'setPassword']);
    }

    /**
     * @param EventInterface $event
     * @return bool
     */
    public function checkOldPassword(EventInterface $event)
    {
        $oldUserEntity = $event->getUserEntity();
        $serviceLocator = $event->getServiceLocator();
        $userTable = $serviceLocator->get(UserModel::USER_TABLE_CLASS);
        $userEntity = $userTable->getUserById($oldUserEntity->getId());
        return md5($oldUserEntity->getOldPassword() . $userEntity->getUniqid()) === $userEntity->getUserPassword();
    }

    /**
     * @param EventInterface $event
     * @return mixed
     */
    public function setUniqid(EventInterface $event)
    {
        $userEntity = $event->getUserEntity();
        return $userEntity->setUniqid(Tool::getUniqid());
    }

    /**
     * @param EventInterface $event
     * @return mixed
     */
    public function setPassword(EventInterface $event)
    {
        $userEntity = $event->getUserEntity();
        $userEntity->setOldPassword(null);
        return $userEntity->setUserPassword(md5($userEntity->getUserPassword() . $userEntity->getUniqid()));
    }
} 