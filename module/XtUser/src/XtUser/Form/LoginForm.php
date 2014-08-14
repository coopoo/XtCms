<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 13:58
 * @QQ: 259522
 * @FileName: LoginForm.php
 */

namespace XtUser\Form;


class LoginForm extends UserBaseForm
{
    public function init()
    {
        parent::init();
        $this->setAttribute('class', 'form-horizontal')->remove('email');
        $this->get('username')->setOption('tips', '');
        $this->get('user_password')->setOption('tips', '');
        $this->get('submit')->setValue('登录网站');


        $this->add([
            'type' => 'XtCaptcha',
            'name' => 'captcha',
            'options' => [
                'label' => '请输入验证码',
            ]
        ]);

        $this->add([
            'type' => 'checkBox',
            'name' => 'rememberMe',
            'options' => [
                'label' => '自动登录',
                'tips' => '两周内自动登录'
            ]
        ]);
    }
} 