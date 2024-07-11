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


    public static function countUpView($id)
    {
        $stmt = self::$db->prepare('UPDATE products SET views = views + 1 WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
