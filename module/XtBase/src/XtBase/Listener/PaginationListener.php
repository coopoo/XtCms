<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-29
 * @Time: 10:49
 * @QQ: 259522
 * @FileName: PaginationListener.php
 */

namespace XtBase\Listener;


use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mvc\MvcEvent;
use Zend\Paginator\Paginator;
use Zend\View\Helper\PaginationControl;

class PaginationListener implements ListenerAggregateInterface
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
        $this->listeners[] = $events->getSharedManager()->attach('*', MvcEvent::EVENT_DISPATCH, [$this, 'setPagination']);
    }

    public function setPagination(EventInterface $event)
    {
        Paginator::setDefaultItemCountPerPage(15);
        PaginationControl::setDefaultScrollingStyle('Sliding');
        PaginationControl::setDefaultViewPartial('pagination/pagination.phtml');
    }

} 