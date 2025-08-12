<?php

class Cabang_models
{
    private $table = 'tb_cabang';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }
    public function getAllCabang()
    {
        $sql = "SELECT * FROM $this->table ORDER BY id_cabang DESC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }
}
