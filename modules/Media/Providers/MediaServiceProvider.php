<?php

namespace Modules\Media\Providers;

use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register the service provider
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $modulePath = __DIR__.'/..';
        $this->loadMigrationsFrom($modulePath.'/database/migrations');
        if (file_exists($modulePath.'/Routes/web.php')) {
            $this->loadRoutesFrom($modulePath.'/Routes/web.php');
        }
        if (file_exists($modulePath.'/Routes/api.php')) {
            $this->loadRoutesFrom($modulePath.'/Routes/api.php');
        }
    }
}
