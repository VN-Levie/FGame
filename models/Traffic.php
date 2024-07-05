<?php

namespace Models;

use PDO;

class Traffic extends Model
{
    public $id;
    public $ip;
    public $user_agent;
    public $user_id;
    public $count_up;
    public $updated_at;
    public $created_at;

    public function __construct()
    {
        parent::__construct();
    }

    public static function all()
    {
        $stmt = self::$db->prepare('SELECT * FROM traffic');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Traffic');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find($id): Traffic
    {
        $stmt = self::$db->prepare('SELECT * FROM traffic WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Traffic');
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function create($data)
    {
        $stmt = self::$db->prepare('INSERT INTO traffic (ip, user_agent, user_id, count_up) VALUES (:ip, :user_agent, :user_id, :count_up)');
        $stmt->bindParam(':ip', $data['ip']);
        $stmt->bindParam(':user_agent', $data['user_agent']);
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':count_up', $data['count_up']);
        return $stmt->execute();
    }

    public static function update($id, $data)
    {
        $stmt = self::$db->prepare('UPDATE traffic SET ip = :ip, user_agent = :user_agent, user_id = :user_id, count_up = :count_up WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':ip', $data['ip']);
        $stmt->bindParam(':user_agent', $data['user_agent']);
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':count_up', $data['count_up']);
        return $stmt->execute();
    }

    public static function checkAndCountUpOrInsert($ip, $user_agent, $user_id)
    {
        // Kiểm tra nếu IP đã tồn tại và user_id giống nhau
        $stmt = self::$db->prepare('SELECT * FROM traffic WHERE ip = :ip AND user_id = :user_id and user_agent = :user_agent');
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':user_agent', $user_agent);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Traffic');
        $stmt->execute();
        $traffic = $stmt->fetch();

        if ($traffic) {
            // Nếu tồn tại, tăng biến đếm lên
            $stmt = self::$db->prepare('UPDATE traffic SET count_up = count_up + 1, user_agent = :user_agent WHERE id = :id');
            $stmt->bindParam(':id', $traffic->id);
            $stmt->bindParam(':user_agent', $user_agent);
            return $stmt->execute();
        } else {
            // Nếu không tồn tại, hoặc tồn tại nhưng user_id khác, chèn mới
            $data = [
                'ip' => $ip,
                'user_agent' => $user_agent,
                'user_id' => $user_id,
                'count_up' => 1
            ];
            return self::create($data);
        }
    }

    //last
    public static function last()
    {
        $stmt = self::$db->prepare('SELECT * FROM traffic ORDER BY id DESC LIMIT 1');
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Traffic');
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
            'ip' => $this->ip,
            'user_agent' => $this->user_agent,
            'user_id' => $this->user_id,
            'count_up' => $this->count_up
        ];
    }

    public static function count($column, $operator = '=', $value = null)
    {
        $stmt = self::$db->prepare("SELECT COUNT(*) FROM traffic WHERE $column $operator :value");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public static function sum($column, $condition = null)
    {
        $stmt = self::$db->prepare("SELECT SUM($column) FROM traffic $condition");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
