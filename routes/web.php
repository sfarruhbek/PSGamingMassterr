<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function (){ return redirect()->route('main.index'); })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/main/index', [MainController::class, 'index'])->name('main.index')->middleware(['auth', 'verified']);
Route::get('/main/types', [MainController::class, 'types'])->name('main.types')->middleware(['auth', 'verified']);
Route::get('/main/devices', [MainController::class, 'devices'])->name('main.devices')->middleware(['auth', 'verified']);
Route::get('/main/history', [MainController::class, 'history'])->name('main.history')->middleware(['auth', 'verified']);
Route::get('/main/product-history', [MainController::class, 'product_history'])->name('main.product-history')->middleware(['auth', 'verified']);
Route::get('/main/dashboard', [MainController::class, 'dashboard'])->name('main.dashboard')->middleware(['auth', 'verified']);
Route::get('/main/products', [MainController::class, 'products'])->name('main.products')->middleware(['auth', 'verified']);


Route::resource('/res/type', TypeController::class)->middleware(['auth', 'verified'])->names('res.type');
Route::resource('/res/device', DeviceController::class)->middleware(['auth', 'verified'])->names('res.device');
Route::resource('/res/product', ProductController::class)->middleware(['auth', 'verified'])->names('res.product');

Route::post('/res/device/start/{id}', [DeviceController::class, 'start'])->name('res.device.start');

Route::post('/res/device/finish/{device}/{history}', [DeviceController::class, 'finishSingleUser']);
Route::post('/res/device/finish-all/{device}', [DeviceController::class, 'finishAllUsers']);

Route::post('product/add-product', [ProductController::class,'add_product'])->name('product.add-product');
Route::post('/res/sell-product', [ProductController::class, 'sell_product'])->name('sell.product');

//API
Route::get('/api/products', [ApiController::class, 'products'])->name('api.products');
