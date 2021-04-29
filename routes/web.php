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
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\StudentCoursesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PrivateStateController;
use App\Http\Controllers\RefundOrderController;
use App\Http\Controllers\TransactionController;
use App\Models\User;

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

//Auth::routes();
Auth::routes(['register' => false]);

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/deptBoss', [DepartmentBossController::class, 'dashboard'])->name('deptBossDashboard');

    // FIXME: this route is not used anywhere, shall we remove it?
    Route::get('/documents/{national_id}', [FileController::class, 'student_documents'])->name('GetStudentDocuments');
    Route::get('/documents/show/{path?}', [FileController::class, 'get_student_document'])
        ->where('path', '(.*)')
        ->name('GetStudentDocument');

    // TODO: disable this in release
    //Logs Viewer
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});

// خدمة المجتمع
Route::middleware(['auth', 'role:خدمة المجتمع'])->group(function () {
    Route::get('/community', [CommunityController::class, 'dashboard'])->name('communityDashboard');
    Route::get('/community/student/payments/review', [CommunityController::class, 'paymentsReviewForm'])->name('paymentsReviewForm');
    Route::post('/community/student/payments/verified-update', [CommunityController::class, 'paymentsReviewUpdate'])->name('paymentsReviewUpdate');
    Route::post('/community/student/payments/verified-docs', [CommunityController::class, 'paymentsReviewVerifiyDocs'])->name('paymentsReviewVerifiyDocs');
    Route::get('/community/new-semester', [CommunityController::class, 'newSemesterForm'])->name('newSemesterForm');
    Route::post('/community/new-semester', [CommunityController::class, 'newSemester'])->name('newSemester');
    // Route::get('/community/publish-to-rayat/{type}', [CommunityController::class, 'publishToRayatForm'])->name('publishToRayatForm');
    // Route::post('/community/publish-to-rayat', [CommunityController::class, 'publishToRayat'])->name('publishToRayatStore');
    Route::get('/community/rayat-report', [CommunityController::class, 'rayatReportForm'])->name('rayatReportFormCommunity');
    Route::get('/community/students-states', [CommunityController::class, 'studentsStates'])->name('studentsStates');
    Route::get('/community/old-students-report', [CommunityController::class, 'oldStudentsReport'])->name('oldStudentsReport');
    Route::get('/community/new-students-report', [CommunityController::class, 'newStudentsReport'])->name('newStudentsReport');
    Route::get('/community/users/create', [CommunityController::class, 'createUserForm'])->name('createUserForm');
    Route::post('/community/users/store', [CommunityController::class, 'createUserStore'])->name('createUserStore');
    Route::get('/community/users/edit/{user}', [CommunityController::class, 'editUserForm'])->name('editUserForm');
    Route::post('/community/users/update/{user}', [CommunityController::class, 'editUserUpdate'])->name('editUserUpdate');
    // Route::get('/community/users/delete/{user}', [CommunityController::class, 'deleteUser'])->name('deleteUser');
    Route::post('/community/users/permissions/update/{user}', [CommunityController::class, 'editUserPermissionsUpdate'])->name('editUserPermissionsUpdate');
    Route::get('/community/users/permission/delete/{permission}', [CommunityController::class, 'deleteUserPermission'])->name('deleteUserPermission');
    Route::get('/community/users/manage', [CommunityController::class, 'manageUsersForm'])->name('manageUsersForm');
    
    //Manage Courses
    Route::get('/community/courses', [CommunityController::class, 'coursesIndex'])->name('coursesIndex');
    Route::get('/community/courses/create', [CommunityController::class, 'createCourseForm'])->name('createCourseForm');
    Route::post('/community/courses/store', [CommunityController::class, 'createCourse'])->name('createCourse');
    Route::get('/community/courses/edit/{course}', [CommunityController::class, 'editCourseForm'])->name('editCourseForm');
    Route::post('/community/courses/update', [CommunityController::class, 'editCourse'])->name('editCourse');
    Route::get('/community/courses/delete/{course}', [CommunityController::class, 'deleteCourse'])->name('deleteCourse');
    
    //charge student wallet direct
    Route::get('/api/community/student/{id}', [CommunityController::class, 'getStudent'])->name('apiCommunityGetStudent');
    Route::get('/community/charge', [CommunityController::class, 'chargeForm'])->name('chargeForm');
    Route::post('/community/charge', [CommunityController::class, 'charge'])->name('charge');

    //Reports
    Route::get('/community/reports/all', [CommunityController::class, 'reportAllForm'])->name('reportAllForm');
    Route::get('/community/reports/filterd', [CommunityController::class, 'reportFilterdForm'])->name('reportFilterdForm');
    Route::post('/community/reports/filterd', [CommunityController::class, 'reportFilterd'])->name('reportFilterd');

    //refunds
    Route::get('/community/refund-orders', [CommunityController::class, 'refundOrdersForm'])->name('refundOrdersForm');
    Route::post('/api/community/refund-orders', [CommunityController::class, 'refundOrdersUpdate'])->name('apiRefundOrdersUpdate');


});


