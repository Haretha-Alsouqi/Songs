<?php

class CategoryController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/categories.js');
    }

    public function indexAction()
    {
        // action body
    }

    public function addAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        if ($this->getRequest()->isPost()) {
            $name = $this->getRequest()->getPost('name');
            $categories = new Application_Model_DbTable_Categories();
            $categories->addCategory($name);
        }
    }

    public function editAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        if ($this->getRequest()->isPost()) {
            $id = $this->getRequest()->getPost('id');
            $name = $this->getRequest()->getPost('name');
            $categories = new Application_Model_DbTable_Categories();
            $categories->updateCategory($id, $name);
        } else {
            $id = $this->getRequest()->getParam('id');
            $categories = new Application_Model_DbTable_Categories();
            echo Zend_Json::encode($categories->getCategory($id));
        }
    }

    public function deleteAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $id = $this->getRequest()->getParam('id');
        $categories = new Application_Model_DbTable_Categories();
        $categories->deleteCategory($id);
    }

    public function dataAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        
        $categories = new Application_Model_DbTable_Categories();
        $categories_obj = $categories->fetchAll();
        echo Zend_Json::encode(array('data' => $categories_obj->toArray()));
    }


}









