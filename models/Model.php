<?php

namespace Models;

use Core\Database;
use PDO;
use View;

class Model
{
    protected static $db;
    public $id;

    protected static $table;


    //open connection
    public function openConnection()
    {
        if (!isset(self::$db)) {
            $database = new Database();
            self::$db = $database->connect();
        }
    }

    //close connection
    public function closeConnection()
    {
        self::$db = null;
    }

    //all
    public static function all()
    {
      try {
        $stmt = self::$db->prepare('SELECT * FROM ' . static::getTableName());
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
      } catch (\Throwable $th) {
        return View::abort(500, $th->getMessage());
      }
    }

    public static function find($id)
    {
        if (!is_numeric($id)) {
            return null;
        }
        $stmt = self::$db->prepare('SELECT * FROM ' . static::getTableName() . ' WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        $result = $stmt->fetch();
        return $result === false ? null : $result;
    }

    //get table name form model name
    public static function getTableName()
    {
        //nếu tồn tại $table thì trả về luôn
        if (isset(static::$table)) {
            return static::$table;
        }
        //nếu không thì tự suy ra tên bảng theo quy tắc
        $table_name = get_called_class();
        //loại bỏ models\ ở đầu nếu có
        $table_name = str_replace('Models\\', '', $table_name);
        //thêm dấu _ giữa các từ
        $table_name = preg_replace('/(?<!^)[A-Z]/', '_$0', $table_name);
        //kiểm tra quy tắc tiếng anh
        if (substr($table_name, -1) === 'y') {
            $table_name = substr($table_name, 0, -1) . 'ies';
        } else {
            $table_name .= 's';
        }
        //chuyển thành chữ thường
        $table_name = strtolower($table_name);
        return $table_name;
    }

    public static function getPropName()
    {

        //nếu không thì tự suy ra tên bảng theo quy tắc
        $prop_name = get_called_class();
        //loại bỏ models\ ở đầu nếu có
        $prop_name = str_replace('Models\\', '', $prop_name);
        //thêm dấu _ giữa các từ
        $prop_name = preg_replace('/(?<!^)[A-Z]/', '_$0', $prop_name);
        //chuyển thành chữ thường
        $prop_name = strtolower($prop_name);
        //loại bỏ s hoăc es ở cuối theo quy tắc tiếng anh
        if (substr($prop_name, -3) === 'ies') {
            $prop_name = substr($prop_name, 0, -3) . 'y';
        } else if (substr($prop_name, -1) === 's') {
            $prop_name = substr($prop_name, 0, -1);
        }
        // echo $prop_name;
        return $prop_name;
    }

    //Forum::where('category_id', $this->id)
    public static function where($column, $value)
    {
        $stmt = self::$db->prepare('SELECT * FROM ' . static::$table . ' WHERE ' . $column . ' = :value');
        $stmt->bindParam(':value', $value);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    //find with condition
    public static function findWhere($conditions)
    {
        $table_name = static::getTableName();
        $sql = "SELECT * FROM $table_name WHERE ";
        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "$key = :$key";
        }
        $sql .= implode(" AND ", $where);
        $stmt = self::$db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute($conditions);
        return $stmt->fetchAll();
    }

    //findone
    public static function findOne($conditions)
    {
        $table_name = static::getTableName();
        $sql = "SELECT * FROM $table_name WHERE ";
        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "$key = :$key";
        }
        $sql .= implode(" AND ", $where);
        $stmt = self::$db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute($conditions);
        return $stmt->fetch();
    }

