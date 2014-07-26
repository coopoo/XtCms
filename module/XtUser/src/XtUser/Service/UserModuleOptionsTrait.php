<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 13:30
 * @QQ: 259522
 * @FileName: UserModuleOptionsTrait.php
 */

namespace XtUser\Service;


use XtUser\Options\UserModuleOptions;

trait UserModuleOptionsTrait
{
    /**
     * @var UserModuleOptions
     */
    protected $userModuleOptions;

    /**
     * @return mixed
     */
    public function getUserModuleOptions()
    {
        return $this->userModuleOptions;
    }


    /**
     * @param UserModuleOptions $userModuleOptions
     * @return $this
     */
    public function setUserModuleOptions(UserModuleOptions $userModuleOptions)
    {
        $this->userModuleOptions = $userModuleOptions;
        return $this;
    }
} 