// FIXME: these routes are shard between `شؤون المتدربين` and `خدمة المجتمع`,
//        and I cannot find way to tell our middleware `role` that.
Route::middleware(['auth', 'agreement'])->group(function () {
    Route::get('/publish-to-rayat/{type}', [CommunityController::class, 'publishToRayatForm'])->name('publishToRayatForm');
    Route::post('/publish-to-rayat', [CommunityController::class, 'publishToRayat'])->name('publishToRayatStore');
});

// شؤون المتدربين
Route::middleware(['auth', 'role:شؤون المتدربين'])->group(function () {
    Route::get('/affairs/dashboard', [StudentAffairsController::class, 'dashboard'])->name('affairsDashboard');
    Route::get('/affairs/finalaccepted', [StudentAffairsController::class, 'finalAcceptedForm'])->name('finalAcceptedForm');
    Route::post('/affairs/finalaccepted', [StudentAffairsController::class, 'finalAcceptedUpdate'])->name('finalAcceptedUpdate');
    Route::get('/affairs/finalaccepted/list', [StudentAffairsController::class, 'finalAcceptedList'])->name('finalAcceptedList');
    Route::get('/affairs/checked', [StudentAffairsController::class, 'checkedStudents'])->name('CheckedStudents');
    Route::get('/affairs/new', [StudentAffairsController::class, 'NewStudents'])->name('NewStudents');
    // ExcelController
    Route::get('/excel/new/add', [ExcelController::class, 'importNewForm'])->name('AddExcelForm');
    //Route::get('/excel/new/export',[ExcelController::class,'exportNewUsers'])->name('ExportExcel');
    Route::post('/excel/new/import', [ExcelController::class, 'importNewUsers'])->name('importExcel');
    // Old users
    Route::get('/excel/old/add', [ExcelController::class, 'importOldForm'])->name('OldForm');
    Route::post('/excel/old/import', [ExcelController::class, 'importOldUsers'])->name('OldImport');
    // //Route::get('/excel/old/export',[ExcelController::class,'exportOldUsers'])->name('ExportExcel');
    Route::get('/affairs/rayat-report', [StudentAffairsController::class, 'rayatReportForm'])->name('rayatReportForm');
    // Route::get('/community/publish-to-rayat/{type}', [CommunityController::class, 'publishToRayatForm'])->name('publishToRayatForm');
    // Route::post('/community/publish-to-rayat', [CommunityController::class, 'publishToRayat'])->name('publishToRayatStore');

    Route::get('/courses/per-level', [DepartmentBossController::class, 'index'])->name('coursesPerLevel');
});

// روأسا الأقسام
// Route::middleware(['auth', 'role:رئيس قسم'])->group(function () {
//departmentBoss
Route::get('/courses/per-level', [DepartmentBossController::class, 'index'])->name('coursesPerLevel');
Route::get('/api/courses', [DepartmentBossController::class, 'apiGetCourses'])->name('apiGetCourses');
Route::post('/api/courses/update-level', [DepartmentBossController::class, 'updateCoursesLevel'])->name('apiUpdateCoursesLevel');

