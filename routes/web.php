<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicCatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', DashboardController::class)
    ->middleware('auth')
    ->name('dashboard');

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Catálogo público por negocio (catch-all — debe ir al final)
|--------------------------------------------------------------------------
*/
Route::get('/{slug}/productos', [PublicCatalogController::class, 'products'])
    ->where('slug', '^(?!app$|dev$)[A-Za-z0-9\-]+$')
    ->name('public.catalog.products');

Route::get('/{slug}/producto/{productoSlugOrId}', [PublicCatalogController::class, 'product'])
    ->where('slug', '^(?!app$|dev$)[A-Za-z0-9\-]+$')
    ->name('public.catalog.product');

Route::get('/{slug}', [PublicCatalogController::class, 'catalog'])
    ->where('slug', '^(?!app$|dev$)[A-Za-z0-9\-]+$')
    ->name('public.catalog');
