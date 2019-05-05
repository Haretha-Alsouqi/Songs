<?php

class Application_Model_DbTable_Artists extends Zend_Db_Table_Abstract
{

    protected $_name = 'artists';

    public function getArtist($id){
        $id = (int) $id;
        $row = $this->fetchRow('id = ' . $id);
        if(!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function addArtist($name){
        $data = array(
            'name' => $name,
        );
        $this->insert($data);
    }

    public function updateArtist($id, $name){
        $data = array(
            'name' => $name,
        );
        $this->update($data, 'id = ' . (int)$id);
    }

    public function deleteArtist($id){
        $this->delete('id = ' . (int)$id);
    }
}

