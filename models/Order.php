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
    // public $product;

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



    public static function sum($column, $condition = null)
    {
        $stmt = self::$db->prepare("SELECT SUM($column) FROM orders $condition");
        $stmt->execute();
        return $stmt->fetchColumn();
    }


    public function save()
    {
        if ($this->id) {
            return $this->update(['Order', 'orders'], $this->id, $this->toArray());
        }
        return $this->create(['Order', 'orders'], $this->toArray());
    }

    public function delete()
    {
        $stmt = self::$db->prepare('DELETE FROM orders WHERE id = :id');
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
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
