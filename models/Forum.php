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
    public $user_id;
    public $views;
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
        $stmt = self::$db->prepare('UPDATE forum SET views = views + 1 WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
