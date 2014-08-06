<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-06
 * @Time: 11:15
 * @QQ: 259522
 * @FileName: reBuildPasswordInputFilter.php
 */

namespace XtUser\InputFilter;


use XtUser\Entity\UserEntity;

class reBuildPasswordInputFilter extends RegisterInputFilter
{
    public function __invoke(UserEntity $userEntity = null)
    {
        parent::__invoke($userEntity);

        $this->remove('username')->remove('email');

        return $this;
    }
} 