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
    public $baned = 0;
    public $email;
    public $updated_at;
    public $created_at;

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
