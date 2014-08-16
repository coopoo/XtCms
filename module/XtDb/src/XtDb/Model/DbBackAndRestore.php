<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-06-30
 * @Time: 12:31
 * @QQ: 259522
 * @FileName: DbBackAndRestore.php
 */

namespace XtDb\Model;


use XtDb\Exception\InvalidArgumentException;
use XtDb\Options\DbOptionsAwareInterface;
use XtDb\Service\DbOptionsTrait;
use XtTool\FileManager\FileManager;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\Feature\EventFeature;

class DbBackAndRestore implements AdapterAwareInterface,
    DbBackRestoreInterface,
    DbOptionsAwareInterface
{
    use DbOptionsTrait;
    /**
     * @var Adapter
     */
    protected $adapter;
    /**
     * @var
     */
    protected $dbName = null;

    /**
     * @var array
     */
    protected $tables = [];

    /**
     * @var null
     */
    protected $fileName = '';

    /**
     * @var string
     */
    protected $backPath = '';

    /**
     * @var array
     */
    protected $backType = [
        0 => 'all',
        1 => 'construction',
        2 => 'data'
    ];

    /**
     * @var bool
     */
    protected $backDate = false;
    /**
     * @var bool
     */
    protected $backConstruction = true;

    /**
     * @var Sql
     */
    protected $sql = null;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $sqlCollection = [
        'version' => 'SELECT VERSION() as v',
        'insert' => 'INSERT INTO %s %s VALUES %s',
        'allTable' => 'SHOW TABLES FROM %s',
        'dbState' => 'SHOW TABLE STATUS FROM %s',
        'dropTable' => 'DROP TABLE IF EXISTS %s',
        'rowCount' => 'SELECT COUNT(0) AS rowCount FROM %s',
        'truncateTable' => 'TRUNCATE %s',
        'columns' => 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = \'%s\' AND  table_schema = \'%s\'',
    ];

    /**
     * Set db adapter
     *
     * @param Adapter $adapter
     * @return AdapterAwareInterface
     */
    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        if (!$this->sql) {
            $this->sql = new Sql($adapter);
        }
        $this->getDbName();
        $this->init();
    }


    /**
     *
     */
    public function init()
    {

    }

    /**
     * @param $options
     * @return $this
     */
    public function setOptions($options = null)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getBackType()
    {
        return isset($this->options['back_type']) ? $this->backType[$this->options['back_type']] : $this->backType[0];
    }

    /**
     * @return array
     */
    protected function getBackTables()
    {
        if (!$this->tables) {
            $options = $this->options;
            $tables = isset($options['tables']) ? $options['tables'] : null;
            $this->checkTableInDb($tables);
        }
        return $this->tables;
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        if (!$this->fileName) {
            $options = $this->options;
            $fileName = isset($options['file_name']) && !empty($options['file_name']) ? $options['file_name'] : '#' . date('Ymd-His-') . mt_rand(0, 999);
            $fileName = (strrchr($fileName, '.') === '.sql') ?: $fileName . '.sql';
            $this->fileName = mb_convert_encoding($fileName, 'gb2312', 'utf-8');
        }
        return $this->fileName;
    }

    /**
     * @return string
     */
    protected function getBackPath()
    {
        if (!$this->backPath) {
            $backPath = $this->dbOptions->getBackPath();
            if (substr(str_replace('\\', '/', $backPath), -1) != '/') {
                $backPath .= '/';
            }
            $this->backPath = ROOT_PATH . $backPath;
        }
        return $this->backPath;
    }

    /**
     * @return string
     */
    protected function getBackFile()
    {
        return $this->getBackPath() . $this->getFileName();
    }

    /**
     * @return string
     */
    protected function getBakInfo()
    {
        return isset($this->options['bak']) ? $this->options['bak'] : '';
    }

    /**
     *
     */
    protected function getBackFileInfo()
    {
        $createTop = '-- ' . $this->getBakInfo() . PHP_EOL;
        $createTop .= '-- 数据库备份类自动生成 ' . PHP_EOL;
        $createTop .= '-- 数据库  ：   `' . $this->dbName . '`' . PHP_EOL;
        $createTop .= '-- 生成日期 ：  ' . date('Y-m-d H:i:s', time()) . PHP_EOL;
        $createTop .= '-- PHP版本 ： ' . PHP_VERSION . PHP_EOL;
        $createTop .= '-- Mysql版本 ： ' . $this->getVersion() . PHP_EOL;
        $createTop .= '-- ----------------------------------------' . PHP_EOL;
        FileManager::write($this->getBackFile(), $createTop);
        return;
    }

    /**
     * @param $sql
     * @return \Zend\Db\Adapter\Driver\StatementInterface|\Zend\Db\ResultSet\ResultSet
     */
    protected function query($sql)
    {
        return $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
    }

    /**
     * @return mixed
     */
    protected function getVersion()
    {
        return $this->query($this->sqlCollection['version'])->current()->v;
    }

    /**
     * @return array
     */
    protected function fetchAllTables()
    {
        if (!$this->tables) {
            $tables = [];
            $tableArray = $this->query(sprintf($this->sqlCollection['allTable'], $this->quoteIdentifier($this->dbName)))->toArray();
            foreach ($tableArray as $tableItem) {
                $tables[] = $tableItem['Tables_in_' . $this->dbName];
            }
            $this->tables = $tables;
        }
        return $this->tables;
    }

    /**
     * @param $table
     * @return array
     */
    protected function getTableColumns($table)
    {
        $result = $this->query(sprintf($this->sqlCollection['columns'], $table, $this->dbName))->toArray();
        if ($result) {
            $columns = [];
            foreach ($result as $column) {
                $columns [] = $this->quoteIdentifier($column['COLUMN_NAME']);
            }
            return $columns;
        }
    }

    /**
     * @param $table
     * @return bool
     */
    protected function checkTableExists($table)
    {
        $tables = $this->fetchAllTables();
        return in_array($table, $tables);
    }

    /**
     * @return array
     */
    public function getDbState()
    {
        return $this->query(sprintf($this->sqlCollection['dbState'], $this->quoteIdentifier($this->dbName)))->toArray();
    }

    /**
     * @return array
     */
    public function getBackFileList()
    {
        return FileManager::listDir($this->getBackPath(), true, '.sql');
    }

    /**
     * @param $file
     * @return string
     */
    public function getRestoreFile($file)
    {
        $lines = FileManager::getFileToArray($this->getBackPath() . $file);
        if (!$lines) {
            return false;
        }
        $sqlStr = '';
        foreach ($lines as $line) {
            if (strpos($line, '/') !== 0 && strpos($line, '-') !== 0) {
                $sqlStr .= trim($line);
            }
            unset($line);
        }
        return $sqlStr;
    }

    /**
     * @param $table
     * @param array $columns
     * @param int $offset
     * @return mixed
     */
    protected function select($table, $offset = 0, $columns = ['*'])
    {
        $select = $this->sql->select();
        $select->from($table);
        $select->columns($columns);
        $select->offset($offset)->limit($this->dbOptions->getLimit());
        $selectString = $this->sql->getSqlStringForSqlObject($select);
        return $this->query($selectString)->toArray();
    }

    /**
     * @param array $row
     * @return string
     */
    protected function setRowToString($row = [])
    {
        if (!empty($row)) {
            return '(\'' . implode('\',\'', $row) . '\')';
        }
    }

    /**
     * @param $table
     * @return string
     */
    protected function getTableCreateInfo($table)
    {
        $sql = 'SHOW CREATE TABLE ' . $this->quoteIdentifier($table);
        $tableInfo = $this->query($sql)->toArray();
        $tableInfoString = '-- ' . PHP_EOL;
        $tableInfoString .= '-- 表的结构   ' . $this->quoteIdentifier($table) . PHP_EOL;
        $tableInfoString .= '-- ' . PHP_EOL;
        if ($this->dbOptions->isAddDrop()) {
            $tableInfoString .= sprintf($this->sqlCollection['dropTable'], $this->quoteIdentifier($table)) . ';' . PHP_EOL;
        }
        $tableInfoString .= $tableInfo[0]['Create Table'] . ';' . PHP_EOL;
        $tableInfoString .= '-- ----------------------------------------' . PHP_EOL;
        return $tableInfoString;
    }

    /**
     * @param $table
     * @return mixed
     */
    protected function getTableRowCount($table)
    {
        return $this->query(sprintf($this->sqlCollection['rowCount'], $this->quoteIdentifier($table)))->current()->rowCount;
    }

    /**
     * @return array
     */
    protected function fetchAll()
    {
        $results = $this->select('xt-user', ['id']);
        return $results;
    }

    /**
     * @return mixed
     */
    protected function getDbName()
    {
        if (!$this->dbName) {
            $this->dbName = $this->adapter->getCurrentSchema();
        }
        return $this->dbName;
    }


    /**
     * @return mixed
     */
    public function back()
    {
        $this->getBackFileInfo();
        $tables = $this->getBackTables();
        if (!$tables) {
            return;
        }

        switch ($this->getBackType()) {
            case 'all':
                $this->backDate = true;
            case 'construction':
                break;
            case 'data':
                $this->backConstruction = false;
                $this->backDate = true;
                break;
            default:
                break;
        }

        foreach ($tables as $table) {
            if ($this->backConstruction) {
                $tableInfo = $this->getTableCreateInfo($table);
                FileManager::write($this->getBackFile(), $tableInfo);
            }
            if (!$this->backDate) {
                continue;
            }
            $dataInfo = '--' . PHP_EOL;
            $dataInfo .= '-- 转存表中的数据 ' . $this->quoteIdentifier($table) . PHP_EOL;
            $dataInfo .= '--' . PHP_EOL;
            FileManager::write($this->getBackFile(), $dataInfo);
            $rowCount = $this->getTableRowCount($table);
            if (!$rowCount) {
                continue;
            }
            $limit = $this->dbOptions->getLimit();
            $num = ceil($rowCount / $limit);
            $tableColumns = $this->getTableColumns($table);
            $columnsString = '(' . implode(',', $tableColumns) . ')';
            for ($i = 0; $i < $num; $i++) {
                $rows = $this->select($table, $i * $limit);
                $rowString = [];
                foreach ($rows as $row) {
                    $rowString[] = $this->setRowToString($row);
                }
                $rowString = implode(',' . PHP_EOL, $rowString) . ';' . PHP_EOL;
                FileManager::write($this->getBackFile(), sprintf($this->sqlCollection['insert'], $this->quoteIdentifier($table), $columnsString, PHP_EOL . $rowString));
            }
        }
        return $this->fileName;
    }

    /**
     * @param $table
     * @throw
     */
    protected function checkTableInDb($table)
    {
        if ($table == null) {
            $this->fetchAllTables();
        } else {
            if (!is_array($table)) {
                $table = (array)$table;
            }
            $tables = [];
            foreach ($table as $item) {
                if ($this->checkTableExists($item)) {
                    $tables[] = $item;
                }
            }
            $this->tables = $tables;
        }
        if (empty($this->tables)) {
            throw new InvalidArgumentException('备份数据表不存在!');
        }
    }

    /**
     * @param string $file
     * @return mixed
     */
    public function restore($file)
    {
        $backFile = $this->getBackPath() . $file;

        $sqlString = $this->setFileToString($backFile);

        $this->splitSqlExecute($sqlString);

        return $file;
    }

    /**
     * @param $string
     * @return bool
     */
    protected function splitSqlExecute($string)
    {
        if (empty($string)) {
            return null;
        }
        //$sqlArray = [];
        $string = trim($string);
        $sqlDropTemp = preg_split('/DROP TABLE/si', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        foreach ($sqlDropTemp as $dropTemp) {
            $dropTemp = trim($dropTemp);
            if (strpos($dropTemp, 'IF') === 0) {
                $dropTemp = 'DROP TABLE ' . $dropTemp;
            }
            $sqlCreateTemp = preg_split('/CREATE TABLE/si', $dropTemp, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            foreach ($sqlCreateTemp as $createItem) {
                $createItem = trim($createItem);
                if (strpos($createItem, '`') === 0 || strpos($createItem, 'IF') === 0) {
                    $createItem = 'CREATE TABLE ' . $createItem;
                }
                $sqlInsertTemp = preg_split('/INSERT INTO/si', $createItem, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
                foreach ($sqlInsertTemp as $insertTemp) {
                    $insertTemp = trim($insertTemp);
                    if (strpos($insertTemp, '`') === 0) {
                        $insertTemp = 'INSERT INTO ' . $insertTemp;
                    }
                    //$sqlArray[] = $insertTemp;
                    $this->query($insertTemp);
                    unset($insertTemp);
                }
                unset($createItem);
            }
            unset($dropTemp);
        }
        return true;
    }

    /**
     * @param $file
     * @return string
     */
    protected function setFileToString($file)
    {
        $lines = FileManager::getFileToArray($file);
        if (!$lines) {
            return '';
        }
        $sqlString = '';
        foreach ($lines as $line) {
            if (strpos($line, '/') !== 0 && strpos($line, '-') !== 0) {
                $sqlString .= trim($line);
            }
            unset($line);
        }
        return $sqlString;
    }

    /**
     * @param string|array $table
     * @return mixed
     */
    public function truncateTable($table)
    {
        if (is_array($table)) {
            foreach ($table as $item) {
                $this->{__FUNCTION__}($item);
            }
            return;
        }
        if ($this->checkTableExists($table)) {
            return $this->query(sprintf($this->sqlCollection['truncateTable'], $this->quoteIdentifier($table)));
        }
    }

    /**
     * @param string|array $table
     * @return mixed
     */
    public function dropTable($table)
    {
        if (!is_array($table)) {
            $table = (array)$table;
        }

        $tableList = '';
        foreach ($table as $item) {
            if ($this->checkTableExists($item)) {
                $tableList .= $this->quoteIdentifier($item);
            }
        }
        return $this->query(sprintf($this->sqlCollection['dropTable'], $tableList));
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
     * @param string $file
     * @return bool
     */
    public function unlink($file)
    {
        return FileManager::unlink($this->getBackPath() . $file);
    }
} 