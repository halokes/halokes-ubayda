<?php

use App\Http\Controllers\Ubayda\BusinessController;
use App\Http\Controllers\Ubayda\BusinessUserController;
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


            Route::prefix('/business/admin')
                ->group(function () {
                    Route::get('/',     [BusinessController::class, 'indexAdmin'])->name('ubayda.business.admin.index');

                    Route::get('/add',  [BusinessController::class, 'create'])->name('ubayda.business.admin.add');
                    Route::post('/add', [BusinessController::class, 'store'])->name('ubayda.business.admin.store');

                    Route::get('/{id}', [BusinessController::class, 'detailAdmin'])->name('ubayda.business.admin.detail');

                    Route::get('/suspend/{id}', [BusinessController::class, 'suspendAdmin'])->name('ubayda.business.admin.suspend');
                    Route::get('/unsuspend/{id}', [BusinessController::class, 'unsuspendAdmin'])->name('ubayda.business.admin.unsuspend');

                    Route::get('/edit/{id}', [BusinessController::class, 'edit'])->name('ubayda.business.admin.edit');
                    Route::put('/edit/{id}', [BusinessController::class, 'update'])->name('ubayda.business.admin.update');

                    Route::get('/delete/{id}', [BusinessController::class, 'deleteConfirm'])->name('ubayda.business.admin.delete');
                    Route::delete('/delete/{id}', [BusinessController::class, 'destroy'])->name('ubayda.business.admin.destroy');
                });
        });

    //PACKAGES RELATED
    Route::prefix('/ubayda')
        ->middleware('role:ROLE_USER')
        ->group(function () {

            Route::prefix('/mybusiness')
                ->group(function () {
                    Route::get('/',     [BusinessUserController::class, 'indexUserBusiness'])->name('ubayda.business.user.index');

                    Route::get('/add',  [BusinessUserController::class, 'createUserBusiness'])->name('ubayda.business.user.add');
                    Route::post('/add', [BusinessUserController::class, 'storeUserBusiness'])->name('ubayda.business.user.store');

                    Route::get('/select/{id}', [BusinessUserController::class, 'selectUserBusiness'])->name('ubayda.business.user.select');
                    Route::get('/{id}', [BusinessUserController::class, 'detailUserBusiness'])->name('ubayda.business.user.detail');

                    Route::get('/edit/{id}', [BusinessUserController::class, 'editUserBusiness'])->name('ubayda.business.user.edit');
                    Route::put('/edit/{id}', [BusinessUserController::class, 'updateUserBusiness'])->name('ubayda.business.user.update');

                    Route::get('/delete/{id}', [BusinessUserController::class, 'deleteConfirmUserBusiness'])->name('ubayda.business.user.delete');
                    Route::delete('/delete/{id}', [BusinessUserController::class, 'destroyUserBusiness'])->name('ubayda.business.user.destroy');
                });
        });
});
