<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BiersysteemController;
use App\Http\Controllers\AdminController;
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

//Biersysteem routes
Route::get('/biersysteem', [BiersysteemController::class, 'LoadBierstandData']);
Route::post('/biersysteem/update', [BiersysteemController::class, 'UpdateBierstand']);

//Admin routes
Route::get('/biersysteem/admin', [AdminController::class, 'LoadAdminPage']);
Route::get('/biersysteem/admin/addperson', [AdminController::class, 'LoadAdminPage_AddPerson']);
Route::post('/biersysteem/admin/addperson', [AdminController::class, 'AddPerson'])->name('addperson');
Route::get('/biersysteem/admin/editperson', [AdminController::class, 'LoadAdminPage_EditPerson']);
Route::post('/biersysteem/admin/editperson/{id}', [AdminController::class, 'UpdateValue'])->name('updateperson');
Route::post('/biersysteem/admin/editperson/{id}/name', [AdminController::class, 'UpdateName'])->name('updatename');
Route::get('/biersysteem/admin/person/{id}/mutations', [AdminController::class, 'GetMutationsForUser']);

// Route::group(['middleware' => 'auth'], function(){
//     Route::group([
//         'middleware' => 'is_admin'
//     ], function(){
//         Route::get('/biersysteem/admin', [AdminController::class, 'LoadAdminPage']);
//         Route::get('/biersysteem/admin/addperson', [AdminController::class, 'LoadAdminPage_AddPerson']);
//         Route::post('/biersysteem/admin/addperson', [AdminController::class, 'AddPerson'])->name('addperson');
//         Route::get('/biersysteem/admin/editperson', [AdminController::class, 'LoadAdminPage_EditPerson']);
//     });
// });

//Authentication & authorization
Route::group(['prefix' => 'biersysteem'], function () {
    Auth::routes();
});

Route::get('/biersysteem/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
