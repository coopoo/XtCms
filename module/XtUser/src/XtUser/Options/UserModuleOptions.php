<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 13:18
 * @QQ: 259522
 * @FileName: UserModuleOptions.php
 */

namespace XtUser\Options;


use Zend\Stdlib\AbstractOptions;

class UserModuleOptions extends AbstractOptions
{
    protected $disabledRegister = false;

    protected $disabledLogin = false;

    protected $table = 'xt-user';

    protected $authenticationStorage = 'Zend\Authentication\Storage\Session';

    protected $passwordFailLimit = 5;

    protected $passwordFailTime = 3600;

    protected $rememberMe = 1440;

    /**
     * @return boolean
     */
    public function isDisabledLogin()
    {
        return $this->disabledLogin;
    }

    /**
     * @param boolean $disabledLogin
     * @return $this
     */
    public function setDisabledLogin($disabledLogin)
    {
        $this->disabledLogin = $disabledLogin;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isDisabledRegister()
    {
        return $this->disabledRegister;
    }

    /**
     * @param boolean $disabledRegister
     * @return $this
     */
    public function setDisabledRegister($disabledRegister)
    {
        $this->disabledRegister = $disabledRegister;
        return $this;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthenticationStorage()
    {
        return $this->authenticationStorage;
    }

    /**
     * @param string $authenticationStorage
     * @return $this
     */
    public function setAuthenticationStorage($authenticationStorage)
    {
        $this->authenticationStorage = $authenticationStorage;
        return $this;
    }

    /**
     * @return int
     */
    public function getPasswordFailLimit()
    {
        return $this->passwordFailLimit;
    }

    /**
     * @param int $passwordFailLimit
     * @return $this
     */
    public function setPasswordFailLimit($passwordFailLimit)
    {
        $this->passwordFailLimit = $passwordFailLimit;
        return $this;
    }

    /**
     * @return int
     */
    public function getPasswordFailTime()
    {
        return $this->passwordFailTime;
    }

    /**
     * @param int $passwordFailTime
     * @return $this
     */
    public function setPasswordFailTime($passwordFailTime)
    {
        $this->passwordFailTime = $passwordFailTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getRememberMe()
    {
        return $this->rememberMe;
    }

    /**
     * @param int $rememberMe
     * @return $this
     */
    public function setRememberMe($rememberMe)
    {
        $this->rememberMe = $rememberMe;
        return $this;
    }
} 