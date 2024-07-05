<?php

namespace Models;

use PDO;

class Cart extends Model
{
    public $id;
    public $product_id;
    public $quantity;
    public $user_id;
    public $updated_at;
    public $created_at;

    public function __construct()
    {
        parent::__construct();
    }

    public static function all()
    {
        $stmt = self::$db->prepare('SELECT * FROM carts');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Cart');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find($id): Cart
    {
        $stmt = self::$db->prepare('SELECT * FROM carts WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Cart');
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function create($data)
    {
        $stmt = self::$db->prepare('INSERT INTO carts (product_id, quantity, user_id) VALUES (:product_id, :quantity, :user_id)');
        $stmt->bindParam(':product_id', $data['product_id']);
        $stmt->bindParam(':quantity', $data['quantity']);
        $stmt->bindParam(':user_id', $data['user_id']);
        return $stmt->execute();
    }

    public static function update($id, $data)
    {
        $stmt = self::$db->prepare('UPDATE carts SET product_id = :product_id, quantity = :quantity, user_id = :user_id WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':product_id', $data['product_id']);
        $stmt->bindParam(':quantity', $data['quantity']);
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
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'user_id' => $this->user_id
        ];
    }
}
