<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-30
 * @Time: 10:27
 * @QQ: 259522
 * @FileName: RoleInputFilter.php
 */

namespace XtUser\InputFilter;


use XtUser\Entity\RoleEntity;
use XtUser\Model\UserModel;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\InputFilter\InputFilter;

class RoleInputFilter extends InputFilter
{
    public function __invoke(RoleEntity $roleEntity = null)
    {
        $this->add([
            'name' => 'csrf',
            'required' => true
        ]);

        $this->add([
            'name' => 'name',
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
                        'table' => UserModel::RoleTable(),
                        'field' => 'name',
                        'adapter' => GlobalAdapterFeature::getStaticAdapter(),
                        'exclude' => [
                            'field' => 'name',
                            'value' => $roleEntity && $roleEntity->getName() ? $roleEntity->getName() : ''
                        ],
                    ],
                ]
            ]
        ]);

        $this->add([
            'name' => 'status',
            'required' => true,
            'filters' => [
                ['name' => 'int'],
            ]
        ]);
        return $this;
    }
} 