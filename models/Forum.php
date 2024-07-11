<?php

namespace Models;

use PDO;

class Forum extends Model
{
    protected static $table = 'forums';
    public $id;
    public $category_id;
    public $title;
    public $content;
    public $views;
    public $soft_delete = 0;
    public $hide = 0;
    public $user_id;
    public $updated_at;
    public $created_at;

    public function getCategory()
    {
        return ForumCategory::find($this->category_id);
    }

    // Lấy comments
    public function getComments()
    {
        return ForumComment::whereWiths([['User']], ['forum_id' => $this->id]);
    }

    public static function countUpView($id)
    {
        $forum = self::find($id);
        $forum->views += 1;
        return $forum->save();
    }
}
