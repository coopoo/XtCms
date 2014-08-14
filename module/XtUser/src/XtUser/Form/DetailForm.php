<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-24
 * @Time: 16:06
 * @QQ: 259522
 * @FileName: DetailForm.php
 */

namespace XtUser\Form;


use XtUser\Entity\UserDetailEntity;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class DetailForm extends Form
{
    public function init()
    {
        $this->setName('user_detail');
        $this->setHydrator(new ClassMethods())->setObject(new UserDetailEntity());
        $this->setAttributes(['role' => 'form', 'class' => 'form-horizontal']);
        $this->add([
            'type' => 'csrf',
            'name' => 'csrf'
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'real_name',
            'options' => [
                'label' => '真实姓名',
                'tips' => '2-16位字符'
            ],
            'attributes' => [
                'id' => 'real_name',
                'required' => 'required',
                'placeholder' => '请输入真实姓名',
                'maxlength' => 16
            ],
        ], ['priority' => 99]);

        $this->add([
            'type' => 'text',
            'name' => 'tel',
            'options' => [
                'label' => '联系电话',
                'tips' => '固定电话'
            ],
            'attributes' => [
                'id' => 'tel',
                'placeholder' => '请输入固定电话',
            ],
        ], ['priority' => 98]);

        $this->add([
            'type' => 'text',
            'name' => 'mobile',
            'options' => [
                'label' => '手机号码',
                'tips' => '手机号码'
            ],
            'attributes' => [
                'id' => 'mobile',
                'placeholder' => '请输入手机号码',
            ],
        ], ['priority' => 98]);


        $this->add([
            'type' => 'text',
            'name' => 'qq',
            'options' => [
                'label' => '腾讯QQ',
                'tips' => '5-12位QQ号码'
            ],
            'attributes' => [
                'id' => 'qq',
                'placeholder' => '请输入腾讯QQ号码',
            ],
        ], ['priority' => 98]);

        $this->add([
            'type' => 'text',
            'name' => 'address',
            'options' => [
                'label' => '联系地址',
                'tips' => '联系地址'
            ],
            'attributes' => [
                'id' => 'address',
                'placeholder' => '请输入联系地址',
            ],
        ], ['priority' => 98]);

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