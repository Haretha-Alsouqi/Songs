<?php

class Application_Model_DbTable_Categories extends Zend_Db_Table_Abstract
{

    protected $_name = 'categories';

    public function getCategory($id){
        $id = (int) $id;
        $row = $this->fetchRow('id = ' . $id);
        if(!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function addCategory($name){
        $data = array(
            'name' => $name,
        );
        $this->insert($data);
    }

    public function updateCategory($id, $name){
        $data = array(
            'name' => $name,
        );
        $this->update($data, 'id = ' . (int)$id);
    }

    public function deleteCategory($id){
        $this->delete('id = ' . (int)$id);
    }
}

