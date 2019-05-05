<?php

class ArtistController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/artists.js');
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
            $artists = new Application_Model_DbTable_Artists();
            $artists->addArtist($name);
        }
    }

    public function editAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        if ($this->getRequest()->isPost()) {
            $id = $this->getRequest()->getPost('id');
            $name = $this->getRequest()->getPost('name');
            $artists = new Application_Model_DbTable_Artists();
            $artists->updateArtist($id, $name);
        } else {
            $id = $this->getRequest()->getParam('id');
            $artists = new Application_Model_DbTable_Artists();
            echo Zend_Json::encode($artists->getArtist($id));
        }
    }

    public function deleteAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $id = $this->getRequest()->getParam('id');
        $artists = new Application_Model_DbTable_Artists();
        $artists->deleteArtist($id);
    }

    public function dataAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        
        $artists = new Application_Model_DbTable_Artists();
        $artists_obj = $artists->fetchAll();
        echo Zend_Json::encode(array('data' => $artists_obj->toArray()));
    }

}
