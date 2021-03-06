<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 14:03
 * @QQ: 259522
 * @FileName: UserInputFilter.php
 */

namespace XtUser\InputFilter;


use XtUser\Entity\UserEntity;
use XtUser\Model\UserModel;
use XtUser\Options\UserModuleOptionsAwareInterFace;
use XtUser\Service\UserModuleOptionsTrait;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\InputFilter\InputFilter;

class UserInputFilter extends InputFilter implements UserModuleOptionsAwareInterFace
{
    use UserModuleOptionsTrait;

    public function __invoke(UserEntity $userEntity = null)
    {
        $this->add([
            'name' => 'csrf',
            'required' => true
        ]);

        $this->add([
            'name' => 'username',
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
                ],
                [
                    'name' => 'Db\NoRecordExists',
                    'options' => [
                        'table' => $this->userModuleOptions->getUserTable(),
                        'field' => 'username',
                        'adapter' => GlobalAdapterFeature::getStaticAdapter(),
                        'exclude' => [
                            'field' => 'id',
                            'value' => $userEntity && $userEntity->getId() ? $userEntity->getId() : 0
                        ],
                    ],
                ]
            ]
        ]);

        $this->add([
            'name' => 'user_password',
            'required' => false,
//            'empty'=>true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                [
                    'name' => 'CallBack',
                    'options' => [
                        'callback' => function ($password) {
                                return UserModel::encryption($password);
                        }
                    ],
                ]
            ]
        ]);

        $this->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => 'stringTrim']
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'max' => 60
                    ],
                ],
                [
                    'name' => 'EmailAddress'
                ],
                [
                    'name' => 'Db\NoRecordExists',
                    'options' => [
                        'table' => $this->userModuleOptions->getUserTable(),
                        'field' => 'email',
                        'adapter' => GlobalAdapterFeature::getStaticAdapter(),
                        'exclude' => [
                            'field' => 'id',
                            'value' => $userEntity && $userEntity->getId() ? $userEntity->getId() : 0
                        ],
                    ],
                ]
            ]
        ]);
        return $this;
    }
}
