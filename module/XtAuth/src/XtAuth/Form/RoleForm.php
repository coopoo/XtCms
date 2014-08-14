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

namespace XtAuth\Form;


use XtAuth\Entity\RoleEntity;
use XtUser\Model\UserModel;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class RoleForm extends Form
{

    public function init()
    {
        $this->setName('role_form');
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
            'attributes' => [
                'id' => 'name',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'Radio',
            'name' => 'status',
            'options' => [
                'label' => '角色状态',
                'value_options' => UserModel::getStatus()
            ],
            'attributes' => [
                'value' => UserModel::DEFAULT_STATUS
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