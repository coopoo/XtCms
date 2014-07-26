<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 13:59
 * @QQ: 259522
 * @FileName: RegisterForm.php
 */

namespace XtUser\Form;


use XtUser\InputFilter\RegisterInputFilter;

class RegisterForm extends UserBaseForm
{
    public function __construct()
    {
        parent::__construct();
        $this->setInputFilter(new RegisterInputFilter())->setAttribute('class', 'form-horizontal');
        $this->add([
            'type' => 'password',
            'name' => 'confirm_password',
            'options' => [
                'label' => '确认密码'
            ],
            'attributes' => [
                'id' => 'confirm_password',
                'required' => 'required',
                'placeholder' => '请再次输入登录密码'
            ],
        ], ['priority' => 98]);

        $this->get('submit')->setValue('注册帐号');
    }
} 