<?php

use App\Http\Controllers\Installer\InstallerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['NotInstalledCheck', 'XSS'])->prefix('install')->group(function () {
    Route::get('initialize', [InstallerController::class,'index'])->name('install.initialize');
    Route::get('finalize',  [InstallerController::class,'final'])->name('install.finalize');
});

Route::middleware(['XSS'])->prefix('install')->group(function () {
    Route::post('process', [InstallerController::class,'getInstall'])->name('install.process');
});


