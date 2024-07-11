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




}
