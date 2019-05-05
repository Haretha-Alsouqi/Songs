<?php

class Application_Model_DbTable_Albums extends Zend_Db_Table_Abstract
{

    protected $_name = 'albums';

    public function getAlbum($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function addAlbum($artist, $title, $image_url)
    {
        $data = array(
            'artist_id' => $artist,
            'title' => $title,
            'image_url' => $image_url,
        );
        return $this->insert($data);
    }

    public function updateAlbum($id, $artist, $title, $image_url)
    {
        $data = array(
            'artist_id' => $artist,
            'title' => $title,
            'image_url' => $image_url,
        );
        $this->update($data, 'id = ' . (int) $id);
    }

    public function deleteAlbum($id)
    {
        $this->delete('id = ' . (int) $id);
    }

}
