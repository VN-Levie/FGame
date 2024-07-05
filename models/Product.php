<?php

namespace Models;

use PDO;

class Product extends Model
{
    public $id;
    public $name;
    public $description;
    public $price;
    public $stock;
    public $category_id;
    public $status;
    public $pinned;
    public $updated_at;
    public $created_at;

    public function __construct()
    {
        parent::__construct();
    }

    public static function all()
    {
        $stmt = self::$db->prepare('SELECT * FROM products');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Product');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find($id): Product
    {
        $stmt = self::$db->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Product');
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function create($data)
    {
        $stmt = self::$db->prepare('INSERT INTO products (name, description, price, stock, category_id, status, pinned) VALUES (:name, :description, :price, :stock, :category_id, :status, :pinned)');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':pinned', $data['pinned']);
        return $stmt->execute();
    }

    public static function update($id, $data)
    {
        $stmt = self::$db->prepare('UPDATE products SET name = :name, description = :description, price = :price, stock = :stock, category_id = :category_id, status = :status, pinned = :pinned WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':pinned', $data['pinned']);
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
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
            'status' => $this->status,
            'pinned' => $this->pinned
        ];
    }
}
