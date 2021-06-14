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
use App\Http\Controllers\GeneralManagementController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\StudentCoursesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentCheckerController;
use App\Http\Controllers\PrivateStateController;
use App\Http\Controllers\RefundOrderController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\TransactionController;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

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
    // Route::get('/home',function(){
    //     return redirect(route('home'));
    // });
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/deptBoss', [DepartmentBossController::class, 'dashboard'])->name('deptBossDashboard');

    // FIXME: this route is not used anywhere, shall we remove it?
    Route::get('/documents/{national_id}', [FileController::class, 'student_documents'])->name('GetStudentDocuments');
    Route::get('/documents/show/{path?}', [FileController::class, 'get_student_document'])
        ->where('path', '(.*)')
        ->name('GetStudentDocument');
    Route::get('api/documents/show/{national_id}/{filename}', [FileController::class, 'get_student_document_api'])
        ->name('GetStudentDocumentApi');
    Route::get('api/documents/show/{national_id}', [FileController::class, 'get_all_documents_api'])->name('GetAllDocumentsApi');

    // TODO: disable this in release
    //Logs Viewer
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});

//الإدارة العامة
Route::middleware(['auth', 'role:الإدارة العامة'])->group(function () {
    Route::get('/management', [GeneralManagementController::class, 'dashboard'])->name('managementDashboard');
    Route::get('/general/student/payments/review', [GeneralManagementController::class, 'generalPaymentsReviewForm'])->name('generalPaymentsReviewForm');
    Route::post('/general/student/payments/verified-docs', [GeneralManagementController::class, 'generalPaymentsReviewVerifiyDocs'])->name('generalPaymentsReviewVerifiyDocs');
    Route::get('/api/general/student/payments/{type}', [GeneralManagementController::class, 'generalPaymentsReviewJson'])->name('generalPaymentsReviewJson');
    Route::post('/general/student/payments/verified-update', [GeneralManagementController::class, 'generalPaymentsReviewUpdate'])->name('generalPaymentsReviewUpdate');
    Route::get('/general/student/payments/report', [GeneralManagementController::class, 'generalPaymentsReport'])->name('generalPaymentsReport');

});

Route::middleware(['auth', 'role:مدقق ايصالات'])->group(function () {
    Route::get('/payments-checker', [PaymentCheckerController::class, 'dashboard'])->name('paymentCheckerDashboard');
    Route::get('/payments-checker/student/payments/review', [PaymentCheckerController::class, 'checkerPaymentsReviewForm'])->name('checkerPaymentsReviewForm');
    Route::post('/payments-checker/student/payments/verified-docs', [PaymentCheckerController::class, 'checkerPaymentsReviewVerifiyDocs'])->name('checkerPaymentsReviewVerifiyDocs');
    Route::get('/api/payments-checker/student/payments/{type}', [PaymentCheckerController::class, 'checkerPaymentsReviewJson'])->name('checkerPaymentsReviewJson');
    Route::post('/payments-checker/student/payments/verified-update', [PaymentCheckerController::class, 'checkerPaymentsReviewUpdate'])->name('checkerPaymentsReviewUpdate');
    Route::get('/payments-checker/student/payments/report', [PaymentCheckerController::class, 'checkerPaymentsReport'])->name('checkerPaymentsReport');

});


// المدربين
Route::middleware(['auth', 'role:مدرب'])->group(function () {
    Route::get('/trainer', [TrainerController::class, 'dashboard'])->name('trainerDashboard');
    Route::get('/trainer/add-courses', [TrainerController::class, 'addCoursesToTrainerView'])->name('addCoursesToTrainerView');
    Route::post('/trainer/add-courses', [TrainerController::class, 'store'])->name('addCoursesToTrainerStore');
    Route::post('/api/trainer/check-division-number', [TrainerController::class, 'isDivisionAvailable'])->name('isDivisionAvailable');
});

