<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-06
 * @Time: 11:17
 * @QQ: 259522
 * @FileName: reBuildPasswordForm.php
 */

namespace XtUser\Form;


class reBuildPasswordForm extends RegisterForm
{
    public function init()
    {
        parent::init();
        $this->setAttribute('class', 'form-horizontal');
        $this->remove('email');

        $this->get('username')->setAttribute('readonly', 'readonly');
        $this->get('submit')->setValue('重置密码');
    }
} 