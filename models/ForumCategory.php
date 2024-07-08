<?php

namespace Models;

use PDO;

class ForumCategory extends Model
{
    public $id;
    public $name;
    public $description;
    public $user_id;
    public $updated_at;
    public $created_at;

    public function __construct()
    {
        parent::__construct();
    }

    public static function all()
    {
        $stmt = self::$db->prepare('SELECT * FROM forum_categories');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\ForumCategory');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find($id): ForumCategory
    {
        $stmt = self::$db->prepare('SELECT * FROM forum_categories WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\ForumCategory');
        $stmt->execute();
        return $stmt->fetch();
    }



    public function save()
    {
        if ($this->id) {
            return self::update(['ForumCategory', 'forum_categories'], $this->id, $this->toArray());
        }
        return self::create(['ForumCategory', 'forum_categories'], $this->toArray());
    }

    //delete function
    public function delete()
    {
        $stmt = self::$db->prepare('DELETE FROM forum_categories WHERE id = :id');
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'user_id' => $this->user_id
        ];
    }
}
