<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 13:44
 * @QQ: 259522
 * @FileName: UserEntity.php
 */

namespace XtUser\Entity;


/**
 * Class UserEntity
 * @package XtUser\Entity
 */
class UserEntity
{
    /**
     * @var
     */
    protected $id;
    /**
     * @var
     */
    protected $username;
    /**
     * @var
     */
    protected $userPassword;
    /**
     * @var
     */
    protected $oldPassword;
    /**
     * @var
     */
    protected $displayName;
    /**
     * @var
     */
    protected $uniqid;
    /**
     * @var
     */
    protected $email;
    /**
     * @var
     */
    protected $status;
    /**
     * @var
     */
    protected $registerTime;
    /**
     * @var
     */
    protected $registerIp;

    /**
     * @var null | UserDetailEntity
     */
    protected $userDetail = null;
    /**
     * @var null| UserLoggerEntity
     */
    protected $userLogger = null;
    /**
     * @var
     */
    protected $lastErrorTime;

    /**
     * @var
     */
    protected $errorCount;

    protected $rememberMe;

    /**
     * @return mixed
     */
    public function getRememberMe()
    {
        return $this->rememberMe;
    }

    /**
     * @param mixed $rememberMe
     * @return $this
     */
    public function setRememberMe($rememberMe)
    {
        $this->rememberMe = $rememberMe;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastErrorTime()
    {
        return $this->lastErrorTime;
    }

    /**
     * @param mixed $lastErrorTime
     * @return $this
     */
    public function setLastErrorTime($lastErrorTime)
    {
        $this->lastErrorTime = $lastErrorTime;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrorCount()
    {
        return $this->errorCount;
    }

    /**
     * @param mixed $errorCount
     * @return $this
     */
    public function setErrorCount($errorCount)
    {
        $this->errorCount = $errorCount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param mixed $displayName
     * @return $this
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegisterIp()
    {
        return $this->registerIp;
    }

    /**
     * @param mixed $registerIp
     * @return $this
     */
    public function setRegisterIp($registerIp)
    {
        $this->registerIp = $registerIp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegisterTime()
    {
        return $this->registerTime;
    }

    /**
     * @param mixed $registerTime
     * @return $this
     */
    public function setRegisterTime($registerTime)
    {
        $this->registerTime = $registerTime;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUniqid()
    {
        return $this->uniqid;
    }

    /**
     * @return mixed
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @param mixed $oldPassword
     * @return $this
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
        return $this;
    }

    /**
     * @param mixed $uniqid
     * @return $this
     */
    public function setUniqid($uniqid)
    {
        $this->uniqid = $uniqid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserPassword()
    {
        return $this->userPassword;
    }

    /**
     * @param mixed $userPassword
     * @return $this
     */
    public function setUserPassword($userPassword)
    {
        $this->userPassword = $userPassword;
        return $this;
    }

    /**
     * @return UserDetailEntity
     */
    public function getUserDetail()
    {
        return $this->userDetail;
    }

    /**
     * @param UserDetailEntity $userDetail
     * @return $this
     */
    public function setUserDetail(UserDetailEntity $userDetail)
    {
        $this->userDetail = $userDetail;
        return $this;
    }

    /**
     * @return null|UserLoggerEntity
     */
    public function getUserLogger()
    {
        return $this->userLogger;
    }

    /**
     * @param null|UserLoggerEntity $userLogger
     * @return $this
     */
    public function setUserLogger(UserLoggerEntity $userLogger)
    {
        $this->userLogger = $userLogger;
        return $this;
    }

} 