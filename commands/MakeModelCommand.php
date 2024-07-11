<?php

namespace Commands;

class MakeModelCommand
{
    public function getName()
    {
        return 'make:model';
    }

    public function execute()
    {
        global $argv;

        if (count($argv) < 3) {
            echo "Usage: php artisan.php make:model ModelName\n";
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

        $template = <<<EOT
<?php

namespace Models;

class $modelName extends Model
{
    // Your model code here
}
EOT;

        file_put_contents($modelFile, $template);
        echo "Model created successfully: $modelFile\n";
    }
}
