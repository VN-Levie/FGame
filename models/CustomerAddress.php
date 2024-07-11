<?php

namespace Models;

use PDO;

class CustomerAddress extends Model
{
    public $id;
    public $customer_name;
    public $phone;
    public $email;
    public $address;
    public $user_id;
    public $updated_at;
    public $created_at;

    public function __construct()
    {
        parent::__construct();
    }

    public static function all()
    {
        $stmt = self::$db->prepare('SELECT * FROM customer_address');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\CustomerAddress');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find($id): CustomerAddress
    {
        $stmt = self::$db->prepare('SELECT * FROM customer_address WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\CustomerAddress');
        $stmt->execute();
        return $stmt->fetch();
    }

  



    public function save()
    {
        if ($this->id) {
            return $this->update(['CustomerAddress', 'customer_address'], $this->id, $this->toArray());
        }
        return $this->create($this->toArray());
    }

    public function delete()
    {
        $stmt = self::$db->prepare('DELETE FROM customer_address WHERE id = :id');
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function toArray()
    {
        return [
            'customer_name' => $this->customer_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'user_id' => $this->user_id
        ];
    }
}
