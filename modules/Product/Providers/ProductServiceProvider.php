<?php

namespace Modules\Product\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Product\Contracts\Repositories\CategoryRepository;
use Modules\Product\Contracts\Repositories\ProductRepository;
use Modules\Product\Repositories\Eloquent\EloquentCategoryRepository;
use Modules\Product\Repositories\Eloquent\EloquentProductRepository;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind repository interface to implementation (module-owned wiring)
        $this->app->bind(ProductRepository::class, EloquentProductRepository::class);
        $this->app->bind(CategoryRepository::class, EloquentCategoryRepository::class);
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
