<?php

namespace Models;

use Core\Database;
use PDO;

class Model
{
    protected static $db;

    protected static $table;

    public function __construct()
    {
        if (!isset(self::$db)) {
            $database = new Database();
            self::$db = $database->connect();
        }
    }

    public static function find($id)
    {
        $stmt = self::$db->prepare('SELECT * FROM ' . static::$table . ' WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        $result = $stmt->fetch();
        return $result === false ? null : $result;
    }
    

    public static function update($model, $id, $data)
    {
        $model_name = $model[0];
        $table_name = $model[1];
        $model_fullPath = "Models\\" . $model_name;
        $instance = new $model_fullPath();
        $props = array_keys(get_object_vars($instance));
        $sql = "UPDATE $table_name SET ";
        foreach ($props as $prop) {
            if ($prop === 'created_at' || $prop === 'updated_at') {
                unset($props[array_search($prop, $props)]);
                continue;
            }
            if ($prop !== 'id') {
                $sql .= "$prop = :$prop, ";
            }
        }

        $sql = rtrim($sql, ', ') . " WHERE id = :id";
        $stmt = self::$db->prepare($sql);
        foreach ($props as $prop) {
            if ($prop !== 'id') {
                $stmt->bindParam(":$prop", $data[$prop]);
            }
        }

        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
    //create
    public static function create($model, $data)
    {
        $model_name = $model[0];
        $table_name = $model[1];
        $model_fullPath = "Models\\" . $model_name;
        $instance = new $model_fullPath();
        $props = array_keys(get_object_vars($instance));
        $sql = "INSERT INTO $table_name (";
        foreach ($props as $prop) {
            if ($prop === 'created_at' || $prop === 'updated_at') {
                unset($props[array_search($prop, $props)]);
                continue;
            }
            if ($prop !== 'id') {
                $sql .= "$prop, ";
            }
        }
        $sql = rtrim($sql, ', ') . ") VALUES (";
        foreach ($props as $prop) {
            if ($prop !== 'id') {
                $sql .= ":$prop, ";
            }
        }

        $sql = rtrim($sql, ', ') . ")";
        $stmt = self::$db->prepare($sql);
        // echo $sql;
        // print_r($props);
        // print_r($data);
        foreach ($props as $prop) {
            if ($prop !== 'id') {
                $stmt->bindParam(":$prop", $data[$prop]);
            }
        }
        return $stmt->execute();
    }
    /**
     * Tạo một đối tượng model từ một hàng dữ liệu.
     *
     * @param array $row Dữ liệu từ một hàng (row) của kết quả truy vấn.
     * @param string $model Tên model cần tạo đối tượng.
     * @param string|null $alias_name Tên alias của model (nếu có), mặc định là null.
     * @return object Đối tượng model đã được tạo và ánh xạ dữ liệu.
     */
    private static function createModelFromRow($row, $model, $alias_name = null)
    {
        $model_fullPath = "Models\\" . $model;
        $alias_name = $alias_name ?? strtolower($model) . 's';
        $instance = new $model_fullPath();

        foreach ($row as $key => $value) {
            if (strpos($key, $alias_name . "_") === 0) {
                $key = substr($key, strlen($alias_name) + 1);
            }
            if (property_exists($instance, $key) && $key !== 'password') {
                $instance->$key = $value;
            }
        }
        return $instance;
    }
    /**
     * Thực hiện eager loading dữ liệu từ các bảng liên quan.
     *
     * @param array $m1 Thông tin về model và bảng chính. Ví dụ: ['ModelName', 'model_table'].
     * @param array $dependents Thông tin về các model và bảng phụ thuộc, dạng mảng các mảng. `[['ModelName', 'model_table', 'column_dependent', 'dependent_name'], ...]`.
     * @param array $conditions Điều kiện truy vấn WHERE, dạng mảng `key`-`value`.
     * @param int $limit Giới hạn số lượng bản ghi, mặc định là `-1` (không giới hạn).
     * @param int $page Trang hiện tại để phân trang, mặc định là `1`.
     * @return array Mảng các đối tượng đã được eager loaded.
     */
    public static function whereWiths(array $m1, array $dependents = [], $conditions = [], $sort_by = 'ASC', $order_by = null, $limit = -1, $page = 1)
    {
        $model_path = "Models\\";
        $model_main = $m1[0];
        $table_main = $m1[1];

        $props = [];
        $columns = [];
        $aliases = [];
        $tables_dot_star = [];

        foreach ($dependents as $index => $m) {
            $model_dependent = $m[0];
            $table_dependent = $m[1];
            $column_dependent = $m[2] ?? strtolower($model_dependent) . "_id";
            $props[] = $m[3] ?? strtolower($model_dependent);
            $columns[] = $column_dependent;
            $aliases[$index] = $table_dependent . " AS t" . ($index + 1);
            $fullPathDependentModel = $model_path . $model_dependent;
            $instance = new $fullPathDependentModel();
            $propsDependent = array_keys(get_object_vars($instance));

            foreach ($propsDependent as $prop) {
                $tables_dot_star[] = "t" . ($index + 1) . ".$prop as t" . ($index + 1) . "_$prop";
            }
        }

        $fullPathMainModel = $model_path . $model_main;
        $instance = new $fullPathMainModel();
        $propsMain = array_keys(get_object_vars($instance));

        $sql = "SELECT";
        foreach ($propsMain as $prop) {
            $sql .= " $table_main.$prop as {$table_main}_{$prop},";
        }
        foreach ($tables_dot_star as $table_dot_star) {
            $sql .= " $table_dot_star,";
        }
        $sql = rtrim($sql, ',') . " FROM $table_main";

        foreach ($aliases as $key => $alias) {
            $sql .= " JOIN $alias ON $table_main." . $columns[$key] . " = t" . ($key + 1) . ".id";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$table_main.$key = :$key";
            }
            $sql .= implode(" AND ", $where);
        }

        if ($order_by) {
            $sql .= " ORDER BY $table_main.$order_by $sort_by";
        } else {
            $sql .= " ORDER BY $table_main.id $sort_by";
        }

        if ($limit > 0) {
            $offset = ($page - 1) > 0 ? ($page - 1) * $limit : 0;
            $sql .= " LIMIT $limit OFFSET $offset";
        }

        $stmt = self::$db->prepare($sql);
        foreach ($conditions as $key => $value) {
            $stmt->bindParam(":$key", $value);
        }
        // echo $sql;
        $stmt->execute($conditions);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($results);
        $objs = [];
        foreach ($results as $row) {
            $obj = self::createModelFromRow($row, $model_main, $table_main);
            foreach ($props as $index => $prop) {
                $obj->$prop = self::createModelFromRow($row, $dependents[$index][0], "t" . ($index + 1));
            }
            $objs[] = $obj;
        }
        return $objs;
    }
}
