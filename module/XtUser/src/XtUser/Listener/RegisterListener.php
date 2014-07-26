<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 16:31
 * @QQ: 259522
 * @FileName: RegisterListener.php
 */

namespace XtUser\Listener;


use XtTool\Tool\IpAddress;
use XtTool\Tool\Tool;
use XtUser\Event\UserEvent;
use XtUser\Model\UserModel;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

class RegisterListener implements ListenerAggregateInterface
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
        $this->listeners[] = $events->getSharedManager()->attach('*', UserEvent::USER_REGISTER_PRE, [$this, 'checkUsername']);
        $this->listeners[] = $events->getSharedManager()->attach('*', UserEvent::USER_REGISTER_PRE, [$this, 'setDisplayName']);
        $this->listeners[] = $events->getSharedManager()->attach('*', UserEvent::USER_REGISTER_PRE, [$this, 'setRegisterTime']);
        $this->listeners[] = $events->getSharedManager()->attach('*', UserEvent::USER_REGISTER_PRE, [$this, 'setRegisterIp']);
        $this->listeners[] = $events->getSharedManager()->attach('*', UserEvent::USER_REGISTER_PRE, [$this, 'setStatus']);
        $this->listeners[] = $events->getSharedManager()->attach('*', UserEvent::USER_REGISTER_PRE, [$this, 'setUniqid']);
        $this->listeners[] = $events->getSharedManager()->attach('*', UserEvent::USER_REGISTER_PRE, [$this, 'setPassword']);
    }

    public function checkUsername(EventInterface $event)
    {
        $userEntity = $event->getUserEntity();
        return true;
    }

    public function setDisplayName(EventInterface $event)
    {
        $userEntity = $event->getUserEntity();
        return $userEntity->setDisplayName($userEntity->getUsername());
    }

    public function setRegisterTime(EventInterface $event)
    {
        $userEntity = $event->getUserEntity();
        return $userEntity->setRegistertime(time());
    }

    public function setRegisterIp(EventInterface $event)
    {
        $userEntity = $event->getUserEntity();
        return $userEntity->setRegisterip(IpAddress::getIp());
    }

    public function setStatus(EventInterface $event)
    {
        $userEntity = $event->getUserEntity();
        return $userEntity->setStatus(UserModel::DEFAULT_STATUS);
    }

    public function setUniqid(EventInterface $event)
    {
        $userEntity = $event->getUserEntity();
        return $userEntity->setUniqid(Tool::getUniqid());
    }

    public function setPassword(EventInterface $event)
    {
        $userEntity = $event->getUserEntity();
        return $userEntity->setUserPassword(md5($userEntity->getUserPassword() . $userEntity->getUniqid()));
    }
} 