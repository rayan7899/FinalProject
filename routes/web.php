<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentAffairsController;
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

Route::get('/', [HomeController::class, 'index']);

//ExcelController
Route::get('/excel/add',[ExcelController::class,'add'])->name('AddExcelForm');
Route::post('/excel/import',[ExcelController::class,'import'])->name('importExcel');
Route::get('/excel/export',[ExcelController::class,'export'])->name('ExportExcel');


//StudentController - Edit student form
//Route::get('/students',[StudentController::class,'index'])->name('ShowAllUsers');
Route::get('/student/edit',[StudentController::class,'edit'])->name('EditOneStudent')->middleware('agreement');
Route::post('/student/update',[StudentController::class,'update'])->name('UpdateOneStudent')->middleware('agreement');
Route::get('/student/delete',[StudentController::class,'destroy'])->name('DeleteOneStudent')->middleware('agreement');

Route::post('/community/student/verified-update',[CommunityController::class,'studentDocumentsReviewUpdate'])->name('studentDocumentsReviewUpdate');
Route::post('/community/student/verified-docs',[CommunityController::class,'studentDocumentsReviewVerifiyDocs'])->name('studentDocumentsReviewVerifiyDocs');



//StudentController - Agreement
Route::get('/student/agreement', [StudentController::class, 'agreement_form'])->name('AgreementForm');
Route::post('/student/agreement', [StudentController::class, 'agreement_submit'])->name('AgreementSubmit');

//UserControllaer New passwprd
Route::get('/user/updatepassword', [UserController::class, 'UpdatePasswordForm'])->name('UpdatePasswordForm');
Route::post('/user/updatepassword', [UserController::class, 'UpdatePassword'])->name('UpdatePassword');


Route::get('/privatestate/student/docs/review',[CommunityController::class, 'private_all_student_form'])->name('PrivateAllStudentsForm');
Route::get('/community/student/docs/review',[CommunityController::class, 'studentDocumentsReviewForm'])->name('studentDocumentsReviewForm');
//Route::get('/community/student/docs/review/data',[CommunityController::class, 'studentDocumentsReviewJson'])->name('studentDocumentsReviewJson');


Route::get('documents/{national_id}',[FileController::class,'student_documents'])->name('GetStudentDocuments');
Route::get('documents/show/{path?}',[FileController::class,'get_student_document'])->where('path', '(.*)')->name('GetStudentDocument');

//Auth::routes();
Auth::routes(['register'=> false]);
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/affairs/finalaccepted',[StudentAffairsController::class, 'finalAcceptedForm'])->name('finalAcceptedForm');
Route::post('/affairs/finalaccepted',[StudentAffairsController::class, 'finalAcceptedUpdate'])->name('finalAcceptedUpdate');
Route::get('/affairs/checked',[StudentAffairsController::class, 'checkedStudents'])->name('CheckedStudents');
Route::get('/affairs/new',[StudentAffairsController::class, 'NewStudents'])->name('NewStudents');

