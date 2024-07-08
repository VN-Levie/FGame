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


  

    public function save()
    {
        if ($this->id) {
            return $this->update(['Game', 'games'], $this->id, $this->toArray());
        }
        return $this->create(['Game', 'games'], $this->toArray());
    }

    public function delete()
    {
        $stmt = self::$db->prepare('DELETE FROM games WHERE id = :id');
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
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
