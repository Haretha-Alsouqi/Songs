<?php

class Application_Model_DbTable_AlbumCategory extends Zend_Db_Table_Abstract
{

    protected $_name = 'album_category';

    public function getAlbumCategory($id){
        $id = (int) $id;
        $row = $this->fetchAll('album_id = ' . $id);
        if(!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function addAlbumCategory($album_id, $category_id){
        $data = array(
            'album_id' => $album_id,
            'category_id' => $category_id,
        );
        $this->insert($data);
    }

    public function deleteAlbumCategory($album_id){
        $this->delete('album_id = ' . (int)$album_id);
    }
}

