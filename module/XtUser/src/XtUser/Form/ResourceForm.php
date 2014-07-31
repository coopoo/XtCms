<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-31
 * @Time: 19:38
 * @QQ: 259522
 * @FileName: ResourceForm.php
 */

namespace XtUser\Form;


use XtUser\Entity\ResourceEntity;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class ResourceForm extends Form
{
    public function __construct()
    {
        parent::__construct('resource_form');

        $this->setHydrator(new ClassMethods())->setObject(new ResourceEntity());
        $this->setAttributes(['role' => 'form', 'class' => 'form-horizontal']);

        $this->add([
            'type' => 'csrf',
            'name' => 'csrf'
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => '资源名称',
                'tips' => '2-20字符',
            ],
            'attributes' => [
                'id' => 'name',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'action',
            'options' => [
                'label' => '资源action',
                'tips' => '2-50字符',
            ],
            'attributes' => [
                'id' => 'name',
                'required' => 'required'
            ]
        ]);

        $this->add([
                'type' => 'submit',
                'name' => 'submit',
                'options' => [
                    'label' => 'operate'
                ],
                'attributes' => [
                    'value' => 'send',
                    'class' => 'btn btn-default'
                ]
            ],
            [
                'priority' => -1000
            ]);

    }
} 