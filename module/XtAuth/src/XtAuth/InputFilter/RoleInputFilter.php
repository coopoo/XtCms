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

namespace XtAuth\InputFilter;


use XtAuth\Entity\RoleEntity;
use XtUser\Options\UserModuleOptionsAwareInterFace;
use XtUser\Service\UserModuleOptionsTrait;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\InputFilter\InputFilter;

class RoleInputFilter extends InputFilter implements UserModuleOptionsAwareInterFace
{
    use UserModuleOptionsTrait;

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
                        'max' => 30
                    ],
                ],
                [
                    'name' => 'Db\NoRecordExists',
                    'options' => [
                        'table' => $this->userModuleOptions->getRoleTable(),
                        'field' => 'name',
                        'adapter' => GlobalAdapterFeature::getStaticAdapter(),
                        'exclude' => [
                            'field' => 'id',
                            'value' => $roleEntity && $roleEntity->getId() ? $roleEntity->getId() : 0
                        ],
                    ],
                ]
            ]
        ]);

        return $this;
    }
} 