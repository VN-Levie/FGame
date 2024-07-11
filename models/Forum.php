<?php

namespace Models;

use PDO;

class Forum extends Model
{
    protected static $table = 'forum';
    public $id;
    public $category_id;
    public $title;
    public $content;
    public $user_id;
    public $views;
    public $updated_at;
    public $created_at;

    public function __construct()
    {
        parent::__construct();
    }

    public static function all()
    {
        $stmt = self::$db->prepare('SELECT * FROM forum');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Forum');
        $stmt->execute();
        return $stmt->fetchAll();
    }





    public function save()
    {
        if ($this->id) {
            return self::update(['Forum', 'forum'], $this->id, $this->toArray());
        }
        return self::create($this->toArray());
    }

    //delete function
    public function delete()
    {
        $stmt = self::$db->prepare('DELETE FROM forum WHERE id = :id');
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function toArray()
    {
        return [
            'category_id' => $this->category_id,
            'title' => $this->title,
            'content' => $this->content,
            'user_id' => $this->user_id,
            'views' => $this->views // Thêm views vào mảng
        ];
    }

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
