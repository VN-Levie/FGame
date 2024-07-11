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
}
