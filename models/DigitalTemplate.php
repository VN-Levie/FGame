<?php

namespace Models;

use PDO;

class DigitalTemplate extends Model
{
    public $id;
    public $name;
    public $description;
    public $template;
    public $updated_at;
    public $created_at;
}
