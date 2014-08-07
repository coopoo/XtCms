<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-07
 * @Time: 9:57
 * @QQ: 259522
 * @FileName: UserExpandForm.php
 */

namespace XtUser\Form;


use XtUser\Model\UserModel;

class UserExpandForm extends UserBaseForm
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('class', 'form-horizontal');
        $this->remove('user_password');

        $this->add([
            'type' => 'text',
            'name' => 'display_name',
            'options' => [
                'label' => '用户昵称'
            ],
            'attributes' => [
                'id' => 'display_name',
                'required' => 'required',
                'placeholder' => '请输入用户昵称',
                'maxlength' => 20
            ],
        ], ['priority' => 99]);

        $this->add([
            'type' => 'text',
            'name' => 'register_time',
            'options' => [
                'label' => '注册时间',
            ],
            'attributes' => [
                'readonly' => 'readonly'
            ]
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'register_ip',
            'options' => [
                'label' => '注册IP',
            ],
            'attributes' => [
                'readonly' => 'readonly'
            ]
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'last_error_time',
            'options' => [
                'label' => '登录错误时间',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'error_count',
            'options' => [
                'label' => '错误次数',
            ],
        ]);

        $this->add([
            'type' => 'Radio',
            'name' => 'status',
            'options' => [
                'label' => '用户状态',
                'value_options' => UserModel::getStatus(),
            ]
        ]);
        $this->get('submit')->setValue('编辑用户');
    }
} 