// خدمة المجتمع
Route::middleware(['auth', 'role:خدمة المجتمع'])->group(function () {
    Route::get('/community', [CommunityController::class, 'dashboard'])->name('communityDashboard');
    // check payments
    Route::get('/community/student/payments/review', [CommunityController::class, 'paymentsReviewForm'])->name('paymentsReviewForm');
    Route::get('/community/student/payments/report', [CommunityController::class, 'paymentsReport'])->name('paymentsReport');
    Route::get('/api/community/student/payments/{type}', [CommunityController::class, 'paymentsReviewJson'])->name('paymentsReviewJson');
    Route::post('/community/student/payments/verified-update', [CommunityController::class, 'paymentsReviewUpdate'])->name('paymentsReviewUpdate');
    Route::post('/community/student/payments/verified-docs', [CommunityController::class, 'paymentsReviewVerifiyDocs'])->name('paymentsReviewVerifiyDocs');
    Route::get('/community/student/payments/final-report', [CommunityController::class, 'finalReviewReprot'])->name('finalReviewReprot');
    Route::get('/community/student/payments/final-review-report', [CommunityController::class, 'finalReviewReprotJson'])->name('finalReviewReprotJson');

    // recheck payments
    Route::get('/community/student/payments/recheck', [CommunityController::class, 'paymentsRecheckForm'])->name('paymentsRecheckForm');
    Route::get('/community/student/payments/recheck/report', [CommunityController::class, 'paymentsRecheckReport'])->name('paymentsReport');
    Route::get('/api/community/student/payments/recheck/{type}', [CommunityController::class, 'paymentsRecheckJson'])->name('paymentsRecheckJson');
    Route::post('/community/student/payments/recheck/verified-docs', [CommunityController::class, 'paymentsRecheckReject'])->name('paymentsRecheckReject');

    Route::get('/community/new-semester', [CommunityController::class, 'newSemesterForm'])->name('newSemesterForm');
    Route::post('/community/new-semester', [CommunityController::class, 'newSemester'])->name('newSemester');
    Route::post('/community/student/payments/edit', [CommunityController::class, 'editOldPayment'])->name('editOldPayment');
    Route::get('community/publish-to-rayat/{type}', [CommunityController::class, 'publishToRayatForm'])->name('publishToRayatFormCommunity');
    Route::get('api/community/publish-to-rayat/{type}', [CommunityController::class, 'publishToRayatJson'])->name('getStudentForRayatCommunityApi');
    Route::post('community/publish-to-rayat', [CommunityController::class, 'publishToRayat'])->name('publishToRayatStoreCommunity');
    /// I moved rayat report to department boos
    Route::get('/community/students-states', [CommunityController::class, 'studentsStates'])->name('studentsStates');
    Route::get('/community/old-students-report', [CommunityController::class, 'oldStudentsReport'])->name('oldStudentsReport');
    Route::get('api/community/students-report/{type}', [CommunityController::class, 'studentsReportJson'])->name('studentsReportCommunityJson');
    Route::get('/community/new-students-report/{type}', [CommunityController::class, 'newStudentsReport'])->name('newStudentsReport');
    // Users manage create,edit,delete
    Route::get('/community/users/manage', [CommunityController::class, 'manageUsersForm'])->name('manageUsersForm');
    Route::get('/community/users/create', [CommunityController::class, 'createUserForm'])->name('createUserForm');
    Route::post('/community/users/store', [CommunityController::class, 'createUserStore'])->name('createUserStore');
    Route::get('/community/users/edit/{user}', [CommunityController::class, 'editUserForm'])->name('editUserForm');
    Route::post('/community/users/update/{user}', [CommunityController::class, 'editUserUpdate'])->name('editUserUpdate');
    // Route::get('/community/users/delete/{user}', [CommunityController::class, 'deleteUser'])->name('deleteUser');
    // Manage permissions
    Route::post('/community/users/permissions/update/{user}', [CommunityController::class, 'editUserPermissionsUpdate'])->name('editUserPermissionsUpdate');
    Route::get('/community/users/permission/delete/{permission}', [CommunityController::class, 'deleteUserPermission'])->name('deleteUserPermission');

    // Students manage create,edit,delete
    Route::get('/community/students/manage', [CommunityController::class, 'manageStudentsForm'])->name('manageStudentsForm');
    Route::get('/community/students/get-student', [CommunityController::class, 'getStudentForm'])->name('getStudentForm');
    Route::get('/api/community/student-report/{id}', [CommunityController::class, 'getStudentForReport'])->name('apiCommunityStudentData');
    Route::get('/community/students/report/{user}', [CommunityController::class, 'studentReport'])->name('studentReport');
    Route::get('/community/students/show-order/{id}', [CommunityController::class, 'showOrder'])->name('showOrder');
    Route::get('/community/students/create', [CommunityController::class, 'createStudentForm'])->name('createStudentForm');
    Route::post('/community/students/store', [CommunityController::class, 'createStudentStore'])->name('createStudentStore');
    Route::get('/community/students/edit/', [CommunityController::class, 'editStudentForm'])->name('editStudentForm');
    Route::post('/community/students/update/{user}', [CommunityController::class, 'editStudentUpdate'])->name('editStudentUpdate');
    Route::get('/community/students/reset-password/{user}', [CommunityController::class, 'resetStusentPassword'])->name('resetStusentPassword');
    Route::get('/api/community/student-info/{id}', [CommunityController::class, 'getStudentById'])->name('GetStudentById');
    // Route::get('/community/students/delete/{user}', [CommunityController::class, 'deleteUser'])->name('deleteUser');
    
    //export
    Route::get('/excel/export/main-data',[ExcelController::class,'exportMainStudentData'])->name('exportMainStudentDataExcel');

    //Manage Courses
    Route::get('/community/courses', [CommunityController::class, 'coursesIndex'])->name('coursesIndex');
    Route::get('/community/courses/create', [CommunityController::class, 'createCourseForm'])->name('createCourseForm');
    Route::post('/community/courses/store', [CommunityController::class, 'createCourse'])->name('createCourse');
    ////// -------- I comment edit and delete becouse i'm using them in dept boss section
    // Route::get('/community/courses/edit/{course}', [CommunityController::class, 'editCourseForm'])->name('editCourseForm');
    Route::post('/community/courses/update', [CommunityController::class, 'editCourse'])->name('editCourse');
    // Route::get('/community/courses/delete/{course}', [CommunityController::class, 'deleteCourse'])->name('deleteCourse');

    //charge student wallet direct
    Route::get('/api/community/student/{id}', [CommunityController::class, 'getStudent'])->name('apiCommunityGetStudent');
    Route::get('/community/charge', [CommunityController::class, 'chargeForm'])->name('chargeForm');
    Route::post('/community/charge', [CommunityController::class, 'charge'])->name('charge');

    //Reports
    Route::get('/community/reports/all', [CommunityController::class, 'reportAllForm'])->name('reportAllForm');
    Route::get('/community/reports/filterd', [CommunityController::class, 'reportFilterdForm'])->name('reportFilterdForm');
    Route::post('/community/reports/filterd', [CommunityController::class, 'reportFilterd'])->name('reportFilterd');

    //orders
    Route::get('/api/community/student/orders/{student_id}', [CommunityController::class, 'getStudentOrders'])->name('getStudentOrders');
    Route::post('/api/community/order/edit', [CommunityController::class, 'editOrder'])->name('editOrder');

    //refunds
    Route::get('/community/refund-orders', [CommunityController::class, 'refundOrdersForm'])->name('refundOrdersForm');
    Route::get('/community/report/refund-orders', [CommunityController::class, 'refundOrdersReport'])->name('refundOrdersReport');
    Route::post('/api/community/refund-orders', [CommunityController::class, 'refundOrdersUpdate'])->name('apiRefundOrdersUpdate');

    //backup
    Route::get('/community/backup/download',[FileController::class, 'downloadBackup'])->name('downloadBackup');

    //manage semesters
    Route::get('/community/semester', [CommunityController::class, 'semesterDashboard'])->name('communitySemesterDashboard');
    Route::get('/community/new-semester', [CommunityController::class, 'newSemesterForm'])->name('newSemesterForm');
    Route::post('/community/new-semester', [CommunityController::class, 'newSemester'])->name('newSemester');
    Route::post('/community/toggle-allow-add-hours', [CommunityController::class, 'toggleAllowAddHours'])->name('toggleAllowAddHours');

});


