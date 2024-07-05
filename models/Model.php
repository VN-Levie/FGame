<?php

namespace Models;

use Core\Database;
use PDO;

class Model
{
    protected static $db;

    public function __construct()
    {
        if (!isset(self::$db)) {
            $database = new Database();
            self::$db = $database->connect();
        }
    }

    private static function createModelFromRow($row, $model)
    {
        $model_fullPath = "Models\\" . $model;
        $instance = new $model_fullPath();
        foreach ($row as $key => $value) {
            if (property_exists($instance, $key)) {
                // if($key == 'created_at' || $key == 'updated_at'){
                //     $value = date('d-m-Y H:i:s', strtotime($value));
                // }
                if($key == 'password'){
                    continue;
                }
                $instance->$key = $value;
            }
        }
        return $instance;
    }

    /**
     * Thực hiện eager loading dữ liệu từ các bảng liên quan
     *
     * @param   array  $m1      Thông tin về model và bảng chính
     *                          Ví dụ: ['Order', 'orders'] là model `Order` và bảng `orders`
     * @param   array  $m2      Thông tin về model và bảng phụ thuộc
     *                          Ví dụ: ['Product', 'products'] là model `Product` và bảng `products`
     * @param   string $column  Tên cột để kết nối giữa hai bảng
     *                          Mặc định là null, tự động xác định từ tên model phụ thuộc
     * @return  array           Mảng các đối tượng đã được eager loaded
     */
    public static function with(array $m1, array $m2, $column = null)
    {
        // Lấy tên model và bảng từ các mảng truyền vào
        $model_main = $m1[0];
        $model_dependent = $m2[0];
        $table_main = $m1[1];
        $table_dependent = $m2[1];
        $prop = strtolower($model_dependent); // Tên thuộc tính để gán đối tượng phụ thuộc
        $column = $column ?? $prop . "_id"; // Tên cột để kết nối giữa hai bảng, mặc định lấy từ tên model phụ thuộc

        // Xây dựng câu truy vấn SQL với các thông tin đã lấy được
        $stmt = self::$db->prepare("SELECT $table_main.*, $table_dependent.* FROM $table_main JOIN $table_dependent ON $table_main.$column = $table_dependent.id");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $objs = [];
        foreach ($results as $row) {
            // Tạo đối tượng model chính từ dòng dữ liệu và gán vào $obj
            $obj = self::createModelFromRow($row, $model_main);
            // Tạo đối tượng model phụ thuộc từ dòng dữ liệu và gán vào $obj->$prop
            $obj->$prop = self::createModelFromRow($row, $model_dependent);
            $objs[] = $obj;
        }
        return $objs;
    }
    /**
     * Thực hiện eager loading dữ liệu từ nhiều bảng phụ thuộc
     *
     * @param   array  $m1      Thông tin về model và bảng chính
     *                          Ví dụ: ['Order', 'orders'] là model Order và bảng orders
     * @param   array  $m2      Mảng các thông tin về các bảng phụ thuộc
     *                          Ví dụ: [['Product', 'products'], ['Customer', 'customers']] là model Product và Customer và bảng products và customers
     * @param   string $column  Tên cột để kết nối giữa các bảng, mặc định là null tự động xác định từ tên model phụ thuộc
     * @return  array           Mảng các đối tượng đã được eager loaded
     */
   
    public static function withs(array $m1, array $m2)
    {
         // $oders = Order::withs(['Order', 'orders'], [['Product', 'products', 'product_id'], ['User', 'users', 'user_id']]);
        // Lấy thông tin về model chính và bảng chính
        $model_main = $m1[0];
        $table_main = $m1[1];

        // Chuẩn bị các mảng chứa thông tin về các model, bảng và cột của các bảng phụ thuộc
        $props = [];
        $tables = [];
        $columns = [];

        foreach ($m2 as $m) {
            $model_dependent = $m[0];
            $table_dependent = $m[1];
            $column_dependent = $m[2] ?? strtolower($model_dependent) . "_id";
            $props[] = strtolower($model_dependent); // Tên thuộc tính để gán đối tượng phụ thuộc
            $tables[] = $table_dependent; // Tên bảng phụ thuộc
            $columns[] = $column_dependent; // Tên cột để kết nối giữa các bảng, mặc định là tên thuộc tính phụ thuộc và "_id"
        }

        $tables_dot_star = [];
        foreach ($tables as $table) {
            $tables_dot_star[] = "$table.*";
        }

        // Xây dựng câu truy vấn SQL
        $sql = "SELECT $table_main.*, " . implode(", ", $tables_dot_star) . " FROM $table_main ";
        $joins = [];
        foreach ($tables as $key => $table) {
            // echo " JOIN $table ON $table_main." . $columns[$key] . " = $table.id";
            $joins[] = " JOIN $table ON $table_main." . $columns[$key] . " = $table.id";
        }
        $sql .= implode(" ", $joins);
        // echo $sql;
        // Thực thi câu truy vấn SQL và lấy kết quả
        $stmt = self::$db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $objs = [];
        foreach ($results as $row) {
            // Tạo đối tượng model chính từ dòng dữ liệu và gán vào $obj
            $obj = self::createModelFromRow($row, $model_main);
            // Tạo các đối tượng model phụ thuộc và gán vào $obj theo từng thuộc tính
            foreach ($props as $index => $prop) {
                $obj->$prop = self::createModelFromRow($row, $m2[$index][0]);
            }
            $objs[] = $obj;
        }
        return $objs;
    }

    //join nhiều bảng $m2 là mảng các bảng phụ thuộc
 
}
