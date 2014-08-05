<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-31
 * @Time: 21:23
 * @QQ: 259522
 * @FileName: ResourceInputFilter.php
 */

namespace XtUser\InputFilter;


use XtUser\Entity\ResourceEntity;
use XtUser\Model\UserModel;
use XtUser\Options\UserModuleOptionsAwareInterFace;
use XtUser\Service\UserModuleOptionsTrait;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\InputFilter\InputFilter;

class ResourceInputFilter extends InputFilter implements UserModuleOptionsAwareInterFace
{
    use UserModuleOptionsTrait;

    public function __invoke(ResourceEntity $resourceEntity = null)
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
                        'table' => $this->userModuleOptions->getResourceTable(),
                        'field' => 'name',
                        'adapter' => GlobalAdapterFeature::getStaticAdapter(),
                        'exclude' => [
                            'field' => 'id',
                            'value' => $resourceEntity && $resourceEntity->getId() ? $resourceEntity->getId() : 0
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
                        'table' => $this->userModuleOptions->getResourceTable(),
                        'field' => 'action',
                        'adapter' => GlobalAdapterFeature::getStaticAdapter(),
                        'exclude' => [
                            'field' => 'id',
                            'value' => $resourceEntity && $resourceEntity->getId() ? $resourceEntity->getId() : 0
                        ],
                    ],
                ]
            ]
        ]);

        return $this;
    }
} 