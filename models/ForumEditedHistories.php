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

    public function __construct()
    {
        parent::__construct();
    }

    public static function all()
    {
        $stmt = self::$db->prepare('SELECT * FROM forum_edited_histories');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\ForumEditedHistory');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find($id): ForumEditedHistory
    {
        $stmt = self::$db->prepare('SELECT * FROM forum_edited_histories WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\ForumEditedHistory');
        $stmt->execute();
        return $stmt->fetch();
    }


  
    public function save()
    {
        if ($this->id) {
            return $this->update(['ForumEditedHistory', 'forum_edited_histories'], $this->id, $this->toArray());
        }
        return $this->create($this->toArray());
    }

    //delete function
    public function delete()
    {
        $stmt = self::$db->prepare('DELETE FROM forum_edited_histories WHERE id = :id');
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function toArray()
    {
        return [
            'forum_id' => $this->forum_id,
            'previous_content' => $this->previous_content,
            'new_content' => $this->new_content,
            'user_id' => $this->user_id
        ];
    }
}
