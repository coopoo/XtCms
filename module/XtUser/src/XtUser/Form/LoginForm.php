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


use XtUser\InputFilter\LoginInputFilter;

class LoginForm extends UserBaseForm
{
    public function __construct()
    {
        parent::__construct();

        $this->setInputFilter(new LoginInputFilter())->setAttribute('class', 'form-horizontal')->remove('email');
        $this->get('username')->setOption('tips', '');
        $this->get('user_password')->setOption('tips', '');
        $this->get('submit')->setValue('登录网站');

        $this->add([
            'type' => 'checkBox',
            'name' => 'rememberMe',
            'options' => [
                'label' => 'rememberMe',
                'tips' => '两周内免登录'
            ]
        ]);

//        $this->add([
//            'type'=>'captcha',
//            'name'=>'captcha',
//            'options'=>[
//                'label'=>'请输入验证码',
//                'captcha'=>[
//                    'class'=>'image',
//                    'font'=>__DIR__.'/World_Colors.ttf',
//                    'wordlen'=>4
//                ]
//            ]
//        ]);

    }
} 