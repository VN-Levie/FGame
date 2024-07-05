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

    public static function create($data)
    {
        $stmt = self::$db->prepare('INSERT INTO platforms (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        return $stmt->execute();
    }

    public static function update($id, $data)
    {
        $stmt = self::$db->prepare('UPDATE platforms SET name = :name, description = :description WHERE id = :id');
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
