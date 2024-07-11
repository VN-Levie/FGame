<?php

namespace Models;

use PDO;

class Game extends Model
{
    public $id;
    public $title;
    public $description;
    public $release_date;
    public $platform_id;
    public $updated_at;
    public $created_at;


}
