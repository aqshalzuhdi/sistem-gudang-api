<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LocationWarehouseController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionCategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UnitOfMeasureController;
use Illuminate\Support\Facades\Route;

Route::group(['middlerware' => 'api'], function() {
    Route::prefix('auth')->group(function() { Route::post('login', [AuthController::class, 'login']); });
    Route::middleware('auth:api')->group(function() {
        Route::group(['prefix' => 'auth'], function() {
            Route::get('logout', [AuthController::class, 'logout']);
            Route::get('refresh', [AuthController::class, 'refresh']);
            Route::get('profile', [AuthController::class, 'profile']);
        });
        
        Route::resource('/product-category', ProductCategoryController::class);
        Route::resource('/warehouse', LocationWarehouseController::class);
        Route::resource('/supplier', SupplierController::class);
        Route::resource('/unit-of-measure', UnitOfMeasureController::class);
        Route::resource('/status', StatusController::class);
        Route::resource('/transaction-category', TransactionCategoryController::class);
        Route::resource('/inventory', InventoryController::class);
        Route::resource('/product', ProductController::class);
        Route::resource('/transaction', TransactionController::class);
    });
});