Route::get('/student/courses', [falteringStudentsController::class, 'index'])->name('studentCourses');
Route::get('/api/student/{id}', [StudentController::class, 'getStudent'])->name('apiGetStudent');
Route::get('/api/major/{majorId}/courses', [CourseController::class, 'getCoursesByMajorId']);
Route::post('/api/student/add-courses', [StudentCoursesController::class, 'addCoursesToStudent'])->name('addCoursesToStudent');
Route::get('/api/program/{id}/majors/', [MajorController::class, 'getmajorsByProgramId']);
// FIXME: use path-parameter instead of header key-value pairs (for consistency)
Route::post('/api/student/delete-courses', [StudentCoursesController::class, 'deleteCourseFromStudent'])->name('apiDeleteCourseFromStudent');
// });

// المتدربين
// Route::middleware(['auth', 'role:متدرب', 'agreement'])->group(function () {
Route::middleware(['auth', 'agreement'])->group(function () {

    //StudentController - Edit student form
    //Route::get('/students',[StudentController::class,'index'])->name('ShowAllUsers');
    Route::get('/student/edit', [StudentController::class, 'edit'])->name('EditOneStudent');
    Route::post('/student/update', [StudentController::class, 'update'])->name('UpdateOneStudent');
    Route::get('/student/delete', [StudentController::class, 'destroy'])->name('DeleteOneStudent');
    Route::post('/student/level', [StudentController::class, 'getStudentOnLevel'])->name('getStudentOnLevel');
    Route::post('/student/update-state/', [StudentController::class, 'updateStudentState'])->name('updateStudentState');

    // Student wallet PaymentController
    Route::get('/student/wallet/main', [PaymentController::class, 'main'])->name('walletMain');
    Route::get('/student/wallet/payment/from', [PaymentController::class, 'form'])->name('paymentForm');
    Route::post('/student/wallet/payment/store', [PaymentController::class, 'store'])->name('paymentStore');

    // Student wallet PaymentController (json)
    // Well be move from CommunityController
    // Route::post('/community/student/payments/verified-update',[TransactionController::class,'paymentsReviewUpdate'])->name('paymentsReviewUpdate');
    // Route::post('/community/student/payments/verified-docs',[TransactionController::class,'paymentsReviewVerifiyDocs'])->name('paymentsReviewVerifiyDocs');

    // Student Courses Orders OrderController
    Route::get('/student/order/form', [OrderController::class, 'form'])->name('orderForm');
    Route::post('/student/order/store', [OrderController::class, 'store'])->name('orderStore');


    //UserControllaer New passwprd
    Route::get('/user/updatepassword', [UserController::class, 'UpdatePasswordForm'])->name('UpdatePasswordForm');
    Route::post('/user/updatepassword', [UserController::class, 'UpdatePassword'])->name('UpdatePassword');

    //refund
    Route::get('/student/refund_order', [RefundOrderController::class, 'form'])->name('refundOrderForm');
    Route::post('/student/refund_order', [RefundOrderController::class, 'store'])->name('refundOrder');
    
});

//الإرشاد
Route::middleware(['auth', 'role:الإرشاد'])->group(function () {
    Route::get('/privatestate', [PrivateStateController::class, 'privateDashboard'])->name('privateDashboard');
    Route::get('/privatestate/docs/review', [PrivateStateController::class, 'privateAllStudentsForm'])->name('PrivateAllStudentsForm');
    Route::post('/privatestate/docs/decision', [PrivateStateController::class, 'privateDocDecision'])->name('privateDocDecision');
    //Rayan ???????
    // Route::get('/community/students-states', [CommunityController::class, 'studentsStates'])->name('studentsStates');

});

//StudentController - Agreement
Route::get('/student/agreement', [StudentController::class, 'agreement_form'])->name('AgreementForm');
Route::post('/student/agreement', [StudentController::class, 'agreement_submit'])->name('AgreementSubmit');


// Route::get('/deptBoss/courses-data', [DepartmentBossController::class, 'getCoursesData'])->name('getCoursesData');
// Route::post('/deptBoss/courses/update-level', [DepartmentBossController::class, 'updateCoursesLevel'])->name('updateCoursesLevel');
