<?php

class User_models
{
    private $db;
    private $table = 'tb_user';

    public function __construct()
    {
        require_once '../app/config/config.php';
        $this->db = new Database();
    }

    public function getUserByUsername($username)
    {
        $this->db->query("SELECT * FROM tb_user WHERE username = :username");
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    public function getAllUsers()
    {
        $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $this->db->resultSet();
    }

    public function tambahDataUser($data)
    {
        // Cek apakah username sudah ada
        $this->db->query("SELECT username FROM {$this->table} WHERE username = :username");
        $this->db->bind('username', $data['username']);
        $this->db->execute();

        if ($this->db->rowCount() > 0) {
            // Username sudah ada
            return 'duplicate';
        }
        $query = "INSERT INTO {$this->table} (username, password, role, name, cabang, status)
              VALUES (:username, :password, :role, :name, :cabang, :status)";

        $this->db->query($query);
        $this->db->bind('username', $data['username']);
        $this->db->bind('password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind('role', $data['role']);
        $this->db->bind('name', $data['name']);
        $this->db->bind('cabang', $data['cabang']);
        $this->db->bind('status', 'aktif');

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function updateDataUser($data)
    {
        $query = "UPDATE {$this->table}
              SET username = :username,
                  role = :role,
                  name = :name,
                  cabang = :cabang,
                  status = :status
               WHERE id = :id";

        $this->db->query($query);
        $this->db->bind('username', $data['username']);
        $this->db->bind('role', $data['role']);
        $this->db->bind('name', $data['name']);
        $this->db->bind('cabang', $data['cabang']);
        $this->db->bind('status', $data['status']);
        $this->db->bind('id', $data['id']);

        $this->db->execute();

        return $this->db->rowCount();
    }


    public function updateDataPass($data)
    {
        $query = "UPDATE {$this->table}
              SET password = :password
               WHERE id = :id";

        $this->db->query($query);
        $this->db->bind('password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind('id', $data['id']);

        $this->db->execute();

        return $this->db->rowCount();
    }



    public function updateStatus($id, $status)
    {
        $this->db->query("UPDATE {$this->table} SET status = :status WHERE id = :id");
        $this->db->bind('status', $status);
        $this->db->bind('id', $id);
        return $this->db->execute();
    }
}
