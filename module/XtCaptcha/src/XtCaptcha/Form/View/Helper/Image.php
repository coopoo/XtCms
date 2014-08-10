<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-08
 * @Time: 10:34
 * @QQ: 259522
 * @FileName: Image.php
 */

namespace XtCaptcha\Form\View\Helper;


use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormRow;

class Image extends FormRow
{
    protected $validTagAttributes = array(
        'name' => true,
        'alt' => true,
        'autofocus' => true,
        'disabled' => true,
        'height' => true,
        'src' => true,
        'type' => true,
        'width' => true,
    );

    protected $horizontalWrap = '%s<div class="form-group %s">%s<div class="%s">%s</div><div class="%s">%s%s</div></div>';

    protected $horizontalLabelClass = 'col-sm-2';
    protected $horizontalInputWrapClass = 'col-sm-4';
    protected $tipsWrapClass = 'col-sm-6';

    protected $hiddenClass = 'hidden';
    protected $errorClass = 'has-error';

    public function render(ElementInterface $element, $labelPosition = null)
    {

        $escapeHtmlHelper = $this->getEscapeHtmlHelper();
        $labelHelper = $this->getLabelHelper();
        $elementHelper = $this->getElementHelper();
        $errorHelper = $this->getElementErrorsHelper();
        $labelHtml = $labelHelper($element);

        $attributes = $element->getAttributes();

        $captcha = (isset($attributes['captcha'])) ? $attributes['captcha'] : new \XtCaptcha\Form\Captcha\Image();

        $imgAttributes = array(
            'width' => $captcha->getWidth(),
            'height' => $captcha->getHeight(),
            'alt' => $captcha->getImgAlt(),
            'src' => $this->getView()->url($captcha->getImgUrl(), ['action' => 'show', 'id' => time()]),
        );
        $closingBracket = $this->getInlineClosingBracket();
        $img = sprintf(
            '<img %s%s',
            $this->createAttributesString($imgAttributes),
            $closingBracket
        );
        $id = (isset($attributes['id'])) ? $attributes['id'] . '-image' : 'captcha-image';

        $imgContainer = '<div class="form-group"><div class="%s" id="%s">%s <span class="form-control-static">看不清(?)点击刷新</span></div></div>';


        // 隐藏域 多个class
        $wrapClass = '';

        $imgContainerHtml = sprintf($imgContainer, 'col-sm-offset-2 col-sm-10', $id, $img);

        $element->setLabelAttributes(array('class' => 'control-label col-sm-2'));//label class
        $inputId = (isset($attributes['id'])) ? $attributes['id'] . '-input' : 'captcha-input';
        $element->setAttributes(array('class' => 'form-control', 'id' => $inputId));

        $errorHtml = $errorHelper($element);

        $tipsHtml = ($element->getOption('tips')) ? '<p class="form-control-static">' . $element->getOption('tips') . '</p>' : null;
        if (!empty($errorHtml)) {
            $wrapClass .= ' ' . $this->errorClass;
            $tipsHtml = '';
        }


        $this->getView()->inlinescript()->captureStart();
        echo '$("#' . $id . '").css("cursor","pointer");';
        echo '$("#' . $id . '").click(function(){
	        $(this).children("img").attr("src","/captcha/show/"+ Math.round(Math.random() * 10000)+".html");
	       });';
        echo '$("#' . $inputId . '").click(function(){
                $(this).val("");
	           });';
        $this->getView()->inlinescript()->captureEnd();

        return sprintf($this->horizontalWrap, $imgContainerHtml, $wrapClass, $labelHelper($element), $this->horizontalInputWrapClass, $elementHelper($element), $this->tipsWrapClass, $errorHtml, $tipsHtml);
    }
} 