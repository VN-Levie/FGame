<?php

namespace Models;

use PDO;





class Order extends Model
{
    public $id;
    public $product_id;
    public $quantity;
    public $product_price;
    public $total;
    public $status;
    public $customer_note;
    public $seller_note;
    public $user_id;
    public $updated_at;
    public $created_at;
    public $product;

    public function __construct()
    {
        parent::__construct();
    }

    public static function all()
    {
        $stmt = self::$db->prepare('SELECT * FROM orders');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Order');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find($id): Order
    {
        $stmt = self::$db->prepare('SELECT * FROM orders WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Order');
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function create($data)
    {
        $stmt = self::$db->prepare('INSERT INTO orders (product_id, quantity, product_price, total, status, customer_note, seller_note, user_id) VALUES (:product_id, :quantity, :product_price, :total, :status, :customer_note, :seller_note, :user_id)');
        $stmt->bindParam(':product_id', $data['product_id']);
        $stmt->bindParam(':quantity', $data['quantity']);
        $stmt->bindParam(':product_price', $data['product_price']);
        $stmt->bindParam(':total', $data['total']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':customer_note', $data['customer_note']);
        $stmt->bindParam(':seller_note', $data['seller_note']);
        $stmt->bindParam(':user_id', $data['user_id']);
        return $stmt->execute();
    }

    public static function update($id, $data)
    {
        $stmt = self::$db->prepare('UPDATE orders SET product_id = :product_id, quantity = :quantity, product_price = :product_price, total = :total, status = :status, customer_note = :customer_note, seller_note = :seller_note, user_id = :user_id WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':product_id', $data['product_id']);
        $stmt->bindParam(':quantity', $data['quantity']);
        $stmt->bindParam(':product_price', $data['product_price']);
        $stmt->bindParam(':total', $data['total']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':customer_note', $data['customer_note']);
        $stmt->bindParam(':seller_note', $data['seller_note']);
        $stmt->bindParam(':user_id', $data['user_id']);
        return $stmt->execute();
    }

    public static function sum($column, $condition = null)
    {
        $stmt = self::$db->prepare("SELECT SUM($column) FROM orders $condition");
        $stmt->execute();
        return $stmt->fetchColumn();
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
            'product_price' => $this->product_price,
            'total' => $this->total,
            'status' => $this->status,
            'customer_note' => $this->customer_note,
            'seller_note' => $this->seller_note,
            'user_id' => $this->user_id
        ];
    }
}
