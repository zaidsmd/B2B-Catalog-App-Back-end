<?php

namespace Modules\Supplier\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Supplier\Contracts\Repositories\SupplierRepository;
use Modules\Supplier\Repositories\Eloquent\EloquentSupplierRepository;

class SupplierServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SupplierRepository::class, EloquentSupplierRepository::class);
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
