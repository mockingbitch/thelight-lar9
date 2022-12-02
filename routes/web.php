<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController as Home;
use App\Http\Controllers\AuthController as Auth;
use App\Http\Controllers\DashboardController as Dashboard;
use App\Http\Controllers\CategoryController as Category;
use App\Http\Controllers\ProductController as Product;
use App\Http\Controllers\TableController as Table;
use App\Http\Controllers\OrderController as Order;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [Home::class, 'index'])->name('home');
Route::get('/login', [Auth::class, 'index'])->name('login');
Route::post('/login', [Auth::class, 'login']);
Route::get('/logout', [Auth::class, 'logout'])->name('logout');
Route::get('/404', [Home::class, 'catchError'])->name('404');

Route::group(['middleware' => ['check.login']], function () {
    //ADMIN
    Route::prefix('admin')->group(function () {
        Route::get('/', [Dashboard::class, 'index'])->name('dashboard.home');
        
        //CATEGORY
        Route::prefix('/category')->group(function () {
            Route::get('/', [Category::class, 'index'])->name('dashboard.category.list');
            Route::get('/create', [Category::class, 'viewCreate'])->name('dashboard.category.create');
            Route::post('/create', [Category::class, 'create']);
            Route::get('/{id}', [Category::class, 'viewUpdate'])->name('dashboard.category.update');
            Route::post('/{id}', [Category::class, 'update']);
            Route::get('/delete', [Category::class, 'delete'])->name('dashboard.category.delete');
        });

        //TABLE
        Route::prefix('/table')->group(function () {
            Route::get('/', [Table::class, 'index'])->name('dashboard.table.list');
            Route::get('/create', [Table::class, 'viewCreate'])->name('dashboard.table.create');
            Route::post('/create', [Table::class, 'create']);
            Route::get('/{id}', [Table::class, 'viewUpdate'])->name('dashboard.table.update');
            Route::post('/{id}', [Table::class, 'update']);
            Route::get('/delete', [Table::class, 'delete'])->name('dashboard.table.delete');
        });

        //PRODUCT
        Route::prefix('/product')->group(function () {
            Route::get('/', [Product::class, 'index'])->name('dashboard.product.list');
            Route::get('/create', [Product::class, 'viewCreate'])->name('dashboard.product.create');
            Route::post('/create', [Product::class, 'create']);
            Route::get('/{id}', [Product::class, 'viewUpdate'])->name('dashboard.product.update');
            Route::post('/{id}', [Product::class, 'update']);
            Route::get('/delete', [Product::class, 'delete'])->name('dashboard.product.delete');
        });
    });

    // HOME
    Route::get('/table', [Table::class, 'viewIndex'])->name('home.table');
    Route::get('/table/{id}', [Table::class, 'viewDetail'])->name('home.table.detail');

    Route::prefix('order')->group(function () {
        Route::get('/', [Home::class, 'getProductsScreenOrder'])->name('home.order');
        Route::get('/add', [Order::class, 'createSessionOrder'])->name('home.order.add');
        Route::get('/update', [Order::class, 'updateSessionOrder'])->name('home.order.update');
        Route::get('/delete', [Order::class, 'deleteSessionOrder'])->name('home.order.delete');
        Route::get('/submit', [Order::class, 'submitOrder'])->name('home.order.submit');
        Route::get('/remove-all', [Order::class, 'remove'])->name('home.order.remove');
        Route::get('/checkout/{id}', [Order::class, 'checkOut'])->name('home.order.checkout');
    });
});

