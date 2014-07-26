<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-24
 * @Time: 14:33
 * @QQ: 259522
 * @FileName: UserDetailEntity.php
 */

namespace XtUser\Entity;


/**
 * Class UserDetailEntity
 * @package XtUser\Entity
 */
class UserDetailEntity
{
    /**
     * @var
     */
    protected $userId;
    /**
     * @var
     */
    protected $realName;
    /**
     * @var
     */
    protected $tel;
    /**
     * @var
     */
    protected $mobile;
    /**
     * @var
     */
    protected $qq;
    /**
     * @var
     */
    protected $address;
    /**
     * @var
     */
    protected $modifyTime;
    /**
     * @var
     */
    protected $modifyIp;

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param mixed $mobile
     * @return $this
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModifyIp()
    {
        return $this->modifyIp;
    }

    /**
     * @param mixed $modifyIp
     * @return $this
     */
    public function setModifyIp($modifyIp)
    {
        $this->modifyIp = $modifyIp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModifyTime()
    {
        return date('Y-m-d H:i:s', $this->modifyTime);
    }

    /**
     * @param mixed $modifyTime
     * @return $this
     */
    public function setModifyTime($modifyTime)
    {
        $this->modifyTime = $modifyTime;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQq()
    {
        return $this->qq;
    }

    /**
     * @param mixed $qq
     * @return $this
     */
    public function setQq($qq)
    {
        $this->qq = $qq;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRealName()
    {
        return $this->realName;
    }

    /**
     * @param mixed $realName
     * @return $this
     */
    public function setRealName($realName)
    {
        $this->realName = $realName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param mixed $tel
     * @return $this
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
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