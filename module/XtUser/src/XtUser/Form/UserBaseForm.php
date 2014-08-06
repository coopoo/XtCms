<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 13:49
 * @QQ: 259522
 * @FileName: UserBaseForm.php
 */

namespace XtUser\Form;


use XtUser\Entity\UserEntity;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class UserBaseForm extends Form
{
    public function __construct()
    {
        parent::__construct('user_form');

        $this->setHydrator(new ClassMethods())->setObject(new UserEntity());

        $this->setAttribute('role', 'form');

        $this->add([
            'type' => 'csrf',
            'name' => 'csrf'
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'username',
            'options' => [
                'label' => '登录帐号',
                'tips' => '2-16位字符'
            ],
            'attributes' => [
                'id' => 'username',
                'required' => 'required',
                'placeholder' => '请输入登录账号',
                'maxlength' => 20
            ],
        ], ['priority' => 99]);

        $this->add([
            'type' => 'password',
            'name' => 'user_password',
            'options' => [
                'label' => '登录密码',
                'tips' => '不少于6位字符'
            ],
            'attributes' => [
                'id' => 'user_password',
                'placeholder' => '请输入登录密码'
            ],
        ], ['priority' => 98]);

        $this->add([
            'type' => 'email',
            'name' => 'email',
            'options' => [
                'label' => '电子邮箱',
                'tips' => '格式:your@domain.com'
            ],
            'attributes' => [
                'id' => 'email',
                'required' => 'required',
                'placeholder' => '请输入电子邮箱'
            ],
        ], ['priority' => 97]);

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