<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentAffairsController;
use App\Http\Controllers\DepartmentBossController;
use App\Http\Controllers\FalteringStudentsController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\StudentCoursesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TransactionController;

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

//Logs Viewer
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

//ExcelController
Route::get('/excel/new/add',[ExcelController::class,'importNewForm'])->name('AddExcelForm');
Route::post('/excel/new/import',[ExcelController::class,'importNewUsers'])->name('importExcel');
//Route::get('/excel/new/export',[ExcelController::class,'exportNewUsers'])->name('ExportExcel');

 //Old users
Route::get('/excel/old/add',[ExcelController::class,'importOldForm'])->name('OldForm');
Route::post('/excel/old/import',[ExcelController::class,'importOldUsers'])->name('OldImport');
//Route::get('/excel/old/export',[ExcelController::class,'exportOldUsers'])->name('ExportExcel');


//StudentController - Edit student form
//Route::get('/students',[StudentController::class,'index'])->name('ShowAllUsers');
Route::get('/student/edit',[StudentController::class,'edit'])->name('EditOneStudent')->middleware('agreement');
Route::post('/student/update',[StudentController::class,'update'])->name('UpdateOneStudent')->middleware('agreement');
Route::get('/student/delete',[StudentController::class,'destroy'])->name('DeleteOneStudent');
Route::post('/student/level',[StudentController::class,'getStudentOnLevel'])->name('getStudentOnLevel');
Route::post('/student/update-state/',[StudentController::class,'updateStudentState'])->name('updateStudentState');

// Student wallet PaymentController
Route::get('/student/wallet/main',[PaymentController::class,'main'])->name('walletMain');
Route::get('/student/wallet/payment/from',[PaymentController::class,'form'])->name('paymentForm');
Route::post('/student/wallet/payment/store',[PaymentController::class,'store'])->name('paymentStore');

// Student wallet PaymentController (json)
// Well be move from CommunityController
// Route::post('/community/student/payments/verified-update',[TransactionController::class,'paymentsReviewUpdate'])->name('paymentsReviewUpdate');
// Route::post('/community/student/payments/verified-docs',[TransactionController::class,'paymentsReviewVerifiyDocs'])->name('paymentsReviewVerifiyDocs');

// Student Courses Orders OrderController
Route::get('/student/order/form',[OrderController::class,'form'])->name('orderForm');
Route::post('/student/order/store',[OrderController::class,'store'])->name('orderStore');

//StudentController - Agreement
Route::get('/student/agreement', [StudentController::class, 'agreement_form'])->name('AgreementForm');
Route::post('/student/agreement', [StudentController::class, 'agreement_submit'])->name('AgreementSubmit');

//UserControllaer New passwprd
Route::get('/user/updatepassword', [UserController::class, 'UpdatePasswordForm'])->name('UpdatePasswordForm');
Route::post('/user/updatepassword', [UserController::class, 'UpdatePassword'])->name('UpdatePassword');


// CommunityController
Route::get('/community',[CommunityController::class, 'dashboard'])->name('communityDashboard');
Route::get('/private',[CommunityController::class, 'privateDashboard'])->name('privateDashboard');
Route::get('/privatestate/student/docs/review',[CommunityController::class, 'private_all_student_form'])->name('PrivateAllStudentsForm');
Route::get('/community/student/docs/review',[CommunityController::class, 'paymentsReviewForm'])->name('paymentsReviewForm');
Route::get('/community/create-user',[CommunityController::class, 'createUser'])->name('createUser');
Route::post('/community/student/payments/verified-update',[CommunityController::class,'paymentsReviewUpdate'])->name('paymentsReviewUpdate');
Route::post('/community/student/payments/verified-docs',[CommunityController::class,'paymentsReviewVerifiyDocs'])->name('paymentsReviewVerifiyDocs');
Route::get('/community/new-semester',[CommunityController::class,'newSemester'])->name('newSemester');
Route::get('/community/publish-to-rayat',[CommunityController::class, 'publishToRayatForm'])->name('publishToRayatFormCommunity');
Route::post('/community/publish-to-rayat',[CommunityController::class, 'publishToRayat'])->name('publishToRayatCommunity');
Route::get('/community/rayat-report',[CommunityController::class, 'rayatReportForm'])->name('rayatReportFormCommunity');
Route::get('/community/students-states',[CommunityController::class, 'studentsStates'])->name('studentsStates');


Route::get('documents/{national_id}',[FileController::class,'student_documents'])->name('GetStudentDocuments');
Route::get('documents/show/{path?}',[FileController::class,'get_student_document'])->where('path', '(.*)')->name('GetStudentDocument');

//Auth::routes();
Auth::routes(['register'=> false]);
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/affairs/dashboard',[StudentAffairsController::class, 'dashboard'])->name('affairsDashboard');
Route::get('/affairs/finalaccepted',[StudentAffairsController::class, 'finalAcceptedForm'])->name('finalAcceptedForm');
Route::post('/affairs/finalaccepted',[StudentAffairsController::class, 'finalAcceptedUpdate'])->name('finalAcceptedUpdate');
Route::get('/affairs/finalaccepted/list',[StudentAffairsController::class, 'finalAcceptedList'])->name('finalAcceptedList');
Route::get('/affairs/checked',[StudentAffairsController::class, 'checkedStudents'])->name('CheckedStudents');
Route::get('/affairs/new',[StudentAffairsController::class, 'NewStudents'])->name('NewStudents');
Route::get('/affairs/publish-to-rayat',[StudentAffairsController::class, 'publishToRayatForm'])->name('publishToRayatForm');
Route::post('/affairs/publish-to-rayat',[StudentAffairsController::class, 'publishToRayat'])->name('publishToRayat');
Route::get('/affairs/rayat-report',[StudentAffairsController::class, 'rayatReportForm'])->name('rayatReportForm');


//departmentBoss
Route::get('/deptBoss',[DepartmentBossController::class, 'dashboard'])->name('deptBossDashboard');
Route::get('/deptBoss/manage-courses',[DepartmentBossController::class, 'index'])->name('manageCourses');
Route::get('/deptBoss/courses-data',[DepartmentBossController::class, 'getCoursesData'])->name('getCoursesData');
Route::post('/deptBoss/courses/update-level',[DepartmentBossController::class, 'updateCoursesLevel'])->name('updateCoursesLevel');
Route::get('/deptBoss/faltering-students',[falteringStudentsController::class, 'index'])->name('falteringStudents');
Route::get('/deptBoss/student-info/{id}',[StudentController::class, 'getStudentInfo'])->name('studentInfo');
Route::get('/major/{majorId}/courses',[CourseController::class, 'getCoursesByMajorId']);

Route::post('/student-courses/add',[StudentCoursesController::class, 'addCourseToStudent']);


Route::get('/majorsByProgramId/{id}',[MajorController::class, 'getmajorsByProgramId']);

Route::post('/student-courses/delete', [StudentCoursesController::class, 'deleteCourseFromStudent'])->name('deleteCourse');

