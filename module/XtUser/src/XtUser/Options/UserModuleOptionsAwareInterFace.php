<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 13:29
 * @QQ: 259522
 * @FileName: UserModuleOptionsAwareInterFace.php
 */

namespace XtUser\Options;


interface UserModuleOptionsAwareInterFace
{
    /**
     * @return mixed
     */
    public function getUserModuleOptions();

    /**
     * @param UserModuleOptions $userModuleOptions
     * @return mixed
     */
    public function setUserModuleOptions(UserModuleOptions $userModuleOptions);
} 