    //first
    public static function first()
    {
        $stmt = self::$db->prepare('SELECT * FROM ' . static::getTableName() . ' LIMIT 1');
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    //last
    public static function last()
    {
        $stmt = self::$db->prepare('SELECT * FROM ' . static::getTableName() . ' ORDER BY id DESC LIMIT 1');
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }


    public static function update($id, $data)
    {
        $model_name = get_called_class();
        $table_name =  static::getTableName();
        $instance = new $model_name();
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
    public static function create($data)
    {
        $model = get_called_class();
        $table_name = static::getTableName();
        $instance = new $model();
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
     * @param array $data Dữ liệu từ một hàng (row) của kết quả truy vấn.
     * @param string $model Tên model cần tạo đối tượng.
     * @param string|null $alias_name Tên alias của model (nếu có), mặc định là null.
     * @return object Đối tượng model đã được tạo và ánh xạ dữ liệu.
     */
    private static function createModel($model, $data,  $alias_name = null)
    {

        $alias_name = $alias_name ?? strtolower($model) . 's';
        $instance = new $model();

        foreach ($data as $key => $value) {
            if (strpos($key, $alias_name . "_") === 0) {
                $key = substr($key, strlen($alias_name) + 1);
            }
            if (property_exists($instance, $key) && $key !== 'password') {
                $instance->$key = $value;
            }
        }
        return $instance;
    }

    //toArray
    public function toArray()
    {
        $props = get_object_vars($this);
        unset($props['db']);
        return $props;
    }

    //delete
    public function delete($conditions = [])
    {
        $table_name = static::getTableName();

        if (empty($conditions)) {
            if (!isset($this->id)) {
                throw new \Exception("Table <strong>'{$table_name}'</strong> must have a primary key to delete.");
            }
            $conditions = ['id' => $this->id];
        }

        $whereClause = [];
        foreach (array_keys($conditions) as $key) {
            $whereClause[] = "$key = :$key";
        }
        $whereClause = implode(' AND ', $whereClause);

        $stmt = self::$db->prepare("DELETE FROM $table_name WHERE $whereClause");

        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public function save()
    {
        if ($this->id) {
            return $this->update($this->id, $this->toArray());
        }
        return $this->create($this->toArray());
    }

    public static function sum($column, $condition = [])
    {
        $table_name = static::getTableName();
        $sql = "SELECT SUM($column) FROM $table_name";
        if (!empty($condition)) {
            $where = [];
            foreach ($condition as $key => $value) {
                $where[] = "$key = :$key";
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $stmt = self::$db->prepare($sql);
        $stmt->execute($condition);
        return $stmt->fetchColumn();
    }

    public static function count($condition = [])
    {
        try {
            $table_name = static::getTableName();
            $sql = "SELECT COUNT(*) FROM $table_name";
            if (!empty($condition)) {
                $where = [];
                foreach ($condition as $key => $value) {
                    $where[] = "$key = :$key";
                }
                $sql .= " WHERE " . implode(" AND ", $where);
            }
            $stmt = self::$db->prepare($sql);
            $stmt->execute($condition);
            return $stmt->fetchColumn();
        } catch (\Throwable $th) {
            return View::abort(500, $th->getMessage());
        }
    }


    //min
    public static function min($column, $condition = [])
    {
        $table_name = static::getTableName();
        $sql = "SELECT MIN($column) FROM $table_name";
        if (!empty($condition)) {
            $where = [];
            foreach ($condition as $key => $value) {
                $where[] = "$key = :$key";
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $stmt = self::$db->prepare($sql);
        $stmt->execute($condition);
        return $stmt->fetchColumn();
    }

    //max
    public static function max($column, $condition = [])
    {
        $table_name = static::getTableName();
        $sql = "SELECT MAX($column) FROM $table_name";
        if (!empty($condition)) {
            $where = [];
            foreach ($condition as $key => $value) {
                $where[] = "$key = :$key";
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $stmt = self::$db->prepare($sql);
        $stmt->execute($condition);
        return $stmt->fetchColumn();
    }

    //avg
    public static function avg($column, $condition = [])
    {
        $table_name = static::getTableName();
        $sql = "SELECT AVG($column) FROM $table_name";
        if (!empty($condition)) {
            $where = [];
            foreach ($condition as $key => $value) {
                $where[] = "$key = :$key";
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $stmt = self::$db->prepare($sql);
        $stmt->execute($condition);
        return $stmt->fetchColumn();
    }

    //raw
    public static function raw($sql, $condition = [])
    {
        $table_name = static::getTableName();
        $sql = "SELECT * FROM $table_name";
        $stmt = self::$db->prepare($sql);
        $stmt->execute($condition);
        return $stmt->fetchAll();
    }

    //random
    public static function random($limit = 1)
    {
        $table_name = static::getTableName();
        $sql = "SELECT * FROM $table_name ORDER BY RAND() LIMIT $limit";
        $stmt = self::$db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Select records from the database.
     *
     * @param array $columns The columns to select. If empty, selects all columns.
     * @param array $condition An associative array of conditions where the key is the column name and the value is the value to filter by.
     * @return array An array of objects representing the selected records.
     * @throws \Exception If there is an error in the SQL execution.
     */
    public static function select($columns = [], $condition = [])
    {
        $table_name = static::getTableName();
        $sql = "SELECT ";
        if (empty($columns)) {
            $sql .= "*";
        } else {
            $sql .= implode(", ", $columns);
        }
        $sql .= " FROM $table_name";
        if (!empty($condition)) {
            $where = [];
            foreach ($condition as $key => $value) {
                $where[] = "$key = :$key";
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $stmt = self::$db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute($condition);
        return $stmt->fetchAll();
    }



    /**
     * Thực hiện eager loading dữ liệu từ các bảng liên quan.
     *     
     * @param array $dependents Thông tin về các model và bảng phụ thuộc, dạng mảng các mảng. `[ ...]`.
     * @param array $conditions Điều kiện truy vấn WHERE, dạng mảng `key`-`value`.
     * @param int $limit Giới hạn số lượng bản ghi, mặc định là `-1` (không giới hạn).
     * @param int $page Trang hiện tại để phân trang, mặc định là `1`.
     * @return array Mảng các đối tượng đã được eager loaded.
     */
    public static function whereWiths(array $dependents = [], $conditions = [], $sort_by = 'ASC', $order_by = null, $limit = -1, $page = 1)
    {

        $model_main =  get_called_class();
        $table_main = static::getTableName();
        // echo $table_main;

        $props = [];
        $columns = [];
        $aliases = [];
        $tables_dot_star = [];
        foreach ($dependents as $index => $m) {
            $dependents[$index][0] = $model_dependent = "Models\\" . $m[0];
            $table_dependent = $model_dependent::getTableName();
            $prop_name = $model_dependent::getPropName();
            $column_dependent = $m[1] ?? $prop_name . "_id";
            $props[] = $m[2] ?? $model_dependent::getPropName();
            $columns[] = $column_dependent;
            $aliases[$index] = $table_dependent . " AS t" . ($index + 1);
            $propsDependent = array_keys(get_object_vars(new $model_dependent()));

            foreach ($propsDependent as $prop) {
                $tables_dot_star[] = "t" . ($index + 1) . ".$prop as t" . ($index + 1) . "_$prop";
            }
        }



        $instance = new $model_main();
        $propsMain = array_keys(get_object_vars($instance));

        $sql = "SELECT";
        foreach ($propsMain as $prop) {
            $sql .= " $table_main.$prop as {$table_main}_{$prop},";
        }
        foreach ($tables_dot_star as $table_dot_star) {
            $sql .= " $table_dot_star,";
        }
        $sql = rtrim($sql, ',') . " FROM $table_main";
        // echo $table_main . "<br>";
        foreach ($aliases as $key => $alias) {
            // echo $columns[$key] . " | " . $alias . "<br>";
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
        // echo $sql;
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
        // echo "<br>";
        // print_r($dependents);
        // echo "<br>";
        foreach ($results as $row) {
            $obj = self::createModel($model_main, $row, $table_main);
            foreach ($props as $index => $prop) {
                $obj->$prop = self::createModel($dependents[$index][0], $row, "t" . ($index + 1));
            }
            $objs[] = $obj;
        }
        return $objs;
    }
}
