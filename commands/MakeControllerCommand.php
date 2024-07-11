<?php

namespace Commands;

class MakeControllerCommand
{
    public function getName()
    {
        return 'make:controller';
    }

    public function execute()
    {
        global $argv;

        if (count($argv) < 3) {
            echo "Usage: php artisan make:controller ControllerName\n";
            exit(1);
        }

        $controllerName = $argv[2];
        $controllerDir = __DIR__ . '/../controllers';
        $controllerFile = $controllerDir . '/' . $controllerName . '.php';

        if (!file_exists($controllerDir)) {
            mkdir($controllerDir, 0777, true);
        }

        if (file_exists($controllerFile)) {
            echo "Controller already exists.\n";
            exit(1);
        }

        $template = <<<EOT
<?php

namespace Controllers;

class $controllerName extends Controller
{
    // Your controller code here
}
EOT;

        file_put_contents($controllerFile, $template);
        echo "Controller created successfully: $controllerFile\n";
    }
}
