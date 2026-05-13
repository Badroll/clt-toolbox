<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CltLayupController;
use App\Http\Controllers\CltLayerController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD
    Route::resource('suppliers', SupplierController::class);
    // CRUD
    Route::post('suppliers/{supplier}/layups', [CltLayupController::class, 'store']);
    Route::put('layups/{clt_layup}', [CltLayupController::class, 'update']);
    Route::delete('layups/{clt_layup}', [CltLayupController::class, 'destroy']);
    // CRUD
    Route::post('layups/{clt_layup}/layers', [CltLayerController::class, 'store']);
    Route::delete('layers/{clt_layer}', [CltLayerController::class, 'destroy']);

    Route::get('suppliers/{supplier}/export', [SupplierController::class, 'export']);
    Route::post('suppliers/{supplier}/import', [SupplierController::class, 'import']);
});

require __DIR__.'/auth.php';
