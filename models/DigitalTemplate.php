<?php

namespace Models;

use PDO;

class DigitalTemplate extends Model
{
    public $id;
    public $name;
    public $description;
    public $template;
    public $updated_at;
    public $created_at;

    public function __construct()
    {
        parent::__construct();
    }

    public static function all()
    {
        $stmt = self::$db->prepare('SELECT * FROM digital_template');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\DigitalTemplate');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find($id): DigitalTemplate
    {
        $stmt = self::$db->prepare('SELECT * FROM digital_template WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\DigitalTemplate');
        $stmt->execute();
        return $stmt->fetch();
    }

    

    public function save()
    {
        if ($this->id) {
            return $this->update(['DigitalTemplate', 'digital_template'], $this->id, $this->toArray());
        }
        return $this->create(['DigitalTemplate', 'digital_template'], $this->toArray());
    }

    //delete function
    public function delete()
    {
        $stmt = self::$db->prepare('DELETE FROM digital_template WHERE id = :id');
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'template' => $this->template
        ];
    }
}
