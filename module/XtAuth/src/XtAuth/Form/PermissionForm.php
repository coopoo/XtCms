<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-14
 * @Time: 16:00
 * @QQ: 259522
 * @FileName: PermissionForm.php
 */

namespace XtAuth\Form;


use XtAuth\Entity\PermissionEntity;
use Zend\Stdlib\Hydrator\ClassMethods;

class PermissionForm extends AbstractAuthForm
{

    public function init()
    {
        $this->setName('permission_form');
        $this->setHydrator(new ClassMethods())->setObject(new PermissionEntity());
        $this->setAttributes(['role' => 'form', 'class' => 'form-horizontal']);

        $this->add([
            'type' => 'csrf',
            'name' => 'csrf'
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'resource_id',
            'options' => [
                'label' => '资源名称',
                'value_options' => $this->getResourceTable()->getResourceList(),
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => '权限名称',
                'tips' => '2-20字符'
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