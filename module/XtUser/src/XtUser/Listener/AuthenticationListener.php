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
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Session\Container;

class AuthenticationListener implements ListenerAggregateInterface,
    ServiceLocatorAwareInterface
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
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'checkAuth'], -100);
    }

    public function checkAuth(EventInterface $event)
    {
        $routeMatch = $event->getRouteMatch();
        if (!$routeMatch instanceof RouteMatch) {
            return;
        }
        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');
        $requestedResource = $controller . '-' . $action;
        $whiteList = [
            'XtUser\Controller\User-disabledLogin',
            'XtUser\Controller\User-register',
            'XtUser\Controller\User-disabledRegister'
        ];
        if (in_array($requestedResource, $whiteList)) {
            return;
        }
        $response = $event->getResponse();
        $response->setStatusCode(302);
        $authentication = $this->getServiceLocator()->get('XtUser\Service\Authenticate');
        $router = $event->getRouter();
        if ($requestedResource === 'XtUser\Controller\User-login') {
            if (!$authentication->isAlive()) {
                return;
            }
            $url = $router->assemble([], ['name' => UserModel::USER_ROUTE]);
            $response->getHeaders()->addHeaderLine('Location', $url);
            return $response;
        }
        if ($authentication->isAlive()) {
            return;
        }
        $container = new Container('redirect');
        $container->offsetSet('routeMatch', $routeMatch);
        $url = $router->assemble(['action' => 'login'], ['name' => UserModel::USER_ROUTE]);
        $response->getHeaders()->addHeaderLine('Location', $url);
        return $response;
    }

} 