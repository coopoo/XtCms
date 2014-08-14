<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-14
 * @Time: 16:49
 * @QQ: 259522
 * @FileName: PermissionInputFilter.php
 */

namespace XtAuth\InputFilter;


use XtAuth\Entity\PermissionEntity;
use XtUser\Options\UserModuleOptionsAwareInterFace;
use XtUser\Service\UserModuleOptionsTrait;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\InputFilter\InputFilter;

class PermissionInputFilter extends InputFilter implements UserModuleOptionsAwareInterFace
{
    use UserModuleOptionsTrait;

    public function __invoke(PermissionEntity $permissionEntity = null)
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
                        'table' => $this->userModuleOptions->getPermissionTable(),
                        'field' => 'name',
                        'adapter' => GlobalAdapterFeature::getStaticAdapter(),
                        'exclude' => [
                            'field' => 'id',
                            'value' => $permissionEntity && $permissionEntity->getId() ? $permissionEntity->getId() : 0
                        ],
                    ],
                ]
            ]
        ]);
        $this->add([
            'name' => 'action',
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
                        'max' => 100
                    ],
                ],
                [
                    'name' => 'Db\NoRecordExists',
                    'options' => [
                        'table' => $this->userModuleOptions->getPermissionTable(),
                        'field' => 'action',
                        'adapter' => GlobalAdapterFeature::getStaticAdapter(),
                        'exclude' => [
                            'field' => 'id',
                            'value' => $permissionEntity && $permissionEntity->getId() ? $permissionEntity->getId() : 0
                        ],
                    ],
                ]
            ]
        ]);
        return $this;
    }
} 