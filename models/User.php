<?php

namespace Models;

use Core\Database;
use PDO;

class User extends Model
{
    public $id;
    public $username;
    public $password;
    public $role;
    public $baned = 0;
    public $soft_delete = 0;
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
        $role = match ($this->role) {
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
        if ($this->role == 9) { //all
            return true;
        }
        if ($this->role == 8 && $str_role != 's-admin') {
            return true;
        }
        if ($this->role == 5 && $str_role == 's-mod') {
            return true;
        }
        if ($str_role == 'mod' && ($this->role == 3 || $this->role == 5)) {
            return true;
        }
        if ($str_role == 'user' && $this->role == 0) {
            return true;
        }
        if ($str_role == 'seller' && $this->role == 1) {
            return true;
        }
        return false;
    }

    public function getName()
    {
        if ($this->soft_delete == 1) { //all
            return '<i class="fa-solid fa-user-xmark"></i><s>[' . $this->username . ' - Deleted]</s>';
        }
        if ($this->baned == 1) { //all
            return '<i class="fa-solid fa-lock"></i> <s>' . $this->username . '</s>';
        }
        // echo $this->roles;
        $name = 'User';
        // 0: user, 1: seller, 3: mod, 5: s-mod, 8: admin, 9: s-admin
        $name = match ($this->role) {
            0 => 'User',
            1 => '<span class="text-info">' . $this->username . '</span>',
            3 => '<span class="text-primary">Moderator</span>',
            5 => '<span style="font-weight: bold" class="text-warning">' . $this->username . '</span>',
            8 => '<span style="font-weight: bold" class="text-danger">' . $this->username . '</span>',
            9 => '<span style="color: darkviolet; font-weight: bold">' . $this->username . '</span>',
            default => 'User',
        };
        return $name;
    }

    //products()
    public function products()
    {
        return Product::where('user_id', $this->id);
    }

    // sum total
    public function sumTotal()
    {
        $products = $this->products();
        $total = 0;
        foreach ($products as $product) {
            $total += $product->sumTotal();
        }
        return $total;
    }

    //orders
    public function orders()
    {
        $products = $this->products();
        $orders = [];
        foreach ($products as $product) {
            $orders = array_merge($orders, $product->orders());
        }
        return $orders;
    }
}
