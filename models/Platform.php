<?php

namespace Models;

use PDO;

class Platform extends Model
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
        $stmt = self::$db->prepare('SELECT * FROM platforms');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Platform');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find($id): Platform
    {
        $stmt = self::$db->prepare('SELECT * FROM platforms WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Platform');
        $stmt->execute();
        return $stmt->fetch();
    }



    public function save()
    {
        if ($this->id) {
            return $this->update(['Platform', 'platforms'], $this->id, $this->toArray());
        }
        return $this->create(['Platform', 'platforms'], $this->toArray());
    }

    public function delete()
    {
        $stmt = self::$db->prepare('DELETE FROM platforms WHERE id = :id');
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
