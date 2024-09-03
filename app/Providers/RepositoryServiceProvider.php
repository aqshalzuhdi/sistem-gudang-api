<?php

namespace App\Providers;

use App\Interfaces\InventoryRepositoryInterface;
use App\Interfaces\LocationWarehouseRespositoryInterface;
use App\Interfaces\ProductCategoryRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\StatusRepositoryInterface;
use App\Interfaces\SupplierRepositoryInterface;
use App\Interfaces\TransactionCategoryRepositoryInterface;
use App\Interfaces\TransactionRepositoryInterface;
use App\Interfaces\UnitOfMeasureRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\InventoryRepository;
use App\Repositories\LocationWarehouseRepository;
use App\Repositories\ProductCategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\StatusRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\TransactionCategoryRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UnitOfMeasureRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProductCategoryRepositoryInterface::class, ProductCategoryRepository::class);
        $this->app->bind(LocationWarehouseRespositoryInterface::class, LocationWarehouseRepository::class);
        $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->bind(UnitOfMeasureRepositoryInterface::class, UnitOfMeasureRepository::class);
        $this->app->bind(StatusRepositoryInterface::class, StatusRepository::class);
        $this->app->bind(TransactionCategoryRepositoryInterface::class, TransactionCategoryRepository::class);
        $this->app->bind(InventoryRepositoryInterface::class, InventoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
