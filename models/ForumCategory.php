<?php

namespace Models;

use PDO;

class ForumCategory extends Model
{
    protected static $table = 'forum_categories';

    public $id;
    public $name;
    public $description;
    public $soft_delete = 0;
    public $hide = 0;
    public $user_id;
    public $updated_at;
    public $created_at;

    //posts
    public function posts()
    {
        return Forum::where('category_id', $this->id);
    }
}
