<?php

namespace Models;

use PDO;

class Game extends Model
{
    public $id;
    public $title;
    public $description;
    public $release_date;
    public $platform_id;
    public $updated_at;
    public $created_at;

    public function __construct()
    {
        parent::__construct();
    }

    public static function all()
    {
        $stmt = self::$db->prepare('SELECT * FROM games');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Game');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find($id): Game
    {
        $stmt = self::$db->prepare('SELECT * FROM games WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Game');
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function create($data)
    {
        $stmt = self::$db->prepare('INSERT INTO games (title, description, release_date, platform_id) VALUES (:title, :description, :release_date, :platform_id)');
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':release_date', $data['release_date']);
        $stmt->bindParam(':platform_id', $data['platform_id']);
        return $stmt->execute();
    }

    public static function update($id, $data)
    {
        $stmt = self::$db->prepare('UPDATE games SET title = :title, description = :description, release_date = :release_date, platform_id = :platform_id WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':release_date', $data['release_date']);
        $stmt->bindParam(':platform_id', $data['platform_id']);
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
            'title' => $this->title,
            'description' => $this->description,
            'release_date' => $this->release_date,
            'platform_id' => $this->platform_id
        ];
    }
}