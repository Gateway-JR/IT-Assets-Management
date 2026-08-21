<?php

use App\Http\Controllers\CctvSiteController;
use App\Http\Controllers\ItAssetController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth()->check()
    ? redirect()->route('dashboard')
    : redirect()->route('login'));

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [CctvSiteController::class, 'index'])
        ->name('dashboard');

    Route::get('/cctv-sites/export', [CctvSiteController::class, 'export'])
        ->name('cctv-sites.export');
    Route::get('/cctv-sites/import/template', [CctvSiteController::class, 'downloadImportTemplate'])
        ->name('cctv-sites.import-template');
    Route::post('/cctv-sites/import', [CctvSiteController::class, 'import'])
        ->name('cctv-sites.import');
    Route::resource('cctv-sites', CctvSiteController::class)->except('index');

    Route::post('/it-assets/import', [ItAssetController::class, 'import'])
        ->name('it-assets.import');
    Route::resource('it-assets', ItAssetController::class);

    Route::resource('users', UserController::class)
        ->middleware(EnsureUserIsAdmin::class);
});

require __DIR__.'/auth.php';
