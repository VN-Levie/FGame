<?php

namespace Models;

use PDO;

class ForumComment extends Model
{
    public $id;
    public $content;
    public $forum_id;
    public $user_id;
    public $updated_at;
    public $created_at;

}
