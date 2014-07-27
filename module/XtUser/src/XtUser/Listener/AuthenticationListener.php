<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-28
 * @Time: 5:23
 * @QQ: 259522
 * @FileName: AuthenticationListener.php
 */

namespace XtUser\Listener;


use XtUser\Model\UserModel;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

class AuthenticationListener implements ListenerAggregateInterface
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
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'checkAuth'], -999);
    }

    public function checkAuth(EventInterface $event)
    {
        $response = $event->getResponse();
        $serviceManager = $event->getApplication()->getServiceManager();
        $authentication = $serviceManager->get('XtUser\Service\Authenticate');
        $routeMatch = $event->getRouteMatch();

        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');
        $requestedResourse = $controller . '-' . $action;

        $whiteList = [
            'XtUser\Controller\User-index',
            'XtUser\Controller\User-login',
            'XtUser\Controller\User-disabledLogin',
            'XtUser\Controller\User-register',
            'XtUser\Controller\User-disabledRegister',
        ];
        if (!in_array($requestedResourse, $whiteList)) {
            if (!$authentication->isAlive()) {

                $container = new Container('redirect');
                $container->offsetSet('routeMatch', $routeMatch);
                $router = $event->getRouter();
                $url = $router->assemble(['action' => 'login'], ['name' => UserModel::USER_ROUTE]);
                $response->getHeaders()->addHeaderLine('Location', $url);
                $response->setStatusCode(302);
                return $response;
            }
        }
    }

} 