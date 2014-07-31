<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-25
 * @Time: 8:31
 * @QQ: 259522
 * @FileName: ChangePasswordForm.php
 */

namespace XtUser\Form;

class ChangePasswordForm extends RegisterForm
{

    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('class', 'form-horizontal');
        $this->remove('username')->remove('email');
        $this->add([
            'type' => 'password',
            'name' => 'old_password',
            'options' => [
                'label' => '旧密码'
            ],
            'attributes' => [
                'id' => 'old_password',
                'required' => 'required',
                'placeholder' => '请输入旧密码'
            ],
        ], ['priority' => 99]);
        $this->get('submit')->setValue('修改密码');
    }
}