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

    public function __construct()
    {
        parent::__construct();
    }

    public static function all()
    {
        $stmt = self::$db->prepare('SELECT * FROM forum_comment');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\ForumComment');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find($id): ForumComment
    {
        $stmt = self::$db->prepare('SELECT * FROM forum_comment WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\ForumComment');
        $stmt->execute();
        return $stmt->fetch();
    }

  

    public function save()
    {
        if ($this->id) {
            return self::update(['ForumComment', 'forum_comment'], $this->id, $this->toArray());
        }
        return self::create($this->toArray());
    }

    //delete function
    public function delete()
    {
        $stmt = self::$db->prepare('DELETE FROM forum_comment WHERE id = :id');
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function toArray()
    {
        return [
            'content' => $this->content,
            'forum_id' => $this->forum_id,
            'user_id' => $this->user_id
        ];
    }
    
    public function getForum()
    {
        return Forum::find($this->forum_id);
    }
}
