<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController as Home;
use App\Http\Controllers\AuthController as Auth;
use App\Http\Controllers\DashboardController as Dashboard;
use App\Http\Controllers\CategoryController as Category;
use App\Http\Controllers\ProductController as Product;
use App\Http\Controllers\TableController as Table;
use App\Http\Controllers\OrderController as Order;
use App\Constants\RouteConstant;

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

Route::get('/', [Home::class, 'index'])->name(RouteConstant::HOMEPAGE);
Route::get('/login', [Auth::class, 'index'])->name(RouteConstant::LOGIN);
Route::post('/login', [Auth::class, 'login']);
Route::get('/logout', [Auth::class, 'logout'])->name(RouteConstant::LOGOUT);
Route::get('/404', [Home::class, 'catchError'])->name(RouteConstant::ERROR);

Route::group(['middleware' => ['check.login']], function () {
    //ADMIN
    Route::prefix('admin')->group(function () {
        Route::get('/', [Dashboard::class, 'index'])->name(RouteConstant::DASHBOARD['home']);
        
        //CATEGORY
        Route::prefix('/category')->group(function () {
            Route::get('/', [Category::class, 'index'])->name(RouteConstant::DASHBOARD['category_list']);
            Route::get('/create', [Category::class, 'viewCreate'])->name(RouteConstant::DASHBOARD['category_create']);
            Route::post('/create', [Category::class, 'create']);
            Route::get('/{id}', [Category::class, 'viewUpdate'])->name(RouteConstant::DASHBOARD['category_update'])->where('id', '[0-9]+');
            Route::post('/{id}', [Category::class, 'update'])->where('id', '[0-9]+');
            Route::get('/delete', [Category::class, 'delete'])->name(RouteConstant::DASHBOARD['category_delete']);
        });

        //TABLE
        Route::prefix('/table')->group(function () {
            Route::get('/', [Table::class, 'index'])->name(RouteConstant::DASHBOARD['table_list']);
            Route::get('/create', [Table::class, 'viewCreate'])->name(RouteConstant::DASHBOARD['table_create']);
            Route::post('/create', [Table::class, 'create']);
            Route::get('/{id}', [Table::class, 'viewUpdate'])->name(RouteConstant::DASHBOARD['table_update'])->where('id', '[0-9]+');
            Route::post('/{id}', [Table::class, 'update'])->where('id', '[0-9]+');
            Route::get('/delete', [Table::class, 'delete'])->name(RouteConstant::DASHBOARD['table_delete']);
        });

        //PRODUCT
        Route::prefix('/product')->group(function () {
            Route::get('/', [Product::class, 'index'])->name(RouteConstant::DASHBOARD['product_list']);
            Route::get('/create', [Product::class, 'viewCreate'])->name(RouteConstant::DASHBOARD['product_create']);
            Route::post('/create', [Product::class, 'create']);
            Route::get('/{id}', [Product::class, 'viewUpdate'])->name(RouteConstant::DASHBOARD['product_update'])->where('id', '[0-9]+');
            Route::post('/{id}', [Product::class, 'update'])->where('id', '[0-9]+');
            Route::get('/delete', [Product::class, 'delete'])->name(RouteConstant::DASHBOARD['product_delete']);
        });
    });

    // HOME
    Route::get('/table', [Table::class, 'viewIndex'])->name(RouteConstant::HOME['table_list']);
    Route::get('/table/{id}', [Table::class, 'viewDetail'])->name(RouteConstant::HOME['table_detail'])->where('id', '[0-9]+');

    Route::prefix('order')->group(function () {
        Route::get('/', [Home::class, 'getProductsScreenOrder'])->name(RouteConstant::HOME['order_products']);
        Route::get('/add', [Order::class, 'createSessionOrder'])->name(RouteConstant::HOME['order_add']);
        Route::get('/update', [Order::class, 'updateOrder'])->name(RouteConstant::HOME['order_update']);
        Route::get('/delete', [Order::class, 'deleteOrder'])->name(RouteConstant::HOME['order_delete']);
        Route::get('/submit', [Order::class, 'submitOrder'])->name(RouteConstant::HOME['order_submit']);
        Route::get('/remove-all', [Order::class, 'remove'])->name(RouteConstant::HOME['order_remove']);
        Route::get('/checkout/{id}', [Order::class, 'checkOut'])->name(RouteConstant::HOME['order_checkout'])->where('id', '[0-9]+');
    });
});

