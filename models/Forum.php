<?php

namespace Models;

use PDO;

class Forum extends Model
{
    public $id;
    public $parent_id;
    public $content;
    public $type;
    public $user_id;
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

    public static function find($id): Forum
    {
        $stmt = self::$db->prepare('SELECT * FROM forum WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Forum');
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function create($data)
    {
        $stmt = self::$db->prepare('INSERT INTO forum (parent_id, content, type, user_id) VALUES (:parent_id, :content, :type, :user_id)');
        $stmt->bindParam(':parent_id', $data['parent_id']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':type', $data['type']);
        $stmt->bindParam(':user_id', $data['user_id']);
        return $stmt->execute();
    }

    public static function update($id, $data)
    {
        $stmt = self::$db->prepare('UPDATE forum SET parent_id = :parent_id, content = :content, type = :type, user_id = :user_id WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':parent_id', $data['parent_id']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':type', $data['type']);
        $stmt->bindParam(':user_id', $data['user_id']);
        return $stmt->execute();
    }

    public static function count($column, $operator = '=', $value = null)
    {
        $stmt = self::$db->prepare("SELECT COUNT(*) FROM forum WHERE $column $operator :value");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public static function last(){
        $stmt = self::$db->prepare('SELECT * FROM forum ORDER BY id DESC LIMIT 1');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Forum');
        $stmt->execute();
        return $stmt->fetch();
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
            'parent_id' => $this->parent_id,
            'content' => $this->content,
            'type' => $this->type,
            'user_id' => $this->user_id
        ];
    }
}
