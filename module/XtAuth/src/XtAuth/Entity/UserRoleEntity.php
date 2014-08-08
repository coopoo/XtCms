<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-08
 * @Time: 10:29
 * @QQ: 259522
 * @FileName: UserRoleEntity.php
 */

namespace XtAuth\Entity;


class UserRoleEntity
{
    protected $id;
    protected $userId;
    protected $roleId;
    protected $modifyTime;
    protected $modifyIp;

    /**
     * @return mixed
     */
    public function getModifyIp()
    {
        return $this->modifyIp;
    }

    /**
     * @param mixed $modifyIp
     */
    public function setModifyIp($modifyIp)
    {
        $this->modifyIp = $modifyIp;
    }

    /**
     * @return mixed
     */
    public function getModifyTime()
    {
        return $this->modifyTime;
    }

    /**
     * @param mixed $modifyTime
     */
    public function setModifyTime($modifyTime)
    {
        $this->modifyTime = $modifyTime;
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
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * @param mixed $roleId
     * @return $this
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;
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