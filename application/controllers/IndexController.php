<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/albums.js');
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
            $artist = $this->getRequest()->getPost('artist');
            $title = $this->getRequest()->getPost('title');
            $image_url = $this->getRequest()->getPost('image_url');
            $categories = $this->getRequest()->getPost('category');
            $albums = new Application_Model_DbTable_Albums();
            $album_id = $albums->addAlbum($artist, $title, $image_url);
            
            $album_category = new Application_Model_DbTable_AlbumCategory();
            foreach ($categories as $category_id) {
                $album_category->addAlbumCategory($album_id, $category_id);
            }
        }
    }

    public function editAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        if ($this->getRequest()->isPost()) {
            $id = $this->getRequest()->getPost('id');
            $artist = $this->getRequest()->getPost('artist');
            $title = $this->getRequest()->getPost('title');
            $image_url = $this->getRequest()->getPost('image_url');
            $categories = $this->getRequest()->getPost('category');
            $albums = new Application_Model_DbTable_Albums();
            $albums->updateAlbum($id, $artist, $title, $image_url);
            
            $album_category = new Application_Model_DbTable_AlbumCategory();
            $album_category->deleteAlbumCategory($id);
            foreach ($categories as $category_id) {
                $album_category->addAlbumCategory($id, $category_id);
            }
        } else {
            $id = $this->getRequest()->getParam('id');
            $albums = new Application_Model_DbTable_Albums();
            $album_category = new Application_Model_DbTable_AlbumCategory();
            $data_array = ['album'=>$albums->getAlbum($id), 'album_category'=>$album_category->getAlbumCategory($id)];
            echo Zend_Json::encode($data_array);
        }
    }

    public function deleteAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $id = $this->getRequest()->getParam('id');
        $albums = new Application_Model_DbTable_Albums();
        $albums->deleteAlbum($id);
    }

    public function dataAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        $albums = new Application_Model_DbTable_AlbumsFullInfo();
        $albums_obj = $albums->fetchAll();
        echo Zend_Json::encode(array('data' => $albums_obj->toArray()));
    }

    public function uploadAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        //$this->_helper->layout->disableLayout();

        $fileName = $_FILES["file"]["name"];
        $unique = uniqid();
        $fname = $fileName . $unique;
        $url = $this->view->baseUrl() . "/uploads/$fname";

        move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/songs/public/uploads/$fname");

        die('{"jsonrpc" : "2.0", "result" : {"url": "' . $url . '"}}');
    }

}
