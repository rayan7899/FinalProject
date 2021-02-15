<?php

use App\Http\Controllers\ImportExcelController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

//ImportExcelController
Route::get('/excel/add',[ImportExcelController::class,'add'])->name('AddExcelForm');
Route::post('/excel/import',[ImportExcelController::class,'import'])->name('importExcel');

//UserController - Edit user form
//Route::get('/users',[UserController::class,'index'])->name('ShowAllUsers');
Route::get('/user/edit',[UserController::class,'edit'])->name('EditOneUser');
Route::post('/user/update',[UserController::class,'update'])->name('UpdateOneUser');

//UserController - Agreement
Route::get('/user/agreement', [UserController::class, 'agreement_form'])->name('AgreementForm');
Route::post('/user/agreement', [UserController::class, 'agreement_submit'])->name('AgreementSubmit');

//UserControllaer New passwprd
Route::get('/user/updatepassword', [UserController::class, 'UpdatePasswordForm'])->name('UpdatePasswordForm');
Route::post('/user/updatepassword', [UserController::class, 'UpdatePassword'])->name('UpdatePassword');


//Auth::routes();
Auth::routes(['register'=> false]);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
