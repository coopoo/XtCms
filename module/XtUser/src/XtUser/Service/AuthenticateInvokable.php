<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-23
 * @Time: 16:13
 * @QQ: 259522
 * @FileName: AuthenticateInvokable.php
 */

namespace XtUser\Service;


use XtUser\Entity\UserEntity;
use XtUser\Model\UserModel;
use XtUser\Options\UserModuleOptionsAwareInterFace;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;
use Zend\Authentication\Adapter\Exception\RuntimeException;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Result;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\Hydrator\ClassMethods;


class AuthenticateInvokable implements UserModuleOptionsAwareInterFace,
    AuthenticationServiceInterface,
    ServiceLocatorAwareInterface
{
    use UserModuleOptionsTrait;
    use ServiceLocatorAwareTrait;

    protected $userEvent;
    protected $userEntity;
    protected $result;
    protected $storage;
    protected $adapter;

    /**
     * @return mixed
     */
    public function getUserEntity()
    {
        $hydrator = new ClassMethods();
        return $hydrator->hydrate((array)$this->getAdapter()->getResultRowObject(), new UserEntity());
    }

    /**
     * @param mixed $userEntity
     * @return $this
     */
    public function setUserEntity($userEntity)
    {
        $this->userEntity = $userEntity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserEvent()
    {
        return $this->userEvent;
    }

    /**
     * @param mixed $userEvent
     * @return $this
     */
    public function setUserEvent($userEvent)
    {
        $this->userEvent = $userEvent;
        return $this;
    }

    /**
     * @param AdapterInterface $adapter
     * @return Result
     */
    public function authenticate(AdapterInterface $adapter = null)
    {
        if (!$adapter) {
            if (!$adapter = $this->getAdapter()) {
                throw new RuntimeException('An adapter must be set or passed prior to calling authenticate()');
            }
        }
        $result = $adapter->authenticate();

        /**
         * ZF-7546 - prevent multiple successive calls from storing inconsistent results
         * Ensure storage has clean state
         */
        if ($this->hasIdentity()) {
            $this->clearIdentity();
        }
        if ($result->isValid()) {
            $userObj = $adapter->getResultRowObject(array('id', 'display_name'));
            $userObj->EXPIRE = microtime(true);
            $this->getStorage()->write($userObj);
        }
        return $result;
    }

    /**
     * Returns true if and only if an identity is available
     *
     * @return bool
     */
    public function hasIdentity()
    {
        return !$this->getStorage()->isEmpty();
    }

    /**
     * Returns the authenticated identity or null if no identity is available
     *
     * @return mixed|null
     */
    public function getIdentity()
    {
        $storage = $this->getStorage();

        if ($storage->isEmpty()) {
            return null;
        }

        return $storage->read();
    }

    /**
     * Clears the identity
     *
     * @return void
     */
    public function clearIdentity()
    {
        $this->getStorage()->clear();
    }

    /**
     * @return mixed
     */
    public function getAdapter()
    {
        if (empty($this->adapter)) {
            $dbAdapter = GlobalAdapterFeature::getStaticAdapter();
            $this->adapter = new CredentialTreatmentAdapter($dbAdapter, $this->userModuleOptions->getTable(), $this->userEvent->getIdentityKey(), 'user_password', "md5(CONCAT(?,uniqid))");
            $this->adapter->setIdentity($this->userEvent->getUserEntity()->getUsername())->setCredential($this->userEvent->getUserEntity()->getUserPassword());
        }
        return $this->adapter;
    }

    /**
     * @param mixed $adapter
     * @return $this
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        if (empty($this->result)) {
            $this->result = $this->authenticate();
        }
        return $this->result;
    }

    /**
     * @param mixed $result
     * @return $this
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStorage()
    {
        if (empty($this->storage)) {
            $authenticationStorageClass = $this->userModuleOptions->getAuthenticationStorage();
            $this->storage = new $authenticationStorageClass($this->userModuleOptions->getTable());
        }
        return $this->storage;
    }

    /**
     * @param mixed $storage
     * @return $this
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
        return $this;
    }

    public function isValid()
    {
        return $this->getResult()->isValid();
    }

    public function isAlive()
    {
        $userObj = $this->getStorage()->read();
        if ($userObj && ($userObj->EXPIRE > microtime(true) - $this->userModuleOptions->getRememberMe())) {
            $userTable = $this->serviceLocator->get(UserModel::USER_TABLE_CLASS);
            $userEntity = $userTable->getOneByColumn($userObj->id, 'id', ['display_name', 'status']);
            if ($userEntity && (int)$userEntity->getStatus() === UserModel::ALLOW_STATUS) {
                $userObj->display_name = $userEntity->getDisplayName();
                $userObj->EXPIRE = microtime(true);
                return true;
            }
        }
        $this->clearIdentity();
        return false;
    }

    public function getUserId()
    {
        return $this->getStorage()->read()->id;
    }


} 