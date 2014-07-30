<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 14:18
 * @QQ: 259522
 * @FileName: LoginInputFilter.php
 */

namespace XtUser\InputFilter;


use XtUser\Entity\UserEntity;
use Zend\Validator\ValidatorChain;

class LoginInputFilter extends UserInputFilter
{
    public function __invoke(UserEntity $userEntity = null)
    {
        parent::__invoke($userEntity);

        $this->remove('email');

        $this->get('user_password')->setRequired(true);

        $this->get('username')->setValidatorChain(new ValidatorChain());

        return $this;

    }
} 