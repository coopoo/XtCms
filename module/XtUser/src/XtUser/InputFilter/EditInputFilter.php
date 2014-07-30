<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-23
 * @Time: 13:00
 * @QQ: 259522
 * @FileName: EditInputFilter.php
 */

namespace XtUser\InputFilter;


use XtUser\Entity\UserEntity;

class EditInputFilter extends UserInputFilter
{

    public function __invoke(UserEntity $userEntity = null)
    {
        parent::__invoke($userEntity);
        $this->remove('user_password')->remove('username');
        $this->add([
            'name' => 'display_name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags']
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 2,
                        'max' => 20
                    ],
                ]
            ]
        ]);
        return $this;
    }
} 