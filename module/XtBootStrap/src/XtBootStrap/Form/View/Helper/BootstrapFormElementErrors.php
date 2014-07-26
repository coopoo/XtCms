<?php
/**
 * @Created by PhpStorm.
 * @Project: xt
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-06-12
 * @Time: 18:43
 * @QQ: 259522
 * @FileName: BootstrapFormElementErrors.php
 */

namespace XtBootstrap\Form\View\Helper;


use Zend\Form\View\Helper\FormElementErrors;

class BootstrapFormElementErrors extends FormElementErrors
{
    /**@+
     * @var string Templates for the open/close/separators for message tags
     */
    protected $messageCloseString = '</p>';
    protected $messageOpenFormat = '<p class="help-block">';
    protected $messageSeparatorString = ',';
} 