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
            'name' => 'status',
            'type' => 'Select',
            'options' => [
                'label' => '用户状态',
                'empty_option' => '请选择用户状态',
                'value_options' => UserModel::getStatus(),
            ]
        ]);

    }
} 