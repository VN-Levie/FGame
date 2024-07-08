<?php

namespace Models;

use Core\Database;
use PDO;

class User extends Model
{
    public $id;
    public $username;
    public $password;
    public $roles;
    public $email;
    public $updated_at;
    public $created_at;
    public function __construct()
    {
        parent::__construct();
    }

    public static function all()
    {
        $stmt = self::$db->prepare('SELECT * FROM users');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find($id): User
    {
        $stmt = self::$db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
        $stmt->execute();
        return $stmt->fetch();
    }


    public static function where($column, $operator = '=', $value = null)
    {
        $stmt = self::$db->prepare("SELECT * FROM users WHERE $column $operator :value");
        $stmt->bindParam(':value', $value);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function count($column, $operator = '=', $value = null)
    {
        $stmt = self::$db->prepare("SELECT COUNT(*) FROM users WHERE $column $operator :value");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public static function last()
    {
        $stmt = self::$db->prepare('SELECT * FROM users ORDER BY id DESC LIMIT 1');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
        $stmt->execute();
        return $stmt->fetch();
    }


    public function  save()
    {
        if ($this->id) {
            return $this->update(['User', 'users'], $this->id, $this->toArray());
        }
        return $this->create(['User', 'users'], $this->toArray());
    }

    public function delete()
    {
        $stmt = self::$db->prepare('DELETE FROM users WHERE id = :id');
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    //get user by username
    public static function getByUsername($username)
    {
        // $db = new Database();
        // $db = $db->connect();
        $stmt = self::$db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
        $stmt->execute();
        return $stmt->fetch();
    }



    public function toArray()
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
            'roles' => $this->roles,
            'email' => $this->email
        ];
    }

    //get roles
    public function getRoles()
    {
        // echo $this->roles;
        $role = 'User';
        // 0: user, 1: seller, 3: mod, 5: s-mod, 8: admin, 9: s-admin
        $role = match ($this->roles) {
            0 => 'User',
            1 => '<span class="text-info">Seller</span>',
            3 => '<span class="text-primary">Moderator</span>',
            5 => '<span style="font-weight: bold" class="text-warning">Super Moderator</span>',
            8 => '<span style="font-weight: bold" class="text-danger">Admin</span>',
            9 => '<span style="color: darkviolet; font-weight: bold">Super Admin</span>',
            default => 'User',
        };
        return $role;
    }

    //check role($str_role)
    public function checkRole($str_role)
    {
        if ($this->roles == 9) { //all
            return true;
        }
        if ($this->roles == 8 && $str_role != 's-admin') {
            return true;
        }
        if ($str_role == 'mod' && ($this->roles == 3 || $this->roles == 5)) {
            return true;
        }
        if ($str_role == 'user' && $this->roles == 0) {
            return true;
        }
        if ($str_role == 'seller' && $this->roles == 1) {
            return true;
        }
        return false;
    }
}
