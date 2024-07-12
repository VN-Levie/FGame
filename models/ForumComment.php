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

    protected static $table = 'forum_comments';

    public function getUser()
    {
        return User::find($this->user_id);
    }

    public function getForum()
    {
        return Forum::find($this->forum_id);
    }

}
