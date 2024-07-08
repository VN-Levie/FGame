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
    public $views; // Thêm thuộc tính views
    public $is_digital; // Thêm thuộc tính is_digital
    public $digital_info; // Thêm thuộc tính digital_info
    public $digital_template_id; // Thêm thuộc tính digital_template_id
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


    public function save()
    {
        if ($this->id) {
            return $this->update(['Product', 'products'],$this->id, $this->toArray());
        }
        return $this->create(['Product', 'products'],$this->toArray());
    }

    public function delete()
    {
        $stmt = self::$db->prepare('DELETE FROM products WHERE id = :id');
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
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
            'pinned' => $this->pinned,
            'views' => $this->views,
            'is_digital' => $this->is_digital,
            'digital_info' => $this->digital_info,
            'digital_template_id' => $this->digital_template_id
        ];
    }

    public static function countUpView($id)
    {
        $stmt = self::$db->prepare('UPDATE products SET views = views + 1 WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
