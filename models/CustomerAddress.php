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

    public static function create($data)
    {
        $stmt = self::$db->prepare('INSERT INTO customer_address (customer_name, phone, email, address, user_id) VALUES (:customer_name, :phone, :email, :address, :user_id)');
        $stmt->bindParam(':customer_name', $data['customer_name']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':user_id', $data['user_id']);
        return $stmt->execute();
    }

    public static function update($id, $data)
    {
        $stmt = self::$db->prepare('UPDATE customer_address SET customer_name = :customer_name, phone = :phone, email = :email, address = :address, user_id = :user_id WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':customer_name', $data['customer_name']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':user_id', $data['user_id']);
        return $stmt->execute();
    }

    public function save()
    {
        if ($this->id) {
            return $this->update($this->id, $this->toArray());
        }
        return $this->create($this->toArray());
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
