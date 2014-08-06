<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-06-30
 * @Time: 9:07
 * @QQ: 259522
 * @FileName: AbstractBaseTableGateway.php
 */

namespace XtBase\Table;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Predicate\PredicateInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature\EventFeature;
use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManager;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Stdlib\Hydrator\Strategy\ClosureStrategy;

/**
 * Class AbstractBaseTableGateway
 * @package XtBase\Table
 */
abstract class AbstractBaseTableGateway extends AbstractTableGateway implements AdapterAwareInterface,
    TableGatewayInitAwareInterface,
    ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    /**
     *
     */
    const DEFAULT_DISABLED_STATUS = 0;
    /**
     *
     */
    const DEFAULT_ENABLED_STATUS = 99;
    /**
     * @var
     */
    protected $entityClass;

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var null|HydratorInterface $hydrator
     */
    protected $hydrator = null;

    /**
     * @return mixed
     */
    protected function getEntityClass()
    {
        if (!$this->entityClass) {
            $this->entityClass = str_replace(['\Model\\', 'Table'], ['\Entity\\', 'Entity'], get_called_class());
        }
        return $this->entityClass;
    }

    /**
     * Set db adapter
     *
     * @param Adapter $adapter
     * @return AdapterAwareInterface
     */
    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->init();
        $hydrator = ($this->hydrator) ?: new ClassMethods();
        $entityClass = $this->getEntityClass();
        $this->resultSetPrototype = new HydratingResultSet($hydrator, new $entityClass());
//        $this->getSqlString();
        $this->initialize();
    }

    /**
     * @param string|array $columns
     * @return $this
     */
    public function addDateTimeStrategy($columns)
    {
        if (empty($columns)) {
            return $this;
        }
        if (empty($this->hydrator)) {
            $this->hydrator = new ClassMethods();
        }
        if (is_array($columns)) {
            foreach ($columns as $column) {
                $this->{__FUNCTION__}($column);
            }
            return $this;
        }
        $this->hydrator->addStrategy($columns, new ClosureStrategy(function ($data) {
            return strtotime($data);
        }, function ($data) {
            return date('Y-m-d H:i:s', $data);
        }));
        return $this;
    }

    /**
     *
     */
    public function init()
    {
    }

    /**
     *
     */
    public function getSqlString()
    {
        if (!$this->featureSet instanceof FeatureSet) {
            $this->featureSet = new FeatureSet();
        }
        $eventManager = new EventManager();
        $eventManager->attach(['preInsert', 'preSelect', 'preUpdate', 'preDelete'], function (EventInterface $event) {
            $sqlKey = strtolower(str_replace('pre', '', $event->getName()));
            echo $event->getParam($sqlKey)->getSqlString($event->getTarget()->getAdapter()->getPlatform()), '<br />';
        });
        $this->featureSet->addFeature(new EventFeature($eventManager));
    }


    /**
     * @param $value
     * @param null $column
     * @param string $order
     * @param array $columns
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getByColumn($value, $column = null, $order = 'DESC', $columns = ['*'])
    {
        $column = ($column) ?: $this->primaryKey;
        return $this->select(function (Select $select) use ($value, $column, $columns, $order) {
            $select->columns($columns);
            $order = ($order) ?: [$this->primaryKey => 'DESC'];
            $select->where([$column => $value])->order($order);
        });
    }

    /**
     * @param $value
     * @param null $column
     * @return array|\ArrayObject|null
     */
    public function getOneByColumn($value, $column = null)
    {
        $column = ($column) ?: $this->primaryKey;
        return $this->select([$column => $value])->current();
    }

    /**
     * @param $value
     * @param string $column
     * @return int
     */
    public function deleteByColumn($value, $column = null)
    {
        $column = ($column) ?: $this->primaryKey;
        return $this->delete(function (Delete $delete) use ($value, $column) {
            $delete->where([$column => $value]);
        });
    }

    /**
     * @param where|\Closure|string|array|PredicateInterface $where
     * @param string|array $order
     * @param array $columns
     * @return Paginator
     */
    public function getPaginator($page, $where = null, $order = null, $columns = ['*'])
    {
        $select = $this->sql->select();
        $select->columns($columns);
        if ($where !== null) {
            $select->where($where);
        }
        $order = ($order) ?: [$this->primaryKey => 'DESC'];
        $select->order($order);
        $dbAdapter = new DbSelect($select, $this->adapter, $this->resultSetPrototype);
        $paginator = new Paginator($dbAdapter);
        $paginator->setCurrentPageNumber((int)$page);
        return $paginator;
    }

    /**
     * @return \Zend\Db\Adapter\Driver\ConnectionInterface
     */
    public function getConnection()
    {
        return $this->adapter->getDriver()->getConnection();
    }

    /**
     * @param $id
     * @param null $status
     */
    public function disabledById($id, $status = null)
    {
        $status = ($status) ?: static::DEFAULT_DISABLED_STATUS;
        if ($this->getOneByColumn($id)) {
            $this->update(['status' => $status], [$this->primaryKey => $id]);
        }
    }

    /**
     * @param $id
     * @param null $status
     */
    public function enabledById($id, $status = null)
    {
        $status = ($status) ?: static::DEFAULT_ENABLED_STATUS;
        if ($this->getOneByColumn($id)) {
            $this->update(['status' => $status], [$this->primaryKey => $id]);
        }
    }

    /**
     * @param $data
     * @param $id
     * @return int
     */
    public function insertOrUpdate($data, $id)
    {
        if ((int)$id === 0) {
            return $this->insert($data) ? $this->lastInsertValue : 0;
        }
        if ($this->getOneByColumn($id)) {
            return $this->update($data, [$this->primaryKey => $id]);
        }
    }
} 