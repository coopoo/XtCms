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
        return $this;
    }
} 