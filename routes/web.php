<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\master\ConsumableController;
use App\Http\Controllers\admin\master\CostController;
use App\Http\Controllers\admin\master\GroupController;
use App\Http\Controllers\admin\master\LineGroupController;
use App\Http\Controllers\admin\transaction\LineController;
use App\Http\Controllers\admin\master\MasterLineController;
use App\Http\Controllers\admin\master\MaterialController;
use App\Http\Controllers\admin\master\PlanController;
use App\Http\Controllers\admin\master\RoleController;
use App\Http\Controllers\admin\master\SlocController;
use App\Http\Controllers\admin\master\TypeMtController;
use App\Http\Controllers\admin\user\UsersController;


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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('register', [RegisteredUserController::class, 'create'])->middleware('role:1,2,3');


Route::group(['middleware' => ['role:1,2,3'], 'prefix' => 'AdminMaster'], function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('Admin.dashboard');
    Route::resource('lines', MasterLineController::class);
    Route::resource('MasterLine', MasterLineController::class);
    Route::resource('Plan', PlanController::class);

    Route::resource('/User', UsersController::class);
    Route::resource('/Role', RoleController::class);
    Route::resource('/Cost', CostController::class);
    Route::resource('/Group', GroupController::class);
    Route::resource('/Sloc', SlocController::class);
    // Route::resource('/Type', TypeMtController::class);
    Route::resource('/LineGroup', LineGroupController::class);
    Route::resource('/Material', MaterialController::class);
    Route::resource('/Consumable', ConsumableController::class);
    Route::get('/download-template', [MaterialController::class, 'downloadTemplate'])->name('download.file');

});

Route::get('/profile', [ProfileController::class, 'edit'])->middleware(['role:1,2,3,4,5'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->middleware(['role:1,2,3,4,5'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->middleware(['role:1,2,3,4,5'])->name('profile.destroy');



Route::group(['middleware' => ['role:4,5'], 'prefix' => 'page'], function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('page.dashboard');


});

Route::group(['middleware' => ['role:1,5'], 'prefix' => 'Transaction'], function () {
    Route::get('/ListLine', [LineController::class, 'indexLine'])->name('listLine');
    Route::get('/{id}/material', [LineController::class, 'indexMaterial'])->name('listMaterial');
    Route::get('/{line}/{material}', [LineController::class, 'indexConsumable'])->name('listConsumable');
    Route::post('/preview', [LineController::class, 'preview'])->name('preview');

});

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('auth') // Gunakan middleware auth
    ->name('register');


// useless routes
// Just to demo sidebar dropdown links active states.
// Route::get('/buttons/text', function () {
//     return view('buttons-showcase.text');
// })->middleware(['auth'])->name('buttons.text');

// Route::get('/buttons/icon', function () {
//     return view('buttons-showcase.icon');
// })->middleware(['auth'])->name('buttons.icon');

// Route::get('/buttons/text-icon', function () {
//     return view('buttons-showcase.text-icon');
// })->middleware(['auth'])->name('buttons.text-icon');

require __DIR__ . '/auth.php';