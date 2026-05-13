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
    //return view('dashboard');
    return redirect("suppliers");
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // // CRUD
    // Route::resource('suppliers', SupplierController::class);
    // // CRUD
    // Route::post('suppliers/{supplier}/layups', [CltLayupController::class, 'store'])->name('suppliers.layups.store');
    // Route::put('layups/{clt_layup}', [CltLayupController::class, 'update'])->name('suppliers.layups.update');
    // Route::delete('layups/{clt_layup}', [CltLayupController::class, 'destroy'])->name('suppliers.layups.destroy');
    // // CRUD
    // Route::post('layups/{clt_layup}/layers', [CltLayerController::class, 'store']);
    // Route::delete('layers/{clt_layer}', [CltLayerController::class, 'destroy']);

    // Route::get('suppliers/{supplier}/export', [SupplierController::class, 'export'])->name('suppliers.export');
    // Route::post('suppliers/{supplier}/import', [SupplierController::class, 'import'])->name('suppliers.import');

    // Route::middleware(['verified'])->group(function () {
    //     // Route resource untuk Supplier
    //     Route::resource('suppliers', SupplierController::class);
    //     // Route khusus untuk handle proses import JSON via AJAX
    //     Route::post('suppliers/{supplier}/import-layup', [SupplierController::class, 'importLayup'])->name('suppliers.import-layup');
    // });

    // Route::middleware(['verified'])->group(function () {

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Supplier Routes
    //     |--------------------------------------------------------------------------
    //     */

    //     Route::resource('suppliers', SupplierController::class);

    //     Route::prefix('suppliers/{supplier}')
    //         ->name('suppliers.')
    //         ->group(function () {

    //             // Export / Import
    //             Route::get('export', [SupplierController::class, 'export'])
    //                 ->name('export');

    //             Route::post('import', [SupplierController::class, 'import'])
    //                 ->name('import');

    //             /*
    //             |--------------------------------------------------------------------------
    //             | Layup Routes
    //             |--------------------------------------------------------------------------
    //             */

    //             Route::post('layups', [CltLayupController::class, 'store'])
    //                 ->name('layups.store');
    //         });

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Single Layup Routes
    //     |--------------------------------------------------------------------------
    //     */

    //     Route::prefix('layups/{cltLayup}')
    //         ->name('layups.')
    //         ->group(function () {

    //             Route::get('edit', [CltLayupController::class, 'edit'])
    //                 ->name('edit');

    //             Route::put('/', [CltLayupController::class, 'update'])
    //                 ->name('update');

    //             Route::delete('/', [CltLayupController::class, 'destroy'])
    //                 ->name('destroy');

    //             /*
    //             |--------------------------------------------------------------------------
    //             | Layer Routes
    //             |--------------------------------------------------------------------------
    //             */

    //             Route::post('layers', [CltLayerController::class, 'store'])
    //                 ->name('layers.store');
    //         });

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Single Layer Routes
    //     |--------------------------------------------------------------------------
    //     */

    //     Route::prefix('layers/{cltLayer}')
    //         ->name('layers.')
    //         ->group(function () {

    //             Route::delete('/', [CltLayerController::class, 'destroy'])
    //                 ->name('destroy');
    //         });

    // });

    Route::resource('suppliers', SupplierController::class);

    Route::resource('suppliers.layups', CltLayupController::class)
        ->parameters([
            'layups' => 'cltLayup'
        ])
        ->shallow();

    Route::resource('layups.layers', CltLayerController::class)
        ->parameters([
            'layers' => 'cltLayer'
        ])
        ->shallow();

    Route::get('suppliers/{supplier}/export', [SupplierController::class, 'export'])->name('suppliers.export');
    Route::post('suppliers/{supplier}/import', [SupplierController::class, 'import'])->name('suppliers.import');
    Route::post('suppliers/{supplier}/resolve-conflicts', [SupplierController::class, 'resolveConflicts'])->name('suppliers.resolve-conflicts');

    //...
});

require __DIR__.'/auth.php';
