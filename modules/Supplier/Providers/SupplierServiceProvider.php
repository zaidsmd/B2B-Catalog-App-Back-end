<?php

namespace Modules\Supplier\Providers;

use Illuminate\Support\ServiceProvider;

class SupplierServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
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
