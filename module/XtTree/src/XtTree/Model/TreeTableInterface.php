<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-15
 * @Time: 9:30
 * @QQ: 259522
 * @FileName: TreeTableInterface.php
 */

namespace XtTree\Model;


interface TreeTableInterface
{
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
    public function addNode($node, $toId = 0);

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
    public function moveNode($formId, $toId = 0);

    /**
     * @删除节点
     * <code>
     *      $table->deleteNodeById(1);
     *      $table->deleteNodeById([2,3]);
     * </code>
     *
     * @param int|array $idOrIds
     */
    public function deleteNodeById($idOrIds);

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
    public function deleteChildNodeById($idOrIds, $itself = true);

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
     * @return mixed
     */
    public function getParentNodeById($id, $depth = null, $order = 'ASC', $columns = ['*']);

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
     * @return mixed
     */
    public function getChildNodeById($id, $depth = null, $order = 'ASC', $columns = ['*']);

} 