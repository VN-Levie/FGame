<?php

namespace Models;


class ProductCategory extends Model
{

    protected static $table = 'product_categories';

    public $id;
 
    // CREATE TABLE `product_categories` (
    //     `id` int NOT NULL AUTO_INCREMENT,
    //     `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    //     `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
    //     `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    //     `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    //     PRIMARY KEY (`id`)
    //    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci    
    public $name;
    public $description;
    public $updated_at;
    public $created_at;
    //count product in category
    public function countProduct()
    {
        return Product::count(condition: ['category_id' => $this->id]);
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
}