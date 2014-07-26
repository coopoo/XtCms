<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-16
 * @Time: 10:46
 * @QQ: 259522
 * @FileName: TestController.php
 */

namespace XtTree\Controller;


use Zend\Mvc\Controller\AbstractActionController;

class TestController extends AbstractActionController
{
    public function indexAction()
    {
        echo $this->params('controller'), '<br>';

        $categoryTable = $this->getServiceLocator()->get('XtTree\Model\CategoryTable');
        //var_dump($categoryTable);
//        $categoryTable->addNode(['cat_name'=>'ddd'],1);
//        $categoryTable->deleteNodeById([9,10]);
//        $categoryTable->deleteChildNodeById(2,false);
//        $result = $categoryTable->getChildNodeById(2);
//        $categoryTable->moveNode(1,2);
//        $categoryTable->deleteChildNodeById([3,2]);

        $result = $categoryTable->getNodeTree();
        var_dump($result);
        exit;
    }
} 