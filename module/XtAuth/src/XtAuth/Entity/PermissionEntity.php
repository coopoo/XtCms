<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-08
 * @Time: 10:14
 * @QQ: 259522
 * @FileName: PermissionEntity.php
 */

namespace XtAuth\Entity;


class PermissionEntity
{
    protected $id;
    protected $name;
    protected $resourceId;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * @param mixed $resourceId
     * @return $this
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;
        return $this;
    }


} 