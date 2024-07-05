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

    public static function create($data)
    {
        $stmt = self::$db->prepare('INSERT INTO game_categories (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        return $stmt->execute();
    }

    public static function update($id, $data)
    {
        $stmt = self::$db->prepare('UPDATE game_categories SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
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
            'name' => $this->name,
            'description' => $this->description
        ];
    }
}
