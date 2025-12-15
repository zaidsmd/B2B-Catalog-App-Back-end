<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CreateModuleCommand extends Command
{
    protected $signature = 'create:module {name : The module name in StudlyCase (e.g., ProductCatalog)}';

    protected $description = 'Create a new module inside modules folder';


    public function handle()
    {
        $raw = (string) $this->argument('name');
        if ($raw === '') {
            $this->error('Module name is required.');
            return self::FAILURE;
        }

        // Normalize module name to StudlyCase
        $name = Str::studly($raw);

        $this->info("Scaffolding module: {$name}");

        $folders = $this->getFolders($name);
        $pass = $this->createFolder($folders);

        if (! $pass) {
            return self::FAILURE;
        }

        $this->scaffoldFiles($name);

        if (! $this->createProvider($name)) {
            return self::FAILURE;
        }

        $this->registerModule($name);
        $this->info('Module created successfully. Don\'t forget to run: composer dump-autoload');
        return self::SUCCESS;
    }

    function getFolders(string $name): array
    {
        return [$name => [
            'Http' => [
                'Controllers' => [],
                'Requests' => [],
                'Resources' => [],
                'Middleware' => [],
            ],
            'Models' => [],
            'Providers' => [],
            'Routes' => [
                'api' => [],
                'web' => [],
            ],
            'Views' => [],
            'database' => [
                'factories' => [],
                'migrations' => [],
                'seeders' => [],
            ],
            'Contracts' => [],
            'Events' => [],
            'Listeners' => [],
            'Notifications' => [],
            'Policies' => [],
            'Tests' => [],
            'Traits' => [],
            'Services' => [],
            'config' => []
        ]];
    }

    function createFolder($folders, $parent = null): bool
    {
        foreach ($folders as $folder => $subfolders) {
            $path = $parent ? $parent . '/' . $folder : $folder;
            $base_path = base_path('modules/' . $path);
            if (file_exists($base_path)) {
                $this->line('Skipping existing folder: ' . $path);
            } else {
                if (!mkdir($base_path, 0777, true) && !is_dir($base_path)) {
                    $this->error('Failed to create folder: ' . $path);
                    return false;
                }
                $this->info('Folder ' . $path . ' created successfully.');
            }
            if ($subfolders) {
                $this->createFolder($subfolders, $path);
            }
        }
        return true;
    }

    function createProvider(string $name): bool
    {
        $class = $name . 'ServiceProvider';
        Artisan::call('make:provider ' . $class);

        $moduleProviderPath = base_path('modules/' . $name . '/Providers/' . $class . '.php');
        $appProviderPath = base_path('app/Providers/' . $class . '.php');

        if (! file_exists($appProviderPath)) {
            $this->error('Failed to generate provider at: ' . $appProviderPath);
            return false;
        }

        if (! is_dir(dirname($moduleProviderPath))) {
            mkdir(dirname($moduleProviderPath), 0777, true);
        }

        copy($appProviderPath, $moduleProviderPath);
        unlink($appProviderPath);

        $providerContent = file_get_contents($moduleProviderPath);
        $providerContent = str_replace('App\Providers', 'Modules\\' . $name . '\\Providers', $providerContent);

        $bootFunctionCode = 'public function boot(): void
    {
        $modulePath = __DIR__ . \'/..\';
        $this->loadMigrationsFrom($modulePath . \'/database/migrations\');
        if (file_exists($modulePath . \'/Routes/web.php\')) {
            $this->loadRoutesFrom($modulePath . \'/Routes/web.php\');
        }
        if (file_exists($modulePath . \'/Routes/api.php\')) {
            $this->loadRoutesFrom($modulePath . \'/Routes/api.php\');
        }
    }';

        $providerContent = str_replace('public function boot(): void
    {
        //
    }', $bootFunctionCode, $providerContent);

        file_put_contents($moduleProviderPath, $providerContent);
        $this->info('Module provider created successfully.');
        return true;
    }

    function scaffoldFiles(string $name): void
    {
        $base = base_path('modules/' . $name . '/Routes');
        if (! is_dir($base)) {
            mkdir($base, 0777, true);
        }

        $web = $base . '/web.php';
        if (! file_exists($web)) {
            $content = "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\n// Web routes for module: {$name}\nRoute::middleware('web')->group(function () {\n    // Route::get('/', fn () => view('welcome'));\n});\n";
            file_put_contents($web, $content);
            $this->info('Created Routes/web.php');
        }

        $api = $base . '/api.php';
        if (! file_exists($api)) {
            $content = "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\n// API routes for module: {$name}\nRoute::middleware('api')->prefix('api')->group(function () {\n    // Route::get('/ping', fn () => ['ok' => true]);\n});\n";
            file_put_contents($api, $content);
            $this->info('Created Routes/api.php');
        }
    }

    function registerModule(string $name): void
    {
        $providersFile = base_path('bootstrap/providers.php');
        if (! file_exists($providersFile)) {
            $this->warn('bootstrap/providers.php not found. Please register the provider manually.');
            return;
        }

        $fqcn = 'Modules\\' . $name . '\\Providers\\' . $name . 'ServiceProvider::class';
        $contents = file_get_contents($providersFile);

        if (str_contains($contents, $fqcn)) {
            $this->line('Provider already registered in bootstrap/providers.php');
            return;
        }

        $insertion = '    ' . $fqcn . ',';
        $pos = strrpos($contents, '];');
        if ($pos === false) {
            $this->warn('Unable to parse bootstrap/providers.php to auto-register provider.');
            return;
        }

        $updated = substr($contents, 0, $pos) . $insertion . PHP_EOL . substr($contents, $pos);
        file_put_contents($providersFile, $updated);
        $this->info('Provider registered in bootstrap/providers.php');
    }
}
