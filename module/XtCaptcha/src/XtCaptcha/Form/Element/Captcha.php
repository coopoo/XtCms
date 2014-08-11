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

namespace XtCaptcha\Form\Element;


use Traversable;
use XtCaptcha\Form\Captcha\Image;
use Zend\Captcha as ZendCaptcha;
use Zend\Form\Element;
use Zend\Form\Exception;
use Zend\InputFilter\InputProviderInterface;

class Captcha extends Element implements InputProviderInterface
{
    protected $attributes = array(
        'type' => 'captcha',
    );
    /**
     * @var \Zend\Captcha\AdapterInterface
     */
    protected $captcha;

    /**
     * Accepted options for XtCaptcha:
     * - captcha: a valid Zend\XtCaptcha\AdapterInterface
     *
     * @param array|Traversable $options
     * @return Captcha
     */
    public function setOptions($options)
    {
        parent::setOptions($options);
        $captcha = (isset($this->options['captcha'])) ? $this->options['captcha'] : new Image();
        $this->setCaptcha($captcha);
        return $this;
    }

    /**
     * Set captcha
     *
     * @param  ZendCaptcha\AdapterInterface $captcha
     * @throws Exception\InvalidArgumentException
     * @return Captcha
     */
    public function setCaptcha($captcha)
    {
        if (!$captcha instanceof ZendCaptcha\AdapterInterface) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects either a Zend\Captcha\AdapterInterface or specification to pass to Zend\Captcha\Factory; received "%s"',
                __METHOD__,
                (is_object($captcha) ? get_class($captcha) : gettype($captcha))
            ));
        }
        $this->captcha = $captcha;

        return $this;
    }

    /**
     * Retrieve captcha (if any)
     *
     * @return null|ZendCaptcha\AdapterInterface
     */
    public function getCaptcha()
    {
        return $this->captcha;
    }

    /**
     * Provide default input rules for this element
     *
     * Attaches the captcha as a validator.
     *
     * @return array
     */
    public function getInputSpecification()
    {
        $spec = array(
            'name' => $this->getName(),
            'required' => true,
            'filters' => array(
                array('name' => 'Zend\Filter\StringTrim'),
            ),
        );

        // Test that we have a captcha before adding it to the spec
        $captcha = $this->getCaptcha();
        if ($captcha instanceof ZendCaptcha\AdapterInterface) {
            $spec['validators'] = array($captcha);
        }

        return $spec;
    }
}