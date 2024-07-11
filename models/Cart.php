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



    public function save()
    {
        if ($this->id) {
            return $this->update($this->id, $this->toArray());
        }
        return $this->create($this->toArray());
    }

    //delete function
    public function delete()
    {
        $stmt = self::$db->prepare('DELETE FROM carts WHERE id = :id');
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
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
