<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-25
 * @Time: 15:59
 * @QQ: 259522
 * @FileName: ChangePasswordInputFilter.php
 */

namespace XtUser\InputFilter;


use XtUser\Entity\UserEntity;
use XtUser\Model\UserModel;

class ChangePasswordInputFilter extends RegisterInputFilter
{
    public function __invoke(UserEntity $userEntity = null)
    {
        parent::__invoke($userEntity);
        $this->remove('username')->remove('email');
        $this->add([
            'name' => 'old_password',
            'required' => 'false',
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                [
                    'name' => 'CallBack',
                    'options' => [
                        'callback' => function ($password) {
                            if (!empty($password)) {
                                return UserModel::encryption($password);
                            }
                            return null;
                        }
                    ],
                ]
            ]
        ]);
        return $this;
    }
} 