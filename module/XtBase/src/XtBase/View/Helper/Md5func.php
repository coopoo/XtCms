<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-07
 * @Time: 9:31
 * @QQ: 259522
 * @FileName: Md5func.php
 */

namespace XtBase\View\Helper;


use Zend\View\Helper\AbstractHelper;

class Md5func extends AbstractHelper
{
    public function __invoke()
    {
        echo __FUNCTION__, '<br/>' . time();
    }
} 