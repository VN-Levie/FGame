<?php

namespace Commands;

class MakeModelCommand
{
    public function getName()
    {
        return 'make:model';
    }

    // Get table name from model name
    function getTableName($table_name)
    {
        // Thêm dấu _ giữa các từ
        $table_name = preg_replace('/(?<!^)[A-Z]/', '_$0', $table_name);
        // Kiểm tra quy tắc tiếng Anh
        if (substr($table_name, -1) === 'y') {
            $table_name = substr($table_name, 0, -1) . 'ies';
        } else {
            $table_name .= 's';
        }
        // Chuyển thành chữ thường
        $table_name = strtolower($table_name);
        return $table_name;
    }

    public function execute()
    {
        global $argv;

        if (count($argv) < 3) {
            echo "Usage: php artisan make:model [ModelName]\n";
            exit(1);
        }

        $modelName = $argv[2];
        $modelDir = __DIR__ . '/../models';
        $modelFile = $modelDir . '/' . $modelName . '.php';

        if (!file_exists($modelDir)) {
            mkdir($modelDir, 0777, true);
        }

        if (file_exists($modelFile)) {
            echo "Model already exists.\n";
            exit(1);
        }

        $tableName = $this->getTableName($modelName);

        $template = <<<EOT
<?php

namespace Models;


class $modelName extends Model
{
    public \$id;

    protected static \$table = '$tableName';
    
    // Your model code here
}
EOT;

        file_put_contents($modelFile, $template);
        echo "Model created successfully: $modelFile\n";
    }
}
