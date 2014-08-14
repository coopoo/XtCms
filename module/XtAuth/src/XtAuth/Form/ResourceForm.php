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

namespace XtAuth\Form;


use XtAuth\Entity\ResourceEntity;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class ResourceForm extends Form
{
    public function init()
    {
        $this->setName('resource_form');
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
                'tips' => '2-30字符',
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
                'tips' => '2-100字符',
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