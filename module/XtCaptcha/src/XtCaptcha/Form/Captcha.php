<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-11
 * @Time: 0:54
 * @QQ: 259522
 * @FileName: Captcha.php
 */

namespace XtCaptcha\Form;


use XtCaptcha\Form\Captcha\Image;
use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\ValidatorInterface;

class Captcha extends Element implements InputProviderInterface
{
    protected $attributes = array(
        'type' => 'captcha',
    );

    protected $validator;

    /**
     * @return mixed
     */
    public function getValidator()
    {
        if (null === $this->validator) {
            $this->setValidator(new Image());
        }
        return $this->validator;
    }

    /**
     * @param mixed $validator
     *
     * @return $this;
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInput()}.
     *
     * @return array
     */
    public function getInputSpecification()
    {
        return array(
            'name' => $this->getName(),
            'required' => true,
            'filters' => array(
                array('name' => 'Zend\Filter\StringTrim'),
            ),
            'validators' => array(
                $this->getValidator(),
            ),
        );
    }

} 