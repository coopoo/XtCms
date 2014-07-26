<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-06-27
 * @Time: 12:36
 * @QQ: 259522
 * @FileName: DbOptions.php
 */

namespace XtDb\Options;


use Zend\Stdlib\AbstractOptions;

class DbOptions extends AbstractOptions
{
    protected $backPath = '/back';

    protected $addDrop = true;

    protected $limit = 100;

    /**
     * @return string
     */
    public function getBackPath()
    {
        return $this->backPath;
    }

    /**
     * @param string $backPath
     * @return $this
     */
    public function setBackPath($backPath)
    {
        $this->backPath = $backPath;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isAddDrop()
    {
        return $this->addDrop;
    }

    /**
     * @param boolean $addDrop
     * @return $this
     */
    public function setAddDrop($addDrop)
    {
        $this->addDrop = $addDrop;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }


} 