<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-15
 * @Time: 10:04
 * @QQ: 259522
 * @FileName: CategoryTable.php
 */

namespace XtTree\Model;

use Traversable;
use Zend\Db\Sql\Select;
use Zend\Stdlib\ArrayUtils;
use XtTree\Exception\InvalidArgumentException;
use XtTree\Exception\RuntimeException;
use Zend\Db\Sql\Expression;

class CategoryTable extends AbstractTreeTableGateway
{
    /**
     * @var string
     */
    protected $table = 'xt_category';
    /**
     * @var string
     */
    protected $catNameColumn = 'cat_name';
    /**
     * @var string
     */
    protected $parentIdColumn = 'parent_id';
    /**
     * @var string
     */
    protected $parentArrayColumn = 'parent_id_array';
    /**
     * @var string
     */
    protected $childArrayColumn = 'child_id_array';
    /**
     * @var string
     */
    protected $pathDelimiter = '/';
    /**
     * @var string
     */
    protected $replace = 'REPLACE(%s,\'%s\',\'%s\')';
    /**
     * @var array
     */
    protected $icon = ['│', '┝', '┕'];
    /**
     * @var array
     */
    protected $nodeTree = [];
    /**
     * @var string
     */
    protected $nbsp = '&nbsp;';

    /**
     * @param array $icon
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @param string $nbsp
     * @return $this
     */
    public function setNbsp($nbsp)
    {
        $this->nbsp = $nbsp;
        return $this;
    }

