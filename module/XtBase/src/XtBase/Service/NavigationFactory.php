<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-25
 * @Time: 16:03
 * @QQ: 259522
 * @FileName: NavigationFactory.php
 */

namespace XtBase\Service;


use Zend\Navigation\Service\AbstractNavigationFactory;

/**
 * Class NavigationFactory
 * @package XtBase\Service
 */
class NavigationFactory extends AbstractNavigationFactory
{
    /**
     * @var
     */
    protected $name = 'default';

    /**
     * @return mixed
     */
    protected function getName()
    {
        return $this->name;
    }

    /**
     * @param null $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
} 