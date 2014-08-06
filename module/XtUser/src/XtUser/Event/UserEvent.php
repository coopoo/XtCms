<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 16:06
 * @QQ: 259522
 * @FileName: UserEvent.php
 */

namespace XtUser\Event;


use XtUser\Entity\UserEntity;
use XtUser\Options\UserModuleOptions;
use XtUser\Options\UserModuleOptionsAwareInterFace;
use Zend\EventManager\Event;
use Zend\Form\FormInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserEvent
 * @package XtUser\Event
 */
class UserEvent extends Event implements UserModuleOptionsAwareInterFace
{
    /**
     *
     */
    const USER_REGISTER_PRE = 'user.register.pre';

    /**
     *
     */
    const USER_REGISTER_POST = 'user.register.post';

    /**
     *
     */
    const USER_REGISTER_FAIL = 'user.register.fail';

    /**
     *
     */
    const USER_LOGIN_PRE = 'user.login.pre';

    /**
     *
     */
    const USER_LOGIN_POST = 'user.login.post';

    /**
     *
     */
    const USER_LOGIN_FAIL = 'user.login.fail';

    /**
     *
     */
    const USER_CHANGE_PASSWORD_PRE = 'user.change.password.pre';
    /**
     *
     */
    const USER_CHANGE_PASSWORD_POST = 'user.change.password.post';
    /**
     *
     */
    const USER_CHANGE_PASSWORD_FAIL = 'user.change.password.fail';

    const REBUILD_PASSWORD_PRE = 'rebuild_password_pre';
    const REBUILD_PASSWORD_POST = 'rebuild_password_post';
    const REBUILD_PASSWORD_FAIL = 'rebuild_password_fail';

    /**
     * 默认用户身份数据库字段
     */
    const DEFAULT_IDENTITY_KEY = 'username';

    /**
     * @return mixed
     */
    public function getUserModuleOptions()
    {
        return $this->getParam('userModuleOptions');
    }

    /**
     * @param UserModuleOptions $userModuleOptions
     * @return mixed
     */
    public function setUserModuleOptions(UserModuleOptions $userModuleOptions)
    {
        $this->setParam('userModuleOptions', $userModuleOptions);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserEntity()
    {
        return $this->getParam('userEntity');
    }

    /**
     * @param UserEntity $userEntity
     * @return $this
     */
    public function setUserEntity(UserEntity $userEntity)
    {
        $this->setParam('userEntity', $userEntity);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getForm()
    {
        return $this->getParam('form');
    }

    /**
     * @param FormInterface $form
     * @return $this
     */
    public function setForm(FormInterface $form)
    {
        $this->setParam('form', $form);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getServiceLocator()
    {
        return $this->getParam('serviceLocator');
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return $this
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->setParam('serviceLocator', $serviceLocator);
        return $this;
    }

    /**
     * @param $identityKey
     * @return $this
     */
    public function setIdentityKey($identityKey)
    {
        $this->setParam('identityKey', $identityKey);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdentityKey()
    {
        return $this->getParam('identityKey', static::DEFAULT_IDENTITY_KEY);
    }

    /**
     * @param $code
     * @return $this
     */
    public function setLoginFailCode($code)
    {
        $this->setParam('loginFailCode', $code);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLoginFailCode()
    {
        return $this->getParam('loginFailCode');
    }

} 