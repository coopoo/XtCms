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
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Class AbstractBaseTableGateway
 * @package XtBase\Table
 */
abstract class AbstractBaseTableGateway extends AbstractTableGateway implements AdapterAwareInterface,
    TableGatewayInitAwareInterface
{
    /**
     * @var
     */
    protected $entityClass;

    /**
     * @var string
     */
    protected $primaryKey = 'id';

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
        $entityClass = $this->getEntityClass();
        $this->adapter = $adapter;
        $this->resultSetPrototype = new HydratingResultSet(new ClassMethods(), new $entityClass());
        $this->init();
        $this->getSqlString();
        $this->initialize();
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
            $select->where([$column => $value])->$order([$column => $order]);
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
    public function getPaginator($where = null, $order = 'DESC', $columns = ['*'])
    {
        $select = $this->sql->select();
        $select->columns($columns);
        if ($where !== null) {
            $select->where($where);
        }
        $select->order([$this->primaryKey => $order]);
        $dbAdapter = new DbSelect($select, $this->adapter, $this->resultSetPrototype);
        return new Paginator($dbAdapter);
    }

    /**
     * @return \Zend\Db\Adapter\Driver\ConnectionInterface
     */
    public function getConnection()
    {
        return $this->adapter->getDriver()->getConnection();
    }
} 