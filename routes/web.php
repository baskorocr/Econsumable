<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\master\ConsumableController;
use App\Http\Controllers\admin\master\CostController;
use App\Http\Controllers\admin\master\GroupController;
use App\Http\Controllers\admin\master\LineGroupController;
use App\Http\Controllers\transaction\LinesController;

use App\Http\Controllers\admin\master\MasterLineController;
use App\Http\Controllers\admin\master\MaterialController;
use App\Http\Controllers\admin\master\PlanController;
use App\Http\Controllers\admin\master\RoleController;
use App\Http\Controllers\admin\master\SlocController;
use App\Http\Controllers\admin\master\TypeMtController;
use App\Http\Controllers\admin\user\UsersController;
use App\Http\Controllers\transaction\ApprovalController;
use App\Http\Controllers\transaction\ReportController;
use App\Http\Controllers\transaction\TransactionController;


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
})->name('home');

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
    // Route::resource('/Material', MaterialController::class);
    Route::resource('/Consumable', ConsumableController::class);
    Route::get('/download-template', [MaterialController::class, 'downloadTemplate'])->name('download.file');
    Route::post('/upload-excel', [ConsumableController::class, 'uploadExcel'])->name('upload.excel');
    Route::get('/line/search', [LinesController::class, 'search'])->name('line.search');
    Route::get('/material/search', [LinesController::class, 'searchMaterial'])->name('material.search');
    Route::get('/consumable/search', [LinesController::class, 'searchConsumable'])->name('consumable.search');

});

Route::group(['middleware' => ['role:1,2,3,4,'], 'prefix' => 'list'], function () {
    Route::get('/approvalConfirmation', [ApprovalController::class, 'index'])->name('approvalConfirmation.index');
    Route::get('/accConfirmation/{id}', [ApprovalController::class, 'acc'])->name('approvalConfirmation.acc');
    Route::get('/rejectConfirmation/{id}', [ApprovalController::class, 'reject'])->name('approvalConfirmation.reject');
    Route::post('/resend', [ApprovalController::class, 'resend'])->name('sap.resend');

});

Route::get('/appr/{id}/{token}', [ApprovalController::class, 'apprNon'])->name('appr.show');
Route::post('/acc', [ApprovalController::class, 'accNon'])->name('acc');
Route::post('/reject', [ApprovalController::class, 'rejectNon'])->name('reject');



Route::get('/profile', [ProfileController::class, 'edit'])->middleware(['role:1,2,3,4,5'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->middleware(['role:1,2,3,4,5'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->middleware(['role:1,2,3,4,5'])->name('profile.destroy');
Route::get('/report', [ReportController::class, 'index'])->middleware(['role:1,2,3,4,5'])->name('index.report');
Route::get('/get-lines', [ReportController::class, 'getLinesBySegment'])->middleware(['role:1,2,3,4,5'])->name('getLinesBySegment');

Route::group(['middleware' => ['role:4'], 'prefix' => 'sap'], function () {
    Route::get('/status', [ApprovalController::class, 'indexStatus'])->name('sap.status');

});



Route::group(['middleware' => ['role:4,5'], 'prefix' => 'page'], function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('page.dashboard');


});

Route::group(['middleware' => ['role:1,5'], 'prefix' => 'Transaction'], function () {
    Route::get('/ListGroup', [LinesController::class, 'indexGroup'])->name('listGroup');
    Route::get('/{id}/line', [LinesController::class, 'indexLine'])->name('listLine');
    Route::get('/{line}/{material}', [LinesController::class, 'indexConsumable'])->name('listConsumable');
    // Route::post('/sapSend', [ApprovalController::class, 'sapSend'])->name('sapSend');
    Route::resource('/proses', TransactionController::class);

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