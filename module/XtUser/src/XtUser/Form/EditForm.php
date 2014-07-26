<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-23
 * @Time: 12:52
 * @QQ: 259522
 * @FileName: EditForm.php
 */

namespace XtUser\Form;


class EditForm extends UserBaseForm
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
        $this->get('username')->setAttribute('readonly', 'readonly');
        $this->get('submit')->setValue('修改信息');
    }
} 