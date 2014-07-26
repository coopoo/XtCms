<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-06-27
 * @Time: 10:36
 * @QQ: 259522
 * @FileName: IndexController.php
 */

namespace XtDb\Controller;

use XtTool\Tool\Tool;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    const DB_ROUTE = 'XtDb-admin';
    protected $dbBackAndRestore = null;

    public function indexAction()
    {
        $dbBackAndRestore = $this->getDbBackAndRestore();
        $dbState = $dbBackAndRestore->getDbState();
        $backFile = $dbBackAndRestore->getBackFileList();
        return ['dbState' => $dbState, 'backFile' => $backFile];
    }

    public function backAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $options = $request->getPost()->toArray();
            $dbBackAndRestore = $this->getDbBackAndRestore();
            $dbBackAndRestore->setOptions($options)->back();
        }
        return $this->redirect()->toRoute(static::DB_ROUTE);
    }

    public function restoreAction()
    {
        $id = $this->params('id');
        $file = Tool::decode($id);
        $this->getDbBackAndRestore()->restore($file);
        return $this->redirect()->toRoute(static::DB_ROUTE);
    }

    public function unlinkAction()
    {
        $id = $this->params('id');
        $file = Tool::decode($id);
        $this->getDbBackAndRestore()->unlink($file);
        return $this->redirect()->toRoute(static::DB_ROUTE);
    }

    public function truncateAction()
    {
        $id = $this->params('id');
        $dbBackAndRestore = $this->getDbBackAndRestore();
        $dbBackAndRestore->truncateTable($id);
        return $this->redirect()->toRoute(static::DB_ROUTE);
    }

    public function dropAction()
    {
        $id = $this->params('id');
        $dbBackAndRestore = $this->getDbBackAndRestore();
        $dbBackAndRestore->dropTable($id);
        return $this->redirect()->toRoute(static::DB_ROUTE);
    }

    protected function getDbBackAndRestore()
    {
        if (!$this->dbBackAndRestore) {
            $this->dbBackAndRestore = $this->getServiceLocator()->get('XtDb\Model\DbBackAndRestore');
        }
        return $this->dbBackAndRestore;
    }

} 