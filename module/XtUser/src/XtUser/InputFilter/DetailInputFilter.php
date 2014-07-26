<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 14:03
 * @QQ: 259522
 * @FileName: DetailInputFilter.php
 */

namespace XtUser\InputFilter;


use XtUser\Entity\UserEntity;
use XtUser\Model\UserModel;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\InputFilter\InputFilter;

class DetailInputFilter extends InputFilter
{
    public function __construct(UserEntity $userEntity = null)
    {
        $this->add([
            'name' => 'csrf',
            'required' => true
        ]);

        $this->add([
            'name' => 'real_name',
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
            'name' => 'tel',
            'required' => 'false',
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags']
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'max' => 20
                    ],
                ]
            ]
        ]);

        $this->add([
            'name' => 'mobile',
            'required' => 'false',
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'Int']
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'max' => 12
                    ],
                ]
            ]
        ]);

        $this->add([
            'name' => 'qq',
            'required' => 'false',
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'Int']
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'max' => 12
                    ],
                ]
            ]
        ]);

        $this->add([
            'name' => 'address',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags']
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'max' => 60
                    ],
                ]
            ]
        ]);
    }
}
