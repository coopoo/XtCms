<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-07
 * @Time: 13:00
 * @QQ: 259522
 * @FileName: Status.php
 */

namespace XtBase\View\Helper;


use Zend\View\Helper\AbstractHelper;

class Status extends AbstractHelper
{
    const ACTIVE = 'Y';
    const INACTIVE = 'N';
    protected $statusArray = [
        'Y' => ['正常', '禁用', 'disabled'],
        'N' => ['禁用', '启用', 'enabled']
    ];
    protected $button = '<a class="btn btn-default">%s</a> <a href="%s" class="btn btn-info">点击%s</a>';

    public function __invoke($status = null, $name = null, $params = [])
    {
        if (null === $name) {
            $name = 'home';
        }
        $helperPluginManager = $this->getView()->getHelperPluginManager();
        $router = $helperPluginManager->getServiceLocator()->get('Application')->getMvcEvent()->getRouter();
        if (!empty($params)) {
            $params = array_merge(['action' => $this->statusArray[$status][2]], $params);
        }
        $url = $router->assemble($params, ['name' => $name]);
        if ($status !== static::ACTIVE) {
            $status = static::INACTIVE;
        }
        return sprintf(
            $this->button,
            $this->statusArray[$status][0],
            $url,
            $this->statusArray[$status][1]
        );
    }
} 