// شؤون المتدربين
Route::middleware(['auth', 'role:شؤون المتدربين'])->group(function () {
    Route::get('/affairs/dashboard', [StudentAffairsController::class, 'dashboard'])->name('affairsDashboard');
    Route::get('/affairs/finalaccepted', [StudentAffairsController::class, 'finalAcceptedForm'])->name('finalAcceptedForm');
    Route::get('api/affairs/finalaccepted', [StudentAffairsController::class, 'finalAcceptedJson'])->name('finalAcceptedJson');
    Route::post('/affairs/finalaccepted/update', [StudentAffairsController::class, 'finalAcceptedUpdate'])->name('finalAcceptedUpdate');

    Route::get('/affairs/finalaccepted/report', [StudentAffairsController::class, 'finalAcceptedReport'])->name('finalAcceptedReport');
    Route::get('api/affairs/finalaccepted/report', [StudentAffairsController::class, 'finalAcceptedReportJson'])->name('finalAcceptedReportJson');

    Route::get('/affairs/checked', [StudentAffairsController::class, 'checkedStudents'])->name('CheckedStudents');
    // Route::get('/affairs/new', [StudentAffairsController::class, 'NewStudents'])->name('NewStudents');
    Route::get('/affairs/new-students-report/{type}', [CommunityController::class, 'newStudentsReport'])->name('NewStudents');
    Route::get('api/affairs/students-report/{type}', [CommunityController::class, 'studentsReportJson'])->name('studentsReportAffairsJson');

    //Rayat - thos routes is dupicate to use deffrent roles
    Route::get('affairs/publish-to-rayat/{type}', [CommunityController::class, 'publishToRayatForm'])->name('publishToRayatFormAffairs');
    Route::get('api/affairs/publish-to-rayat/{type}', [CommunityController::class, 'publishToRayatJson'])->name('getStudentForRayatAffairsApi');
    Route::post('affairs/publish-to-rayat', [CommunityController::class, 'publishToRayat'])->name('publishToRayatStoreAffairs');
    // ExcelController
    Route::get('/excel/new/add', [ExcelController::class, 'importNewForm'])->name('AddExcelForm');
    Route::post('/excel/new/import', [ExcelController::class, 'importNewUsers'])->name('importExcel');
    // Route::get('/excel/export/main-data',[ExcelController::class,'exportMainStudentData'])->name('exportMainStudentDataExcel');
    // Old users
    Route::get('/excel/old/add', [ExcelController::class, 'importOldForm'])->name('OldForm');
    Route::post('/excel/old/import', [ExcelController::class, 'importOldUsers'])->name('OldImport');
    //Update Students wallet
    Route::get('/excel/wallet/update', [ExcelController::class, 'updateStudentsWalletForm'])->name('UpdateStudentsWalletForm');
    Route::post('/excel/wallet/update', [ExcelController::class, 'updateStudentsWalletStore'])->name('UpdateStudentsWalletStore');

    //Add rayat_id to new students
    Route::get('/excel/rayat/update', [ExcelController::class, 'addRayatIdForm'])->name('addRayatIdForm');
    Route::post('/excel/rayat/update', [ExcelController::class, 'addRayatIdStore'])->name('addRayatIdStore');

    Route::get('/affairs/rayat-report/{type}', [CommunityController::class, 'rayatReportForm'])->name('rayatReportFormAffairs');
    Route::get('api/affairs/rayat-report/{type}', [CommunityController::class, 'rayatReportApi'])->name('rayatReportAffairsApi');
    Route::get('/courses/per-level', [DepartmentBossController::class, 'index'])->name('coursesPerLevel');
});

