<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController as Home;
use App\Http\Controllers\AuthController as Auth;
use App\Http\Controllers\DashboardController as Dashboard;
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

Route::prefix('admin')->group(function () {
    Route::get('/', [Dashboard::class, 'index'])->name('dashboard');
});