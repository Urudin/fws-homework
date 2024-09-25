<?php

use App\Services\ProductService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/upload');
Route::post('/import', [\App\Http\Controllers\ProductController::class, 'import'])->name('import');

Route::get('getXml', [ProductService::class, 'returnAsXml']);