    /**
     * @param int $id
     * @param string $adds
     * @param bool $options
     * @param array $columns
     * @return array
     */
    public function getNodeTree($id = 0, $adds = '', $options = true, $columns = ['*'])
    {
        $child = $this->getChildNodeByParentId($id, $columns);
        if (($total = count($child)) > 0) {
            $number = 1;
            foreach ($child as $val) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds . $j : '';
                $nbsp = str_replace('&nbsp;', '　', $this->nbsp);
                $catName = $spacer . $nbsp . $val[$this->catNameColumn];
                if ($options) {
                    $this->nodeTree[$val[$this->primaryKey]] = $catName;
                } else {
                    $val[$this->catNameColumn] = $catName;
                    $this->nodeTree[] = $val;
                }
                $this->{__FUNCTION__}($val[$this->primaryKey], $adds . $k . $nbsp, $options, $columns);
                $number++;
            }
        }
        return $this->nodeTree;
    }

    /**
     * @param $id
     * @param array $columns
     * @return array
     */
    protected function getChildNodeByParentId($id, $columns = ['*'])
    {
        return $this->select(function (Select $select) use ($id, $columns) {
            $select->columns($columns);
            $select->where([$this->parentIdColumn => $id]);
        })->toArray();
    }

    /**
     * @添加节点
     * <code>
     *      $table = addNode(['name'=>'dd']);
     *      $table = addNode(['name'=>'dd'],1);
     * </code>
     *
     * @param $node
     * @param int $toId
     * @return mixed
     */
    public function addNode($node, $toId = 0)
    {
        $node = $this->resultSetExtract($node);
        $node[$this->parentIdColumn] = $toId = (int)$toId;
        $node[$this->depthColumn] = 0;
        $node[$this->childArrayColumn] = $node[$this->parentArrayColumn] = '';
        try {
            $this->getConnection()->beginTransaction();
            if ($this->insert($node) < 1) {
                throw new RuntimeException('node 新增失败');
            }
            $lastInsertValue = $this->lastInsertValue;
            if ($toId == $lastInsertValue) {
                $node[$this->parentIdColumn] = $toId = 0;
            }
            if ($toId > 0) {
                $toNode = $this->getOneByColumn($toId, $this->primaryKey, [$this->depthColumn, $this->parentArrayColumn]);
                $toNodePatentArray = $this->pathToArray($toNode[$this->parentArrayColumn]);
                $toNodePatentArray[] = $toId;
                if ($this->update([
                        $this->childArrayColumn => new Expression(
                            'CONCAT(' . $this->childArrayColumn . ',\'' . $lastInsertValue . $this->pathDelimiter . '\')'
                        )
                    ], [$this->primaryKey => $toNodePatentArray]) < 1
                ) {
                    throw new RuntimeException('node 更新失败');
                }
                $node[$this->parentArrayColumn] = $toNode[$this->parentArrayColumn];
                $node[$this->depthColumn] = $toNode[$this->depthColumn];
            }
            $updateData = [
                $this->parentIdColumn => $toId,
                $this->parentArrayColumn => $node[$this->parentArrayColumn] . $toId . $this->pathDelimiter,
                $this->childArrayColumn => $lastInsertValue . $this->pathDelimiter,
                $this->depthColumn => $node[$this->depthColumn] + 1
            ];
            if ($this->update($updateData, [$this->primaryKey => $lastInsertValue]) < 1) {
                throw new RuntimeException('node 更新失败');
            }
            $this->getConnection()->commit();
        } catch (RuntimeException $e) {
            $lastInsertValue = 0;
            $this->getConnection()->rollback();
        } catch (InvalidArgumentException $e) {
            $lastInsertValue = 0;
            $this->getConnection()->rollback();
        }
        return $lastInsertValue;
    }

    /**
     * @移动节点
     * <code>
     *      $table = moveNode(2,1);
     *      $table = moveNode(3);
     *      $table = moveNode(4,0);
     * </code>
     *
     * @param int $formId
     * @param int $toId
     */
    public function moveNode($formId, $toId = 0)
    {
        $toId = (int)$toId;
        $formNode = $this->getOneByColumn($formId, $this->primaryKey, [$this->parentIdColumn, $this->parentArrayColumn, $this->childArrayColumn, $this->depthColumn]);
        if ((int)$formNode[$this->parentIdColumn] === $toId) {
            throw new RuntimeException('node 无须移动');
        }
        $toNode = [];
        $toNodeParentColumn = '';
        if ($toId > 0) {
            if (strpos($formNode[$this->childArrayColumn], $this->pathDelimiter . $toId . $this->pathDelimiter) > -1) {
                throw new RuntimeException('node 不能移动到子节点');
            }
            $toNode = $this->getOneByColumn($toId, $this->primaryKey, [$this->parentIdColumn, $this->parentArrayColumn, $this->childArrayColumn, $this->depthColumn]);
            $toNodeParentColumn = $toNode[$this->parentArrayColumn];
        }
        $toNode[$this->parentArrayColumn] = $toNodeParentColumn . $toId . $this->pathDelimiter;
        $toParentNode = isset($toNode[$this->parentArrayColumn]) ? $this->pathToArray($toNode[$this->parentArrayColumn]) : [];
        try {
            $this->getConnection()->beginTransaction();
            $FormChildNodeArray = $this->pathToArray($formNode[$this->childArrayColumn]);
            //删除原父节点中的子节点
            $formParentNode = [];
            if ((int)$formNode[$this->parentIdColumn] !== 0) {
                $formParentNode = $this->pathToArray($formNode[$this->parentArrayColumn]);
                $upParentNode = array_diff($formParentNode, $toParentNode);
                if (count($upParentNode) > 0 && count($FormChildNodeArray) > 0) {
                    $replaceString = $this->quoteIdentifier($this->childArrayColumn);
                    foreach ($FormChildNodeArray as $childNodeId) {
                        $replaceString = sprintf($this->replace, $replaceString, $this->pathDelimiter . $childNodeId . $this->pathDelimiter, $this->pathDelimiter);
                    }
                    $this->update([
                        $this->childArrayColumn => new Expression($replaceString)
                    ], [$this->primaryKey => $upParentNode]);
                }
            }
            //更新层次
            $toNode[$this->depthColumn] = isset($toNode[$this->depthColumn]) ? $toNode[$this->depthColumn] : 0;
            $depth = $toNode[$this->depthColumn] - $formNode[$this->depthColumn] + 1;
            $string = sprintf($this->replace, $this->quoteIdentifier($this->parentArrayColumn), $formNode[$this->parentArrayColumn], $toNode[$this->parentArrayColumn]);
            $this->update([
                $this->parentArrayColumn => new Expression($string),
                $this->depthColumn => new Expression($this->quoteIdentifier($this->depthColumn) . '+' . $depth)
            ], [$this->primaryKey => $FormChildNodeArray]);

            //在新父节点中增加子节点
            $upChildNode = array_diff($toParentNode, $formParentNode);
            if (count($upChildNode) > 0) {
                $this->update([
                        $this->childArrayColumn => new Expression('CONCAT(' . $this->quoteIdentifier($this->childArrayColumn) . ',\'' . $formNode[$this->childArrayColumn] . '\')')
                    ],
                    [$this->primaryKey => $upChildNode]);
            }
            $this->update([
                $this->parentIdColumn => $toId
            ], [
                $this->primaryKey => $formId
            ]);
            $this->getConnection()->commit();
        } catch (RuntimeException $e) {
            $this->getConnection()->rollback();
        } catch (InvalidArgumentException $e) {
            $this->getConnection()->rollback();
        }
    }

    /**
     * @删除节点
     * <code>
     *      $table->deleteNodeById(1);
     *      $table->deleteNodeById([2,3]);
     * </code>
     *
     * @param int|array $idOrIds
     */
    public function deleteNodeById($idOrIds)
    {
        if ($idOrIds instanceof Traversable) {
            $idOrIds = ArrayUtils::iteratorToArray($idOrIds);
        }
        if (is_array($idOrIds)) {
            foreach ($idOrIds as $id) {
                $this->{__FUNCTION__}($id);
            }
            return;
        }
        $node = $this->getOneByColumn($idOrIds, $this->primaryKey, [$this->parentIdColumn, $this->parentArrayColumn, $this->childArrayColumn]);
        try {
            $this->getConnection()->beginTransaction();

            $nodePatentArray = $this->pathToArray($node[$this->parentArrayColumn]);
            //var_dump($nodePatentArray);
            //更新父节点的子节点栏目值
            if (count($nodePatentArray) > 0) {
                if ($this->update([
                        $this->childArrayColumn => new Expression(
                            sprintf($this->replace, $this->quoteIdentifier($this->childArrayColumn), $this->pathDelimiter . $idOrIds . $this->pathDelimiter, $this->pathDelimiter)
                        )
                    ], [$this->primaryKey => $nodePatentArray]) < 1
                ) {
                    throw new RuntimeException('node 子节点更新失败!');
                }
            }

            //更新子节点的父节点栏目值
            $nodeChildArray = $this->pathToArray($node[$this->childArrayColumn]);
            array_shift($nodeChildArray);
            if (count($nodeChildArray) > 0) {
                if ($this->update([
                        $this->parentIdColumn => $node[$this->parentIdColumn],
                        $this->parentArrayColumn => new Expression(
                            sprintf($this->replace, $this->quoteIdentifier($this->parentArrayColumn), $this->pathDelimiter . $idOrIds . $this->pathDelimiter, $this->pathDelimiter)
                        ),
                        $this->depthColumn => new Expression($this->quoteIdentifier($this->depthColumn) . '-1'),
                    ], [$this->primaryKey => $nodeChildArray]) < 1
                ) {
                    throw new RuntimeException('node 父节点更新失败!');
                }
            }
            //删除节点信息
            if ($this->delete([$this->primaryKey => $idOrIds]) < 1) {
                throw new RuntimeException('node 删除失败!');
            }
            $this->getConnection()->commit();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
            $this->getConnection()->rollback();
        } catch (InvalidArgumentException $e) {
            echo $e->getMessage();
            $this->getConnection()->rollback();
        }
        return;
    }

    /**
     * 根据节点id 删除所有子元素
     *
     * <code>
     *      $table->deleteChildNodeById(1);
     *      $table->deleteChildNodeById(1,false);
     * </code>
     *
     * @param int | Traversable | array $idOrIds
     * @param bool $itself
     * @return int | null
     */
    public function deleteChildNodeById($idOrIds, $itself = true)
    {
        if ($idOrIds instanceof Traversable) {
            $idOrIds = ArrayUtils::iteratorToArray($idOrIds);
        }
        if (is_array($idOrIds)) {
            foreach ($idOrIds as $id) {
                $this->{__FUNCTION__}($id, $itself);
            }
            return;
        }
        $node = $this->getOneByColumn($idOrIds, $this->primaryKey, [$this->parentIdColumn, $this->parentArrayColumn, $this->childArrayColumn]);
        $nodeChildArray = $this->pathToArray($node[$this->childArrayColumn]);
        $upParentNode = $this->pathToArray($node[$this->parentArrayColumn]);
        if (!$itself) {
            array_shift($nodeChildArray);
            $upParentNode[] = $idOrIds;
        }
        try {
            $this->getConnection()->beginTransaction();
            //更新子节点
            if (count($nodeChildArray) > 0) {
                if (count($upParentNode) > 0) {
                    $replaceString = $this->quoteIdentifier($this->childArrayColumn);
                    foreach ($nodeChildArray as $childNodeId) {
                        $replaceString = sprintf($this->replace, $replaceString, $this->pathDelimiter . $childNodeId . $this->pathDelimiter, $this->pathDelimiter);
                    }
                    $this->update([
                        $this->childArrayColumn => new Expression($replaceString)
                    ], [$this->primaryKey => $upParentNode]);
                }
                //删除节点
                $this->delete([$this->primaryKey => $nodeChildArray]);
            }
            $this->getConnection()->commit();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
            $this->getConnection()->rollback();
        } catch (InvalidArgumentException $e) {
            echo $e->getMessage();
            $this->getConnection()->rollback();
        }
        return;
    }

    /**
     * 根据节点Id 获取父节点
     * <code>
     *      $table->getParentNodeById(1);
     *      $table->getParentNodeById(1,2);
     * </code>
     *
     * @param $id
     * @param null $depth
     * @param string $order
     * @param array $columns
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getParentNodeById($id, $depth = null, $order = 'ASC', $columns = ['*'])
    {
        $node = $this->getOneByColumn($id, $this->primaryKey, [$this->parentIdColumn, $this->depthColumn, $this->parentArrayColumn]);
        if ($node[$this->parentIdColumn] == '0') {
            return [];
        }
        return $this->select(function (Select $select) use ($depth, $order, $columns, $node) {
            $select->columns($columns);
            $parentArray = $this->pathToArray($node[$this->parentArrayColumn]);
            $select->where([$this->primaryKey => $parentArray]);
            if ($depth !== null) {
                $select->where($this->depthColumn, $node[$this->depthColumn] - $depth);
            }
            $select->order([$this->depthColumn => $order]);
        });
    }

    /**
     * 根据节点Id 获取子节点
     * <code>
     *      $table->getChildNodeById(1);
     *      $table->getChildNodeById(1,2);
     * </code>
     *
     * @param $id
     * @param null $depth
     * @param string $order
     * @param array $columns
     * @return array|mixed|\Zend\Db\ResultSet\ResultSet
     */
    public function getChildNodeById($id, $depth = null, $order = 'ASC', $columns = ['*'])
    {
        $node = $this->getOneByColumn($id, $this->primaryKey, [$this->depthColumn, $this->childArrayColumn]);
        $nodeChildArray = $this->pathToArray($node[$this->childArrayColumn]);
        array_shift($nodeChildArray);
        if (empty($nodeChildArray)) {
            return [];
        }
        return $this->select(function (Select $select) use ($depth, $order, $columns, $nodeChildArray, $node) {
            $select->columns($columns);
            $select->where([$this->primaryKey => $nodeChildArray]);
            if ($depth !== null) {
                $select->where($this->depthColumn, $depth + $node[$this->depthColumn]);
            }
            $select->order([$this->depthColumn => $order]);
        });
    }

    /**
     * @param string $path
     * @param string $delimiter
     * @return array
     */
    protected function pathToArray($path, $delimiter = null)
    {
        return array_filter(explode(($delimiter) ?: $this->pathDelimiter, $path));
    }
} 