// روأسا الأقسام
Route::middleware(['auth'])->group(function () {
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


    //Manage Courses
    Route::get('/department-boss/courses', [DepartmentBossController::class, 'coursesIndex'])->name('deptCoursesIndex');
    Route::get('/department-boss/courses/create', [DepartmentBossController::class, 'createCourseForm'])->name('deptCreateCourseForm');
    Route::post('/department-boss/courses/store', [DepartmentBossController::class, 'createCourse'])->name('deptCreateCourse');
    Route::get('/department-boss/courses/edit/{course}', [CommunityController::class, 'editCourseForm'])->name('editCourseForm');
    Route::post('/department-boss/courses/update', [DepartmentBossController::class, 'editCourse'])->name('deptEditCourse');
    Route::get('/department-boss/courses/delete/{course}', [CommunityController::class, 'deleteCourse'])->name('deleteCourse');

    // Users manage create,edit,delete
    Route::get('/department-boss/students/manage', [CommunityController::class, 'manageStudentsForm'])->name('manageStudentsForm');
    Route::get('/department-boss/students/create', [DepartmentBossController::class, 'createStudentForm'])->name('deptCreateStudentForm');
    Route::post('/department-boss/students/store', [DepartmentBossController::class, 'createStudentStore'])->name('deptCreateStudentStore');
    // Route::get('/department-boss/students/edit/', [CommunityController::class, 'editStudentForm'])->name('editStudentForm');
    // Route::post('/department-boss/students/update/{user}', [CommunityController::class, 'editStudentUpdate'])->name('editStudentUpdate');
    // Route::get('/api/department-boss/student-info/{id}', [CommunityController::class, 'getStudentById'])->name('GetStudentById');
    // Route::get('/community/students/delete/{user}', [CommunityController::class, 'deleteUser'])->name('deleteUser');


    /// rayat
    Route::get('/community/rayat-report/{type}', [CommunityController::class, 'rayatReportForm'])->name('rayatReportFormCommunity');
    Route::get('api/community/rayat-report/{type}', [CommunityController::class, 'rayatReportApi'])->name('rayatReportCommunityApi');


    /// review trainers orders 
    Route::get('/department-boss/trainers-info', [DepartmentBossController::class, 'trainersInfoView'])->name('trainersInfoView');
    Route::get('/api/department-boss/get-courses/{trainer}', [DepartmentBossController::class, 'getCoursesByTrainer'])->name('getCoursesByTrainer');
    Route::post('/api/department-boss/accept', [DepartmentBossController::class, 'acceptTrainerCourseOrder'])->name('acceptTrainerCourseOrder');

});

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
    Route::post('/student/payment/delete', [PaymentController::class, 'deletePayment'])->name('deletePayment');

    // Student wallet PaymentController (json)
    // Well be move from CommunityController
    // Route::post('/community/student/payments/verified-update',[TransactionController::class,'paymentsReviewUpdate'])->name('paymentsReviewUpdate');
    // Route::post('/community/student/payments/verified-docs',[TransactionController::class,'paymentsReviewVerifiyDocs'])->name('paymentsReviewVerifiyDocs');

    // Student Courses Orders OrderController
    Route::get('/student/order/form', [OrderController::class, 'form'])->name('orderForm');
    Route::post('/student/order/store', [OrderController::class, 'store'])->name('orderStore');
    Route::post('/student/order/delete', [OrderController::class, 'deleteOrder'])->name('deleteOrder');


    // //UserControllaer New passwprd
    // Route::get('/user/updatepassword', [UserController::class, 'UpdatePasswordForm'])->name('UpdatePasswordForm');
    // Route::post('/user/updatepassword', [UserController::class, 'UpdatePassword'])->name('UpdatePassword');

    //refund
    Route::get('/student/refund_order', [RefundOrderController::class, 'form'])->name('refundOrderForm');
    Route::post('/student/refund_order', [RefundOrderController::class, 'store'])->name('refundOrder');
});

//الإرشاد
Route::middleware(['auth', 'role:الإرشاد'])->group(function () {
    Route::get('/privatestate', [PrivateStateController::class, 'privateDashboard'])->name('privateDashboard');
    Route::get('/privatestate/docs/review', [PrivateStateController::class, 'privateAllStudentsForm'])->name('PrivateAllStudentsForm');
    Route::post('/privatestate/docs/decision', [PrivateStateController::class, 'privateDocDecision'])->name('privateDocDecision');
    Route::get('/privatestate/docs/report', [PrivateStateController::class, 'privateStudentsReport'])->name('PrivateStudentsReport');

    //Rayan ???????
    // Route::get('/community/students-states', [CommunityController::class, 'studentsStates'])->name('studentsStates');

});

//StudentController - Agreement
Route::get('/student/agreement', [StudentController::class, 'agreement_form'])->name('AgreementForm');
Route::post('/student/agreement', [StudentController::class, 'agreement_submit'])->name('AgreementSubmit');


// Route::get('/deptBoss/courses-data', [DepartmentBossController::class, 'getCoursesData'])->name('getCoursesData');
// Route::post('/deptBoss/courses/update-level', [DepartmentBossController::class, 'updateCoursesLevel'])->name('updateCoursesLevel');
