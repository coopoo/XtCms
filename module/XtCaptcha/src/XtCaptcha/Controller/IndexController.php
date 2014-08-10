<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-08
 * @Time: 10:34
 * @QQ: 259522
 * @FileName: IndexController.php
 */

namespace XtCaptcha\Controller;


use XtCaptcha\Form\Captcha\Image;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function showAction()
    {
        $image = new Image();
        $image->generate();
        exit;
    }
} 