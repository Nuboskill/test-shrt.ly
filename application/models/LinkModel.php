<?php

class LinkModel extends CI_Model {

    public $hash;
    public $url;

    public function __construct()
    {
        $this->tableName = 'links';
    }

    public function searchByHash($hash, $select = [])
    {
        if(count($select)) $this->db->select($select);
        $query = $this->db->where('hash', $hash)->limit(1)->get($this->tableName);
        return $query->result() ? $query->result()[0] : null;
    }

    public function searchByUrl($url, $select = [])
    {
        if(count($select)) $this->db->select($select);
        $query = $this->db->where('url', $url)->limit(1)->get($this->tableName);
        return $query->result() ? $query->result()[0] : null;
    }

    public function getLastId()
    {
        $query = $this->db->select('id')->order_by('id', 'DESC')->limit(1)->get($this->tableName);
        return $query->result() ? $query->result()[0]->id : null;
    }

    public function getNextId()
    {
        $lastId = $this->getLastId() ?? 0;
        return $lastId + 1;
    }

    public function insertEntry($data)
    {
        $this->db->insert('links', $data);
    }

}