<?php

namespace Models;

use PDO;

class GameCategory extends Model
{
    public $id;
    public $name;
    public $description;
    public $updated_at;
    public $created_at;

    public function __construct()
    {
        parent::__construct();
    }

    public static function all()
    {
        $stmt = self::$db->prepare('SELECT * FROM game_categories');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\GameCategory');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find($id): GameCategory
    {
        $stmt = self::$db->prepare('SELECT * FROM game_categories WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\GameCategory');
        $stmt->execute();
        return $stmt->fetch();
    }

  

    public function save()
    {
        if ($this->id) {
            return $this->update(['GameCategory', 'game_categories'], $this->id, $this->toArray());
        }
        return $this->create($this->toArray());
    }

    public function delete()
    {
        $stmt = self::$db->prepare('DELETE FROM game_categories WHERE id = :id');
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'description' => $this->description
        ];
    }
}
