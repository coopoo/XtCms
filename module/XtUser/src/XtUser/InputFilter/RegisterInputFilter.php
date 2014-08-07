<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 14:21
 * @QQ: 259522
 * @FileName: RegisterInputFilter.php
 */

namespace XtUser\InputFilter;


use XtUser\Entity\UserEntity;

class RegisterInputFilter extends UserInputFilter
{
    public function __invoke(UserEntity $userEntity = null)
    {
        parent::__invoke($userEntity);

        $this->get('user_password')->setRequired(true);

        $this->add([
            'name' => 'confirm_password',
            'required' => true,
            'filters' => [
                ['name' => 'stringTrim'],
                ['name' => 'StripTags']
            ],
            'validators' => [
                [
                    'name' => 'string_length',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 6
                    ],
                ],
                [
                    'name' => 'identical',
                    'options' => [
                        'token' => 'user_password'
                    ]
                ]
            ]
        ]);
        return $this;
    }
} 