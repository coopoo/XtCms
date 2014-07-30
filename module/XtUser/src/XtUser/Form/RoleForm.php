<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-28
 * @Time: 14:28
 * @QQ: 259522
 * @FileName: RoleForm.php
 */

namespace XtUser\Form;


use XtUser\Entity\RoleEntity;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class RoleForm extends Form
{

    public function __construct()
    {
        parent::__construct('role_form');
        $this->setHydrator(new ClassMethods())->setObject(new RoleEntity());
        $this->setAttributes(['role' => 'form', 'class' => 'form-horizontal']);

        $this->add([
            'type' => 'csrf',
            'name' => 'csrf'
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => '角色名称',
                'tips' => '2-20字符',
            ],
        ]);

        $this->add([
            'type' => 'Radio',
            'name' => 'status',
            'options' => [
                'label' => '角色状态',
                'value_options' => [
                    0 => '禁用',
                    99 => '启用'
                ]
            ],
            'attributes' => [
                'value' => 99
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