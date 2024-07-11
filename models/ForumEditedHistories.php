<?php

namespace Models;

use PDO;

class ForumEditedHistory extends Model
{
    public $id;
    public $forum_id;
    public $previous_content;
    public $new_content;
    public $user_id;
    public $updated_at;
    public $created_at;



}
