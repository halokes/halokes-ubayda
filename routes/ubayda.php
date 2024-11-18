<?php

use App\Http\Controllers\Ubayda\BusinessController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Saas Routes
|--------------------------------------------------------------------------
|
|   All routes relates to SaaS operation (not the main business operations)
|   is stored in here
|
*/

Route::middleware('auth')->group(function () {
    //PACKAGES RELATED
    Route::prefix('/ubayda')
        ->middleware('role:ROLE_ADMIN,ROLE_SUPERVISOR')
        ->group(function () {


            Route::prefix('/business')
                ->group(function () {
                    Route::get('/',     [BusinessController::class, 'index'])->name('ubayda.business.index');

                    Route::get('/add',  [BusinessController::class, 'create'])->name('ubayda.business.add');
                    Route::post('/add', [BusinessController::class, 'store'])->name('ubayda.business.store');

                    Route::get('/{id}', [BusinessController::class, 'detail'])->name('ubayda.business.detail');

                    Route::get('/edit/{id}', [BusinessController::class, 'edit'])->name('ubayda.business.edit');
                    Route::put('/edit/{id}', [BusinessController::class, 'update'])->name('ubayda.business.update');

                    Route::get('/delete/{id}', [BusinessController::class, 'deleteConfirm'])->name('ubayda.business.delete');
                    Route::delete('/delete/{id}', [BusinessController::class, 'destroy'])->name('ubayda.business.destroy');
                });
        });
});
