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

    public static function create($data)
    {
        $stmt = self::$db->prepare('INSERT INTO forum_edited_histories (forum_id, previous_content, new_content, user_id) VALUES (:forum_id, :previous_content, :new_content, :user_id)');
        $stmt->bindParam(':forum_id', $data['forum_id']);
        $stmt->bindParam(':previous_content', $data['previous_content']);
        $stmt->bindParam(':new_content', $data['new_content']);
        $stmt->bindParam(':user_id', $data['user_id']);
        return $stmt->execute();
    }

    public static function update($id, $data)
    {
        $stmt = self::$db->prepare('UPDATE forum_edited_histories SET forum_id = :forum_id, previous_content = :previous_content, new_content = :new_content, user_id = :user_id WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':forum_id', $data['forum_id']);
        $stmt->bindParam(':previous_content', $data['previous_content']);
        $stmt->bindParam(':new_content', $data['new_content']);
        $stmt->bindParam(':user_id', $data['user_id']);
        return $stmt->execute();
    }

    public function save()
    {
        if ($this->id) {
            return $this->update($this->id, $this->toArray());
        }
        return $this->create($this->toArray());
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
