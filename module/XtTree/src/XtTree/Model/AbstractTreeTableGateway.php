<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-15
 * @Time: 9:31
 * @QQ: 259522
 * @FileName: AbstractTreeTableGateway.php
 */

namespace XtTree\Model;

use ArrayObject;
use Traversable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\SqlInterface;
use Zend\Db\TableGateway\Feature\EventFeature;
use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManager;
use Zend\Stdlib\ArrayUtils;
use XtTree\Exception\InvalidArgumentException;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\AbstractTableGateway;

abstract class AbstractTreeTableGateway extends AbstractTableGateway implements AdapterAwareInterface,
    TreeTableInterface
{
    /**
     * @var string
     */
    protected $depthColumn = 'depth';
    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Set db adapter
     *
     * @param Adapter $adapter
     * @return AdapterAwareInterface
     */
    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        if (!$this->featureSet instanceof FeatureSet) {
            $this->featureSet = new FeatureSet;
        }

        $eventManager = new EventManager();
        $eventManager->attach(['preInsert', 'preSelect', 'preUpdate', 'preDelete'], function (EventInterface $event) {
            $sqlKey = strtolower(str_replace('pre', '', $event->getName()));
            echo $event->getParam($sqlKey)->getSqlString($event->getTarget()->getAdapter()->getPlatform()), '<br>';
        });

        $this->featureSet->addFeature(new EventFeature($eventManager));

        $this->initialize();
    }

    /**
     * @return \Zend\Db\Adapter\Driver\ConnectionInterface
     */
    protected function getConnection()
    {
        return $this->adapter->getDriver()->getConnection();
    }

    /**
     * @param $identifier
     * @return string
     */
    protected function quoteIdentifier($identifier)
    {
        return $this->adapter->getPlatform()->quoteIdentifier($identifier);
    }

    /**
     * @param $value
     * @param $column
     * @param array $selectColumns
     * @return array
     */
    public function getOneByColumn($value, $column, $selectColumns = ['*'])
    {
        $row = $this->select(function (Select $select) use ($value, $column, $selectColumns) {
            $select->columns($selectColumns);
            $select->where([$column => $value]);
        })->current();
        return $this->resultSetExtract($row);
    }

    /**
     * @param $row
     * @return array
     */
    public function resultSetExtract($row)
    {
        if (!$row) {
            throw new InvalidArgumentException('节点不存在!');
        }

        if ($row instanceof ArrayObject) {
            $row = $row->getArrayCopy();
        }
        if ($row instanceof Traversable) {
            $row = ArrayUtils::iteratorToArray($row);
        } elseif (is_object($row)) {
            $row = $this->resultSetPrototype->getHydrator()->extract($row);
        }
        if (!is_array($row)) {
            throw new InvalidArgumentException('$row 必须是数组或者是数据实体对象');
        }
        return $row;
    }

    /**
     * @param $executeSql
     * @return \Zend\Db\Adapter\Driver\ResultInterface
     */
    protected function executeSql($executeSql)
    {
        if (!$executeSql instanceof SqlInterface) {
            throw new InvalidArgumentException('$executeSql 必须是 \Zend\Db\Sql\SqlInterface 实例');
        }

        $class = get_class($executeSql);

        $executeAction = substr($class, strrpos($class, '\\') + 1);
        $this->featureSet->apply('pre' . $executeAction, array($executeSql));
        $statement = $this->sql->prepareStatementForSqlObject($executeSql);
        $result = $statement->execute();
        $this->featureSet->apply('post' . $executeAction, array($statement, $result, new ResultSet()));
        return $result;
    }

    /**
     * @param $result
     * @param $key
     * @return array
     */
    public function getInList($result, $key)
    {
        $result = $this->resultSetExtract($result);
        $inList = [];
        foreach ($result as $node) {
            $inList[] = $node[$key];
        }
        return $inList;
    }
} 