<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-07
 * @Time: 11:08
 * @QQ: 259522
 * @FileName: UserExpandInputFilter.php
 */

namespace XtUser\InputFilter;


use XtUser\Entity\UserEntity;

class UserExpandInputFilter extends UserInputFilter
{
    public function __invoke(UserEntity $userEntity = null)
    {
        parent::__invoke($userEntity);

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

        $this->add([
            'name' => 'last_error_time',
            'filters' => [
                ['name' => 'int']
            ],
        ]);

        $this->add([
            'name' => 'error_count',
            'filters' => [
                ['name' => 'int']
            ],
        ]);

        return $this;

    }
} 