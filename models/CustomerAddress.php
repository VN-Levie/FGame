<?php

namespace Models;

use PDO;

class CustomerAddress extends Model
{
    public $id;
    public $customer_name;
    public $phone;
    public $email;
    public $address;
    public $user_id;
    public $updated_at;
    public $created_at;
}
