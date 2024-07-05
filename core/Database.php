<?php

namespace Core;

use PDO;
use PDOException;

class Database
{
    private $host = 'localhost';
    private $db_name = 'hieu_fgame';
    private $username = 'root';
    private $password = 'Matkhau123';
    private $conn;

    public function connect()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo 'Connected successfully';
        } catch (PDOException $e) {
            // echo 'Connection Error: ' . $e->getMessage();
            die('Connection Error: ' . $e->getMessage());
        }
        return $this->conn;
    }

    public $old_db = null;
    public function test()
    {
        try {
            $db = new PDO("mysql:host=localhost;dbname=$this->db_name", $this->username, $this->password);
            $db->exec("set names utf8mb4");
            return $db;
        } catch (PDOException $e) {
            //echo $e->getMessage();
            echo 'Loi ket noi';
            exit;
        }
    }
}
