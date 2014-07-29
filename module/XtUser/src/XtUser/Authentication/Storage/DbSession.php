<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-28
 * @Time: 7:18
 * @QQ: 259522
 * @FileName: DbSession.php
 */

namespace XtUser\Authentication\Storage;


use Zend\Authentication\Storage\StorageInterface;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;
use Zend\Session\SaveHandler\DbTableGateway;
use Zend\Session\SaveHandler\DbTableGatewayOptions;
use Zend\Session\SessionManager;

/**
 * Class DbSession
 * @package XtUser\Authentication\Storage
 */
class DbSession implements StorageInterface
{
    /**
     * @var null
     */
    protected $namespace;
    /**
     * @var Container
     */
    protected $session;
    /**
     * @var string
     */
    protected $table = 'xt_user_session';
    /**
     * @var TableGateway
     */
    protected $tableGateway;

    /**
     * @param null $namespace
     * @param SessionManager $sessionManager
     */
    public function __construct($namespace = null, SessionManager $sessionManager = null)
    {
        if ($namespace !== null) {
            $this->namespace = $namespace;
        }

        $this->session = new Container($namespace, $sessionManager);

        $this->tableGateway = new TableGateway($this->table, GlobalAdapterFeature::getStaticAdapter());

        $dbTableGatewayOptions = new DbTableGatewayOptions();
        $dbTableGateway = new DbTableGateway($this->tableGateway, $dbTableGatewayOptions);

        $dbTableGateway->open(null, $this->namespace);

        $this->getSessionManager()->setSaveHandler($dbTableGateway);
    }

    /**
     * @return mixed
     */
    public function getSessionManager()
    {
        return $this->session->getManager();
    }

    /**
     * Returns true if and only if Storage is empty
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface If it is impossible to determine whether Storage is empty
     * @return bool
     */
    public function isEmpty()
    {
        $data = $this->read();
        return empty($data);
    }

    /**
     * Returns the contents of Storage
     *
     * Behavior is undefined when Storage is empty.
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface If reading contents from Storage is impossible
     * @return mixed
     */
    public function read()
    {
        return json_decode($this->getSessionManager()->getSaveHandler()->read($this->getId()));
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->getSessionManager()->getId();
    }

    /**
     * Writes $contents to Storage
     *
     * @param  mixed $contents
     * @throws \Zend\Authentication\Exception\ExceptionInterface If writing $contents to Storage is impossible
     * @return void
     */
    public function write($contents)
    {
        if (!is_string($contents)) {
            $contents = json_encode($contents);
        }
        return $this->getSessionManager()->getSaveHandler()->write($this->getId(), $contents);
    }

    /**
     * Clears contents from Storage
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface If clearing contents from Storage is impossible
     * @return void
     */
    public function clear()
    {
        return $this->getSessionManager()->getSaveHandler()->destroy($this->getId());
    }

} 