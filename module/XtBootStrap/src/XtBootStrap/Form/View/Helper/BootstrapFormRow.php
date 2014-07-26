<?php
/**
 * @Created by PhpStorm.
 * @Project: xt
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-06-12
 * @Time: 18:42
 * @QQ: 259522
 * @FileName: BootstrapFormRow.php
 */

namespace XtBootstrap\Form\View\Helper;


use Zend\Form\ElementInterface;
use Zend\Form\Form;
use Zend\Form\FormInterface;
use Zend\Form\View\Helper\FormElementErrors;
use Zend\Form\View\Helper\FormRow;

class BootstrapFormRow extends FormRow
{
    protected $formAllowClass = [
        'form-horizontal',
        'form-inline'
    ];
    protected $defaultWrap = '<div class="form-group %s">%s%s%s</div>';
    protected $horizontalWrapHtml = '<div class="form-group %s">%s<div class="%s">%s %s</div></div>';

    protected $tipsWrapHtml = '<p class="help-block">%s</p>';

    protected $horizontalLabelClass = 'col-sm-2';
    protected $horizontalInputWrapClass = 'col-sm-10';

    protected $hiddenClass = 'hidden';
    protected $errorClass = 'has-error';

    public function render(ElementInterface $element, $labelPosition = null)
    {
        $escapeHtmlHelper = $this->getEscapeHtmlHelper();
        $labelHelper = $this->getLabelHelper();
        $elementHelper = $this->getElementHelper();
        $elementErrorsHelper = $this->getElementErrorsHelper();

        $label = $element->getLabel();
        $inputErrorClass = $this->getInputErrorClass();

        if (is_null($labelPosition)) {
            $labelPosition = $this->labelPosition;
        }

        if (isset($label) && '' !== $label) {
            // Translate the label
            if (null !== ($translator = $this->getTranslator())) {
                $label = $translator->translate(
                    $label, $this->getTranslatorTextDomain()
                );
            }
        }

        //隐藏元素
        if ($element->getAttribute('type') == $this->hiddenClass) {
            return $elementHelper->render($element);
        }

        // 获取form 不存在 则创建个空的
        if (!($form = $element->getOption('_form'))) {
            $form = new Form();
        }

        /**
         * @除submit 以外的元素增加class
         */

        if (!in_array($element->getAttribute('type'), ['submit'])) {
            $this->addElementClass($element, ['form-control']);
        } else {
            $this->addLabelClass($element, ['sr-only']);
        }

        $wrapClass = '';

        switch ($this->getFormClass($form)) {
            case 'form-horizontal':
                $this->setHorizontalClass($form);
                $this->addLabelClass($element, ['control-label', $this->horizontalLabelClass]);
                $errorHtml = $elementErrorsHelper($element);
                $labelWrapHtml = $labelHelper($element);
                if (!empty($errorHtml)) {
                    $wrapClass .= ' ' . $this->errorClass;
                }
                $tipsWrapHtml = ($element->getOption('tips')) ? sprintf($this->tipsWrapHtml, $element->getOption('tips')) : '';
                $errorHtml = ($errorHtml) ?: $tipsWrapHtml;
                return sprintf($this->horizontalWrapHtml, $wrapClass, $labelWrapHtml,
                    $this->horizontalInputWrapClass, $elementHelper($element), $errorHtml) . PHP_EOL;
            case 'form-inline':
                $this->addLabelClass($element, array('sr-only'));
                $errorHtml = $elementErrorsHelper($element);
                if (!empty($errorHtml)) {
                    $wrapClass .= ' ' . $this->errorClass;
                }

                return sprintf($this->defaultWrap, $wrapClass, $labelHelper($element), $elementHelper($element), $errorHtml);

            default:
                $errorHtml = $elementErrorsHelper($element);
                if (!empty($errorHtml)) {
                    $wrapClass .= ' ' . $this->errorClass;
                }
                return sprintf($this->defaultWrap, $wrapClass, $labelHelper($element), $elementHelper($element), $errorHtml);
        }
    }

    protected function setHorizontalClass(FormInterface $form)
    {
        if ($labelClass = $form->getAttribute('horizontalLabelClass')) {
            $this->horizontalLabelClass = $labelClass;
        }

        if ($inputWrapClass = $form->getAttribute('horizontalInputWrapClass')) {
            $this->horizontalInputWrapClass = $inputWrapClass;
        }
    }

    /**
     * @param ElementInterface $element
     * @param $classList
     */
    protected function addElementClass(ElementInterface $element, $classList)
    {

        $attributes = $element->getAttributes();

        if (isset($attributes['class'])) {
            $attributes['class'] = implode(' ', array_unique(array_merge(explode(' ', $attributes['class']), $classList)));
        } else {
            $attributes['class'] = implode(' ', $classList);
        }

        $element->setAttributes($attributes);

    }

    /**
     * @param ElementInterface $element
     * @param $classList
     */
    protected function addLabelClass(ElementInterface $element, $classList)
    {
        $labelAttributes = $element->getLabelAttributes();

        if (isset($labelAttributes['class'])) {
            $labelAttributes['class'] = implode(' ', array_unique(array_merge(explode(' ', $labelAttributes['class']), $classList)));
        } else {
            $labelAttributes['class'] = implode(' ', $classList);
        }

        $element->setLabelAttributes($labelAttributes);
    }

    /**
     * @param FormInterface $form
     * @return mixed|string
     */
    protected function getFormClass(FormInterface $form)
    {
        $class = $form->getAttribute('class');

        if ($class) {
            $classList = explode(' ', $class);
            $intersectClass = array_intersect($classList, $this->formAllowClass);
            if (!empty($intersectClass)) {
                return array_pop($intersectClass);
            }
        }
        return 'default';
    }

    /**
     * @return BootstrapFormElementErrors|FormElementErrors
     */
    protected function getElementErrorsHelper()
    {
        if ($this->elementErrorsHelper) {
            return $this->elementErrorsHelper;
        }

        if (method_exists($this->view, 'plugin')) {
            $this->elementErrorsHelper = $this->view->plugin('XtBootstrapFormElementErrors');
        }

        if (!$this->elementErrorsHelper instanceof FormElementErrors) {
            $this->elementErrorsHelper = new BootstrapFormElementErrors();
        }

        return $this->elementErrorsHelper;
    }

} 