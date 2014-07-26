<?php
/**
 * @Created by PhpStorm.
 * @Project: xt
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-06-12
 * @Time: 18:37
 * @QQ: 259522
 * @FileName: BootstrapForm.php
 */

namespace XtBootstrap\Form\View\Helper;


use Zend\Form\FieldsetInterface;
use Zend\Form\FormInterface;
use Zend\Form\View\Helper\Form;

class BootstrapForm extends Form
{
    public function render(FormInterface $form)
    {
        if (method_exists($form, 'prepare')) {
            $form->prepare();
        }

        $formContent = '';

        foreach ($form as $element) {
            if ($element instanceof FieldsetInterface) {
                $formContent .= $this->getView()->formCollection($element);
            } else {
                $element->setOption('_form', $form);
                $formContent .= $this->getView()->XtBootstrapFormRow($element);
            }
        }

        return $this->openTag($form) . $formContent . $this->closeTag();
    }
} 