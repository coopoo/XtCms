<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-30
 * @Time: 14:15
 * @QQ: 259522
 * @FileName: GlobalAdapterListener.php
 */

namespace XtBase\Listener;


use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class GlobalAdapterListener implements ListenerAggregateInterface, ServiceLocatorAwareInterface
{
    use ListenerAggregateTrait;
    use ServiceLocatorAwareTrait;

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
        $this->listeners[] = $events->getSharedManager()->attach('Zend\Mvc\Application', MvcEvent::EVENT_ROUTE, [$this, 'setGlobalAdapter'], 100);
    }

    public function setGlobalAdapter(EventInterface $event)
    {
        $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        GlobalAdapterFeature::setStaticAdapter($adapter);
    }

} 