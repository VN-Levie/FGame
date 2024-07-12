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
    public $user_id; // Thêm thuộc tính digital_template_id
    public $updated_at;
    public $created_at;


    public static function countUpView($id)
    {
        $stmt = self::$db->prepare('UPDATE products SET views = views + 1 WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    //orders
    public function orders()
    {
        $dependents = [
            ['Product'],
            ['User']
        ];
        return Order::whereWiths($dependents, ['product_id' => $this->id]);
    }

    //sum total
    public function sumTotal()
    {
        return Order::sum('total', ['product_id' => $this->id]);
    }

    
    public function getThumbnail()
    {
        $content = $this->description;
        $doc = new \DOMDocument();
        @$doc->loadHTML($content);
        $tags = $doc->getElementsByTagName('img');
        foreach ($tags as $tag) {
            return $tag->getAttribute('src');
        }
        return '/assets/images/logo.png';
    }

    //get category
    public function getCategory()
    {
        return ProductCategory::find($this->category_id);
    }

     //get short content (remove html tag)
     public function getShortContent($l = 3)
     {
         return strip_tags(limit_word($this->description, $l));
     }
 
}
