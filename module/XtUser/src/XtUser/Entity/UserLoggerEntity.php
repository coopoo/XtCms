<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-24
 * @Time: 13:30
 * @QQ: 259522
 * @FileName: UserLoggerEntity.php
 */

namespace XtUser\Entity;


/**
 * Class UserLoggerEntity
 * @package XtUser\Entity
 */
class UserLoggerEntity
{
    /**
     * @var
     */
    protected $userId;
    /**
     * @var
     */
    protected $loginTime;
    /**
     * @var
     */
    protected $loginIp;

    /**
     * @return mixed
     */
    public function getLoginIp()
    {
        return $this->loginIp;
    }

    /**
     * @param mixed $loginIp
     * @return $this
     */
    public function setLoginIp($loginIp)
    {
        $this->loginIp = $loginIp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLoginTime()
    {
        return $this->loginTime;
    }

    /**
     * @param mixed $loginTime
     * @return $this
     */
    public function setLoginTime($loginTime)
    {
        $this->loginTime = $loginTime;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

} 