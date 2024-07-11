<?php

namespace Core;

use PDO;
use PDOException;
use View;

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
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8mb4', $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo 'Connected successfully';
        } catch (PDOException $e) {           
            return View::abort(500, 'Connection Error: ' . $e->getMessage());
        }
        return $this->conn;
    }
}
