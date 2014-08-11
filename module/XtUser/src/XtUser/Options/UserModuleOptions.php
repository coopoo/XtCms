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

/**
 * Class UserModuleOptions
 * @package XtUser\Options
 */
class UserModuleOptions extends AbstractOptions
{
    protected $__strictMode__ = false;
    protected $tablePre = '';
    protected $userTable = 'user';
    protected $detailTable = 'user_detail';
    protected $loggerTable = 'user_logger';
    protected $sessionTable = 'user_session';
    protected $roleTable = 'role';
    protected $resourceTable = 'resource';
    protected $permissionTable = 'permission';
    protected $userRoleTable = 'user_role';
    protected $rolePermissionTable = 'role_permission';
    protected $disabledLogin = false;
    protected $disabledRegister = false;
    protected $passwordFailLimit = 5;
    protected $passwordFailTime = 3600;
    protected $credentialColumn = 'user_password';
    protected $credentialType = 'md5(CONCAT(?,uniqid))';
    protected $loginCaptcha = true;
    protected $registerCaptcha = true;

    /**
     * @return boolean
     */
    public function isLoginCaptcha()
    {
        return $this->loginCaptcha;
    }

    /**
     * @param boolean $loginCaptcha
     *
     * @return $this;
     */
    public function setLoginCaptcha($loginCaptcha)
    {
        $this->loginCaptcha = $loginCaptcha;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isRegisterCaptcha()
    {
        return $this->registerCaptcha;
    }

    /**
     * @param boolean $registerCaptcha
     *
     * @return $this;
     */
    public function setRegisterCaptcha($registerCaptcha)
    {
        $this->registerCaptcha = $registerCaptcha;
        return $this;
    }

    /**
     * @return string
     */
    public function getCredentialColumn()
    {
        return $this->credentialColumn;
    }

    /**
     * @return string
     */
    public function getSessionTable()
    {
        return $this->tablePre . $this->sessionTable;
    }

    /**
     * @param string $sessionTable
     * @return $this
     */
    public function setSessionTable($sessionTable)
    {
        $this->sessionTable = $sessionTable;
        return $this;
    }

    /**
     * @param string $credentialColumn
     * @return $this
     */
    public function setCredentialColumn($credentialColumn)
    {
        $this->credentialColumn = $credentialColumn;
        return $this;
    }

    /**
     * @return string
     */
    public function getCredentialType()
    {
        return $this->credentialType;
    }

    /**
     * @param string $credentialType
     * @return $this
     */
    public function setCredentialType($credentialType)
    {
        $this->credentialType = $credentialType;
        return $this;
    }

    /**
     * @return string
     */
    public function getDetailTable()
    {
        return $this->tablePre . $this->detailTable;
    }

    /**
     * @param string $detailTable
     * @return $this
     */
    public function setDetailTable($detailTable)
    {
        $this->detailTable = $detailTable;
        return $this;
    }

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
    public function getLoggerTable()
    {
        return $this->tablePre . $this->loggerTable;
    }

    /**
     * @param string $loggerTable
     * @return $this
     */
    public function setLoggerTable($loggerTable)
    {
        $this->loggerTable = $loggerTable;
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
     * @return string
     */
    public function getPermissionTable()
    {
        return $this->tablePre . $this->permissionTable;
    }

    /**
     * @param string $permissionTable
     * @return $this
     */
    public function setPermissionTable($permissionTable)
    {
        $this->permissionTable = $permissionTable;
        return $this;
    }

    /**
     * @return string
     */
    public function getResourceTable()
    {
        return $this->tablePre . $this->resourceTable;
    }

    /**
     * @param string $resourceTable
     * @return $this
     */
    public function setResourceTable($resourceTable)
    {
        $this->resourceTable = $resourceTable;
        return $this;
    }

    /**
     * @return string
     */
    public function getRolePermissionTable()
    {
        return $this->tablePre . $this->rolePermissionTable;
    }

    /**
     * @param string $rolePermissionTable
     * @return $this
     */
    public function setRolePermissionTable($rolePermissionTable)
    {
        $this->rolePermissionTable = $rolePermissionTable;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoleTable()
    {
        return $this->tablePre . $this->roleTable;
    }

    /**
     * @param string $roleTable
     * @return $this
     */
    public function setRoleTable($roleTable)
    {
        $this->roleTable = $roleTable;
        return $this;
    }

    /**
     * @return string
     */
    public function getTablePre()
    {
        return $this->tablePre;
    }

    /**
     * @param string $tablePre
     * @return $this
     */
    public function setTablePre($tablePre)
    {
        $this->tablePre = $tablePre;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserRoleTable()
    {
        return $this->tablePre . $this->userRoleTable;
    }

    /**
     * @param string $userRoleTable
     * @return $this
     */
    public function setUserRoleTable($userRoleTable)
    {
        $this->userRoleTable = $userRoleTable;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserTable()
    {
        return $this->tablePre . $this->userTable;
    }

    /**
     * @param string $userTable
     * @return $this
     */
    public function setUserTable($userTable)
    {
        $this->userTable = $userTable;
        return $this;
    }


} 