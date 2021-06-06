<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Major;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Permission;
use App\Models\Program;
use App\Models\RefundOrder;
use App\Models\Role;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CommunityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $title = "خدمة المجتمع";
        $links = [
            (object) [
                "name" => "تدقيق الايصالات",
                "url" => route("paymentsReviewForm")
            ],
            (object) [
                "name" => "تقرير طلبات الشحن",
                "url" => route("paymentsReport")
            ],
            // (object) [
            //     "name" => "ادارة محفظة المتدرب",
            //     "url" => route("chargeForm")
            // ],

            (object) [
                "name" => "الرفع لرايات",
                "url" => route('publishToRayatFormCommunity', ["type" => "community"])
            ],

            (object) [
                "name" => "تقرير رايات",
                "url" => route("rayatReportFormCommunity", ["type" => "community"])
            ],
            (object) [
                "name" => "جميع المتدربين المستمرين",
                "url" => route("oldStudentsReport")
            ],
            (object) [
                "name" => "جميع المتدربين المستجدين",
                "url" => route("newStudentsReport", ["type" => "community"])
            ],
            (object) [
                "name" => "ادارة المستخدمين",
                "url" => route("manageUsersForm")
            ],
            (object) [
                "name" => "ادارة المتدربين",
                "url" => route("manageStudentsForm")
            ],
            // (object) [
            //     "name" => "اضافة متدرب",
            //     "url" => route("createStudentForm")
            // ],
            // (object) [
            //     "name" => "تعديل بيانات متدرب",
            //     "url" => route("editStudentForm")
            // ],

            (object) [
                "name" => "ادارة المقررات",
                "url" => route("coursesIndex")
            ],
            (object) [
                "name" => "ادارة الفصل الدراسي",
                "url" => route("communitySemesterDashboard")
            ],
            (object) [
                "name" => "جميع العمليات المالية",
                "url" => route("reportAllForm")
            ],
            (object) [
                "name" => "العمليات المالية حسب التخصص",
                "url" => route("reportFilterdForm")
            ],
            (object) [
                "name" => "طلبات الاسترداد",
                "url" => route("refundOrdersForm")
            ],
            (object) [
                "name" => "تقرير طلبات الاسترداد",
                "url" => route("refundOrdersReport")
            ],
            // (object) [
            //     "name" => "المتدربين المدققة ايصالاتهم",
            //     "url" => route("CheckedStudents")
            // ],
            // (object) [
            //     "name" => "انشاء مستخدم",
            //     "url" => route("createUserForm")
            // ],

            // (object) [
            //     "name" => "متابعة حالات المتدربين",
            //     "url" => route("studentsStates")
            // ],





        ];
        return view("manager.community.dashboard")->with(compact("links", "title"));
    }



    public function manageStudentsForm()
    {
        try {
            return view("manager.community.students.manage");
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with('error', ' حدث خطأ غير معروف ' . $e->getCode());
        }
    }


    public function getStudentForm()
    {
        try {
            if (Auth::user()->hasRole("خدمة المجتمع")) {
                return view('manager.community.students.getStudentForm');
            } else {
                return view('error')->with("error", "ليس لديك صلاحيات لتنفيذ هذا الامر");
            }
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return view('error')->with("error", "تعذر ارسال الطلب حدث خطا غير معروف");
        }
    }
    public function studentReport(User $user)
    {
        try {
            if (Auth::user()->hasRole("خدمة المجتمع")) {
                if ($user->student == null) {
                    return view('error')->with("error", "حدث خطأ غير معروف");
                }
                return view('manager.community.students.report')->with(compact('user'));
            } else {
                return view('error')->with("error", "ليس لديك صلاحيات لتنفيذ هذا الامر");
            }
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return view('error')->with("error", "تعذر ارسال الطلب حدث خطا غير معروف");
        }
    }

    public function showOrder($orderId)
    {
        try {
            if (Auth::user()->hasRole("خدمة المجتمع")) {
                $order = Order::where("id", $orderId)->first();
                if ($order == null) {
                    return back()->with("error", "لا يوجد طلب بهذا الرقم");
                }
                $user = $order->student->user;
                return view('manager.community.students.showOrder')->with(compact('user', 'order'));
            } else {
                return view('error')->with("error", "ليس لديك صلاحيات لتنفيذ هذا الامر");
            }
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return view('error')->with("error", "حدث خطا غير معروف");
        }
    }

    public function createStudentForm()
    {
        $programs = json_encode(Program::with("departments.majors.courses")->get());
        return view("manager.community.students.create")->with(compact('programs'));
    }




    public function createStudentStore(Request $request)
    {
        $requestData = $this->validate($request, [
            'national_id' => 'required|digits:10|unique:users,national_id',
            "rayat_id"    => 'nullable|digits_between:9,10|unique:students,rayat_id',
            'name'     => 'required|string|min:3|max:100',
            "phone"        => 'required|digits_between:9,14|unique:users,phone',
            "major"         => "required|numeric|exists:majors,id",
            "level"         => "required|numeric|min:1|max:5",
        ]);

        try {
            if (Auth::user()->hasRole("خدمة المجتمع")) {

                $password = Hash::make("bct12345");
                $major = Major::find($requestData['major']) ?? null;
                $prog_id = $major->department->program->id;
                $dept_id = $major->department->id;
                if ($major == null) {
                    return back()->with("error", "لا يوجد قسم حسب المعلومات المرسله");
                }
                DB::beginTransaction();
                User::create([
                    "national_id" => $requestData['national_id'],
                    "name" => $requestData['name'],
                    "phone" => $requestData['phone'],
                    "password" => $password
                ])->student()->create([
                    "rayat_id" => $requestData['rayat_id'],
                    "program_id" => $prog_id,
                    "department_id" => $dept_id,
                    "has_imported_docs" => false,
                    "major_id" => $requestData['major'],
                    "level"    => $requestData['level'],
                ]);
                DB::commit();
                return redirect(route("createStudentForm"))->with('success', 'تم اضافة المتدرب بنجاح');
            } else {
                return back()->with("error", "ليس لديك صلاحيات لتنفيذ هذا الامر");
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with('error', ' حدث خطأ غير معروف ' . $e->getCode());
        }
    }


    public function editStudentForm()
    {
        try {
            if (Auth::user()->hasRole("خدمة المجتمع")) {
                $programs = json_encode(Program::with("departments.majors.courses")->get());
                return view("manager.community.students.edit")->with(compact('programs'));
            } else {
                return back()->with("error", "ليس لديك صلاحيات لتنفيذ هذا الامر");
            }
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with("error", "تعذر ارسال الطلب حدث خطا غير معروف");
        }
    }


    public function resetStusentPassword(User $user)
    {
        try {
            $user->password =  Hash::make("bct12345");
            $user->save();
            return back()->with('success', 'تم اعادة تعيين كلمة المرور بنجاح');
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with('error', 'حدث خطأ غير معروف');
        }
    }

    public function editStudentUpdate(Request $request, User $user)
    {
        $requestData = $this->validate($request, [
            'national_id' => 'required|digits:10|unique:users,national_id,' . $user->id,
            "rayat_id"    => 'required|digits_between:9,10|unique:students,rayat_id,' . $user->student->id,
            'name'     => 'required|string|min:3|max:100',
            "phone"        => 'required|digits_between:9,14|unique:users,phone,' . $user->id,
            "major"         => "required|numeric|exists:majors,id",
            "level"         => "required|numeric|min:1|max:5",
        ]);

        try {
            if (Auth::user()->hasRole("خدمة المجتمع")) {

                $major = Major::find($requestData['major']) ?? null;
                $prog_id = $major->department->program->id;
                $dept_id = $major->department->id;
                if ($major == null) {
                    return back()->with("error", "لا يوجد قسم حسب المعلومات المرسله");
                }
                DB::beginTransaction();
                $user->update([
                    "national_id" => $requestData['national_id'],
                    "name" => $requestData['name'],
                    "phone" => $requestData['phone'],
                ]);
                $user->student()->update([
                    "rayat_id" => $requestData['rayat_id'],
                    "program_id" => $prog_id,
                    "department_id" => $dept_id,
                    "has_imported_docs" => true,
                    "final_accepted"    => true,
                    "major_id" => $requestData['major'],
                    "level"    => $requestData['level'],
                ]);
                DB::commit();
                return redirect(route("editStudentForm"))->with('success', 'تم تحديث بيانات المتدرب بنجاح');
            } else {
                return back()->with("error", "ليس لديك صلاحيات لتنفيذ هذا الامر");
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with('error', ' حدث خطأ غير معروف ' . $e->getCode());
        }
    }

    public function getStudentById($id)
    {
        try {
            if (Auth::user()->hasRole("خدمة المجتمع")) {
                $user = User::with("student")->whereHas('student', function ($res) use ($id) {
                    $res->where('national_id', $id)
                        ->orWhere('rayat_id', $id);
                })->first() ?? null;
                if ($user == null) {
                    return response()->json(["message" => "لا يوجد متدرب بهذا الرقم"], 422);
                }
                return response()->json($user, 200);
            } else {
                return back()->with("error", "ليس لديك صلاحيات لتنفيذ هذا الامر");
            }
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return response()->json(["message" => "خطأ غير معروف"]);
        }
    }


    public function manageUsersForm()
    {
        try {
            $users = User::with("manager.permissions")->whereHas("manager")->where("id", ">", 1)->get();
            return view("manager.community.users.manage")->with(compact('users'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with('error', ' حدث خطأ غير معروف ' . $e->getCode());
        }
    }

    public function createUserForm()
    {
        return view("manager.community.users.create");
    }


    public function createUserStore(Request $request)
    {
        $requestData = $this->validate($request, [
            'national_id' => 'required|digits:10|unique:users,national_id',
            'name'     => 'required|string|min:3|max:100',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $requestData['password'] = Hash::make($requestData['password']);
        try {
            if (Auth::user()->hasRole("خدمة المجتمع")) {
                User::create($requestData)->manager()->create();

                return redirect(route("manageUsersForm"))->with('success', 'تم انشاء المستخدم بنجاح');
            } else {
                return back()->with("error", "ليس لديك صلاحيات لتنفيذ هذا الامر");
            }
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with('error', ' حدث خطأ غير معروف ' . $e->getCode());
        }
    }


    public function editUserForm(User $user)
    {
        try {
            if (Auth::user()->hasRole("خدمة المجتمع")) {
                $permissions = $user->manager->permissions->pluck('role_id');
                $roles = Role::whereNotin('id', $permissions)->get();
                return view("manager.community.users.edit")->with(compact('roles', 'user'));
            } else {
                return back()->with("error", "ليس لديك صلاحيات لتنفيذ هذا الامر");
            }
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with("error", "تعذر ارسال الطلب حدث خطا غير معروف");
        }
    }


    public function editUserUpdate(Request $request, User $user)
    {
        $requestData = $this->validate($request, [
            'national_id' => 'required|digits:10|exists:users,national_id',
            'name'     => 'required|string|min:3|max:100',
            // 'password' => 'required|string|min:8|confirmed',
        ]);
        // $requestData['password'] = Hash::make($requestData['password']);
        try {
            if (Auth::user()->hasRole("خدمة المجتمع")) {
                $user->update($requestData);
                return redirect(route("manageUsersForm"))->with('success', 'تم تحديث بيانات المستخدم بنجاح');
            } else {
                return back()->with("error", "ليس لديك صلاحيات لتنفيذ هذا الامر");
            }
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with('error', ' حدث خطأ غير معروف ' . $e->getCode());
        }
    }






    public function editUserPermissionsUpdate(Request $request, User $user)
    {

        $requestData = $this->validate($request, [
            "roles"      => "required|array|min:1",
            "roles.*"    => "required|numeric|distinct|exists:roles,id",
        ]);
        try {
            if (Auth::user()->hasRole("خدمة المجتمع")) {
                foreach ($requestData['roles'] as $role_id) {
                    $user->manager->permissions()->create(array('role_id' => $role_id));
                }
                return redirect(route("manageUsersForm"))->with("success", "تم تعديل الصلاحيات بنجاح");
            } else {
                return back()->with("error", "ليس لديك صلاحيات لتنفيذ هذا الامر");
            }
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            DB::rollBack();
            return back()->with("error", "تعذر ارسال الطلب حدث خطا غير معروف");
        }
    }

    public function deleteUser(User $user)
    {
        try {
            // $user->delete();
            // return back()->with('success', 'تم حذف المستخدم بنجاح');
            return back()->with('error', 'تم ايقاف هذا الامر ');
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with('error', ' حدث خطأ غير معروف ' . $e->getCode());
        }
    }

    public function deleteUserPermission(Permission $permission)
    {
        try {
            $permission->delete();
            return back()->with('success', 'تم ازالة الصلاحية بنجاح');
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with('error', ' حدث خطأ غير معروف ' . $e->getCode());
        }
    }

    public function paymentsReviewForm()
    {
        return view('manager.community.paymentsReview');
    }

    public function paymentsReport()
    {
        return view('manager.community.paymentsReport');
    }

    public function paymentsReviewJson($type)
    {
        // $fetch_errors = [];
        try {
            $cond = "=";
            if ($type == 'report') {
                $cond = "!=";
            }
            $payments = Payment::with(["student.user", "student.program", "student.department", "student.major","transactions"])->where("accepted", $cond, null)->get();
            return response()->json(["data" => $payments->toArray()], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return view('manager.community.paymentsReview')->with('error', "تعذر جلب المتدربين");
        }
    }

    public function paymentsReviewUpdate(Request $request)
    {
        $reviewedPayment = $this->validate($request, [
            "national_id"        => "required|numeric",
            "payment_id"         => "required|numeric|exists:payments,id",
            "amount"             => "required|numeric",
            "decision"           => "required|in:accept,reject",
            "note"               => "string|nullable"
        ]);

        try {
            $semester = Semester::latest()->first();
            $decision = false;
            if ($reviewedPayment["decision"] == "accept") {
                $decision = true;
            }
            $user = User::with('student')->where('national_id', $reviewedPayment['national_id'])->first();
            $payment = Payment::where("id", $reviewedPayment["payment_id"])->first() ?? null;
            if ($payment == null) {
                return response(json_encode(['message' => 'خطأ غير معروف']), 422);
            } else if ($payment->accepted !== null) {
                return response(['message' => "تمت معالجة هذا الطلب من قبل"], 422);
            }
            DB::beginTransaction();

            if ($decision == true) {
                $transaction = $user->student->transactions()->create([
                    "payment_id"    => $payment->id,
                    "amount"        => $reviewedPayment["amount"],
                    "note"          => $reviewedPayment["note"],
                    "type"          => "recharge",
                    "manager_id"    => Auth::user()->manager->id,
                    "semester_id"   => $semester->id,

                ]);
                $payment->update([
                    "transaction_id" => $transaction->id,
                    "note"          => $reviewedPayment["note"],
                    "accepted"       => true,

                ]);

                $user->student->wallet += $reviewedPayment["amount"];
                $user->student->save();
            } else {
                $payment->update([
                    "note"          => $reviewedPayment["note"],
                    "accepted"       => false,

                ]);
            }


            DB::commit();
            return response(json_encode(['message' => 'تم ارسال الطلب بنجاح']), 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            DB::rollBack();
            return response(json_encode(['message' => 'حدث خطأ غير معروف' . $e->getCode()]), 422);
        }
    }

    public function editOldPayment(Request $request){
        $semester = Semester::latest()->first();
        $reviewedPayment = $this->validate($request, [
            "payment_id"         => "required|numeric|exists:payments,id",
            "amount"             => "required|numeric",
            "note"               => "string|nullable"
        ]);
        if(Auth::user()->id != 1){
            return response(json_encode(['message' => "ليس لديك صلاحيات لتنفيذ هذا الامر"]), 422);
        }elseif($reviewedPayment["amount"] < 0){
            return response(json_encode(['message' => 'يجب ان يكون المبلغ المدخل اكبر من صفر']), 422);
        }
        try {
            $payment = Payment::where("id", $reviewedPayment["payment_id"])->first() ?? null;
            $acceptedAmount = 0;
            foreach ($payment->transactions as  $transaction) {
                if($transaction->type == 'editPayment-charge' || $transaction->type == 'recharge' || $transaction->type == 'manager_recharge'){
                    $acceptedAmount += $transaction->amount;
                }else{
                    $acceptedAmount -= $transaction->amount;
                }
            }
             
            if($reviewedPayment["amount"] == $acceptedAmount){
                return response(json_encode(['message' => 'يجب ان يكون المبلغ المدخل غير مطابق للمبلغ السابق']), 422);
            }

            DB::beginTransaction();
                if($acceptedAmount > $reviewedPayment["amount"]){
                    $diff = $acceptedAmount - $reviewedPayment["amount"];
                    $payment->student->wallet -= $diff ;
                    $type = 'editPayment-deduction';
                }else{
                    $diff = $reviewedPayment["amount"] - $acceptedAmount;
                    $payment->student->wallet += $diff;
                    $type = 'editPayment-charge';
                }
                $payment->student->save();
                $transaction = $payment->student->transactions()->create([
                    "payment_id"    => $payment->id,
                    "amount"        => $diff,
                    "type"          => $type,
                    "manager_id"    => Auth::user()->manager->id,
                    "semester_id"   => $semester->id,
                    "note"          => $reviewedPayment["note"] ?? null,
                ]);
                $payment->update([
                    "transaction_id"    => $transaction->id,
                ]);
            DB::commit();
            return response(json_encode(['message' => 'تم تعديل المبلغ بنجاح']), 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            DB::rollBack();
            return response(json_encode(['message' => 'حدث خطأ غير معروف ' . $e->getCode()]), 422);
        }
    }

    public function paymentsReviewVerifiyDocs(Request $request)
    {
        $semester = Semester::latest()->first();
        $reviewedPayment = $this->validate($request, [
            "national_id"        => "required|numeric",
            "payment_id"         => "required|numeric|exists:payments,id",
            "decision"           => "required|in:accept,reject",

        ]);

        try {
            $decision = false;
            if ($reviewedPayment["decision"] == "accept") {
                $decision = true;
            }

            DB::beginTransaction();

            $user = User::with('student')->where('national_id', $reviewedPayment['national_id'])->first();
            $payment = Payment::where("id", $reviewedPayment["payment_id"])->first();

            if ($decision == true) {
                $transaction = $user->student->transactions()->create([
                    "payment_id"    => $payment->id,
                    "amount"    => $payment->amount,
                    "type"    => "recharge",
                    "manager_id"    => Auth::user()->manager->id,
                    "semester_id"        => $semester->id,

                ]);
                $payment->update([
                    "transaction_id" => $transaction->id,
                    "accepted"       => $decision,

                ]);
                $user->student->wallet += $payment->amount;
                $user->student->save();
            } else {
                $payment->update([
                    "accepted"       => false,

                ]);
            }

            DB::commit();
            return response(json_encode(['message' => 'تم ارسال الطلب بنجاح']), 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            DB::rollBack();
            return response(json_encode(['message' => 'حدث خطأ غير معروف' . $e->getCode()]), 422);
        }
    }

    public function semesterDashBoard()
    {
        try {
            $semester = Semester::latest()->first();
            return view("manager.community.semesters.manage")->with(compact('semester'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with('error', 'حدث خطأ غير معروف');
        }
    }


    public function newSemesterForm()
    {
        return view("manager.community.newSemester");
    }



    public function newSemester(Request $request)
    {

        $requestData = $this->validate($request, [
            "national_id"        => "required|digits:10",
            'password' => 'required|string|min:8',
            'start_date' => 'required|date_format:Y-m-d|after:yesterday',
            'end_date' => 'required|date_format:Y-m-d|after:today',
            'isSummerSemester' => "boolean"

        ]);
        try {
            $user = Auth::user();
            if (!$user->hasRole("خدمة المجتمع")) {
                return back()->with("error", "ليس لديك صلاحيات لتنفيذ هذا الامر");
            }

            if (!Hash::check($requestData["password"], Auth::user()->password) || $requestData["national_id"] != Auth::user()->national_id) {
                return  back()->with('error', 'اسم المستخدم او كلمة المرور غير صحيحة');
            }

            DB::beginTransaction();
            Semester::create([
                "start_date" => $requestData["start_date"],
                "end_date" => $requestData["end_date"],
                "isSummer"  => $requestData["isSummerSemester"],
            ]);

            DB::table('students')
                ->update([
                    'credit_hours' => 0,
                ]);
            if (isset($requestData['isSummerSemester']) &&  $requestData['isSummerSemester'] == false) {
                DB::table('students')
                    ->where('level', "<", 5)
                    ->update([
                        'level' => DB::raw('level + 1'),
                    ]);
            }


            DB::commit();
            return redirect(route("communityDashboard"))->with("success", "تم معالجة الطلب بنجاح");
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            DB::rollBack();
            return back()->with('error', 'حدث خطأ غير معروف');
        }
    }

    public function toggleAllowAddHours(Request $request)
    {
        try {
            $semester = Semester::latest()->first();
            if($semester->can_request_hours){
                $semester->can_request_hours = false;
                $msg = ' ايقاف اضافة المقررات';
            }else{
                $semester->can_request_hours = true;
                $msg = ' اتاحة اضافة المقررات';
            }
            $semester->save();
            return back()->with(["success" => "تم".$msg]);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with(['error' => "تعذر".$msg]);
        }
    }


    public function publishToRayatForm($type)
    {
        if (isset($type)) {
            if ($type == "affairs") {
                $cond = "=";
            } else if ($type == "community") {
                $cond = ">";
            }
        }
        return view('manager.community.publishHoursToRayat')
            ->with(compact('type'));
    }
    public function publishToRayatJson($type)
    {
        if (isset($type)) {
            if ($type == "affairs") {
                $cond = "=";
            } else if ($type == "community") {
                $cond = ">";
            }
        }
        try {
            $orders = Order::with(["student", "student.user", "student.program", "student.department", "student.major", "student.payments"])
                ->where("transaction_id", null)
                ->where("private_doc_verified", true)
                ->whereDoesntHave("student.payments", function ($res) {
                    $res->where("accepted", null);
                })->whereHas("student", function ($res) use ($cond, $type) {
                    $res->where("level", $cond, 1);
                    if ($type == "affairs") {
                        $res->where("final_accepted", true);
                    }
                })->get();
            $countOfOrders =  count($orders);
            for ($i = 0; $i < $countOfOrders; $i++) {
                switch ($orders[$i]->student->traineeState) {
                    case 'privateState':
                        $discount = 0; // = %100 discount
                        break;
                    case 'employee':
                        $discount = 0.25; // = %75 discount
                        break;
                    case 'employeeSon':
                        $discount = 0.5; // = %50 discount
                        break;
                    default:
                        $discount = 1; // = %0 discount
                }
                // FIXME: rewrite me
                if ($orders[$i]->student->wallet > 0 && $orders[$i]->student->traineeState != 'privateState') {
                    $canAddHours = floor($orders[$i]->student->wallet / ($orders[$i]->student->program->hourPrice * $discount));
                } elseif ($orders[$i]->student->traineeState != 'privateState') {
                    unset($orders[$i]);
                    continue;
                }

                if ($orders[$i]->student->traineeState != 'privateState') {
                    if ($canAddHours == 0) {
                        unset($orders[$i]);
                        continue;
                    } elseif ($canAddHours < $orders[$i]->requested_hours) {
                        $orders[$i]->requested_hours = $canAddHours;
                    }
                } else {
                    $canAddHours = 'لا يوجد';
                }
                $orders[$i]->canAddHours = $canAddHours;
            }
            return response()->json(["data" => $orders->values()], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return view('manager.community.paymentsReview')->with('error', "تعذر جلب المتدربين");
        }
        /*
        $users = User::with("student")
            ->whereHas("student", function ($res) use ($cond) {
                $res->where('final_accepted', true)
                    ->where('level', $cond, '1')
                    ->whereHas("orders", function ($res) {
                        $res->where("transaction_id", null)
                            ->where("private_doc_verified", true);
                    })
                    ->whereDoesntHave("payments", function ($res) {
                        $res->where("accepted", null);
                    });
            })->get();
            */
    }
    public function publishToRayat(Request $request)
    {

        $semester = Semester::latest()->first();
        $requestData = $this->validate($request, [
            "national_id"        => "required|digits:10",
            "requested_hours"    => "required|numeric|min:0",
            "order_id"         => "required|numeric|exists:orders,id",
        ]);

        try {
            $user = User::with('student.orders')->where('national_id', $requestData['national_id'])
                ->whereHas("student.orders", function ($res) use ($requestData) {
                    $res->where("id", $requestData['order_id']);
                })->get()[0];

            if ($user === null) {
                return response(['message' => "خطأ في بيانات المتدرب"], 422);
            }

            $order = Order::where("id", $requestData['order_id'])->first();
            if ($order === null) {
                return response(['message' => "خطأ في بيانات الطلب"], 422);
            } else if ($order->transaction_id !== null) {
                return response(['message' => "تم قبول هذا الطلب من قبل"], 422);
            }

            switch ($user->student->traineeState) {
                case 'privateState':
                    $discount = 0; // = %100 discount
                    break;
                case 'employee':
                    $discount = 0.25; // = %75 discount
                    break;
                case 'employeeSon':
                    $discount = 0.5; // = %50 discount
                    break;
                default:
                    $discount = 1; // = %0 discount
            }
            $origAmount = $requestData['requested_hours'] * $user->student->program->hourPrice;
            $amount = $origAmount * $discount;
            $discountAmount = $origAmount - $amount;

            $canAddHours = $requestData['requested_hours'];
            $note = null;

            if ($user->student->traineeState != 'privateState') {
                if ($amount >= $user->student->wallet) {
                    $canAddHours = floor($user->student->wallet / ($user->student->program->hourPrice * $discount));
                }
            }

            if ($requestData['requested_hours'] > $canAddHours) {
                return response(['message' => "عدد الساعات اكبر من الحد الاعلى"], 422);
            }

            // if ($requestData['requested_hours'] > $order->requested_hours) {
            //     return response(['message' => "عدد الساعات اكبر من الحد الاعلى"], 422);
            // }

            if ($requestData['requested_hours'] < $order->requested_hours) {
                $note = " تم تغيير عدد الساعات من " .
                    $order->requested_hours .
                    " الى " . $requestData['requested_hours'] .
                    " لعدم امكانية اضافتها الى رايات او عدم كفاية الرصيد ";
            }
            DB::beginTransaction();
            $transaction = $user->student->transactions()->create([
                "order_id"    => $order->id,
                "amount"        => $amount,
                "type"          => "deduction",
                "manager_id"       => Auth::user()->manager->id,
                "semester_id"   => $semester->id,

            ]);

            $order->update([
                "amount" => $amount,
                "discount" => $discountAmount,
                "requested_hours" => $requestData['requested_hours'],
                "note"          => $note,
                "transaction_id" => $transaction->id,
            ]);

            $user->student->wallet -= $amount;
            $user->student->credit_hours += $requestData['requested_hours'];
            $user->student->save();
            DB::commit();
            return response(['message' => 'تم قبول الطلب بنجاح'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage() . ' ' . $e);
            return response(['message' => 'حدث خطأ غير معروف' . $e->getCode()], 422);
        }
    }

    public function rayatReportForm($type)
    {
        return view('manager.community.rayatReport')
            ->with(compact('type'));
    }

    public function rayatReportApi($type)
    {
        try {
            if (isset($type)) {
                if ($type == "affairs") {
                    $cond = "=";
                } else if ($type == "community") {
                    $cond = ">";
                } else if ($type == "departmentBoss") {
                    $cond = ">=";
                }
            }
            $users = User::with(['student.program', 'student.department', 'student.major'])->whereHas('student', function ($result) use ($cond, $type) {
                $result->where('level', $cond, '1')
                    ->where('credit_hours', '>', 0);

                $user = Auth::user();
                if ($type == 'departmentBoss' && $user->manager->isDepartmentManager()) {
                    $result->whereHas('department', function ($res) use ($user) {
                        $res->whereIn('role_id', $user->manager->getPermissionRoleIds());
                    });
                }
            })->get();

            return response()->json(["data" => $users->toArray()], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return view('manager.community.rayatReport');
        }
    }

    public function getStudentOrders($student_id)
    {
        try {
            $orders = Student::find($student_id)->orders()->with('transaction', 'student.program')->get();
            return response()->json($orders, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return response()->json(["message" => $e], 422);        
        }
    }




    public function oldStudentsReport()
    {
        return view('manager.community.oldStudentsReport');
    }

    public function studentsReportJson($type)
    {
        try {
            if (isset($type)) {
                if ($type == "new") {
                    $cond = "=";
                } else if ($type == "old") {
                    $cond = ">";
                }
            }
            $users = User::with(['student.program', 'student.department', 'student.major'])->whereHas('student', function ($result) use ($cond) {
                $result->where('level', $cond, '1');
            })->get();
            return response()->json(["data" => $users->toArray()], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return redirect(route('home'))->with('error', 'تعذر جلب المتدربين حدث خطأ غير معروف');
        }
    }

    public function newStudentsReport($type)
    {
        return view('manager.community.newStudentsReport')->with(compact('type'));
    }


    public function studentsStates()
    {
        $users = User::with('student')->get();
        return view('manager.community.studentsStates')
            ->with(compact('users'));
    }


    public function coursesIndex()
    {
        try {
            if (Auth::user()->hasRole('خدمة المجتمع')) {
                $programs = json_encode(Program::with("departments.majors.courses")->get());
                return view('manager.community.courses.index')->with(compact('programs'));
            } else {
                return view("error")->with("error", "لا تملك الصلاحيات لدخول لهذه الصفحة");
            }
        } catch (Exception $e) {
            return view("error")->with("error", "حدث خطأ غير معروف");
            Log::error($e->getMessage() . ' ' . $e);
        }
    }



    public function createCourseForm()
    {

        try {
            if (Auth::user()->hasRole('خدمة المجتمع')) {
                $programs = json_encode(Program::with("departments.majors.courses")->get());
                return view('manager.community.courses.create')->with(compact('programs'));
            } else {
                return view("error")->with("error", "لا تملك الصلاحيات لدخول لهذه الصفحة");
            }
        } catch (Exception $e) {
            return view("error")->with("error", "حدث خطأ غير معروف");
            Log::error($e->getMessage() . ' ' . $e);
        }
    }




    public function createCourse(Request $request)
    {
        $requestData = $this->validate($request, [
            "major"         => "required|numeric|exists:majors,id",
            "name"          => "required|string|min:3|max:100",
            "code"          => "required|string|min:3|max:15",
            "level"         => "required|numeric|min:1|max:5",
            "credit_hours"  => "required|numeric|min:1|max:20",
            "contact_hours" => "required|numeric|min:1|max:20",
        ]);
        $major = Major::findOrFail($requestData["major"]);

        try {
            $major->courses()->create([
                'name' => $requestData["name"],
                'code' => $requestData["code"],
                'level' => $requestData["level"],
                'suggested_level' => 0,
                'credit_hours' => $requestData["credit_hours"],
                'contact_hours' => $requestData["contact_hours"],
            ]);
            return redirect(route("coursesIndex"))->with("success", "تم انشاء المقرر بنجاح");
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with("error", "حدث خطأ غير معروف تعذر انشاء المقرر");
        }
    }


    public function deleteCourse(Course $course)
    {
        try {
            $course->delete();
            return response()->json(["message" => "تم حذف المقرر بنجاح"], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return response()->json(["message" => "حدث خطأ غير معروف تعذر حذف المقرر"], 422);
        }
    }



    public function editCourseForm(Course $course)
    {
        return view("manager.community.courses.edit")->with(compact('course'));
    }



    public function editCourse(Request $request)
    {
        $requestData = $this->validate($request, [
            "id"         => "required|numeric|exists:courses,id",
            "name"          => "required|string|min:3|max:100",
            "code"          => "required|string|min:3|max:15",
            "level"         => "required|numeric|min:1|max:5",
            "credit_hours"  => "required|numeric|min:1|max:20",
            "contact_hours" => "required|numeric|min:1|max:20",
        ]);

        $course = Course::findOrFail($requestData["id"]);
        try {
            $course->update([
                'name' => $requestData["name"],
                'code' => $requestData["code"],
                'level' => $requestData["level"],
                'credit_hours' => $requestData["credit_hours"],
                'contact_hours' => $requestData["contact_hours"],
            ]);
            return redirect(route("coursesIndex"))->with("success", "تم تعديل المقرر بنجاح");
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with("error", "حدث خطأ غير معروف تعذر تعديل المقرر");
        }
    }



    public function reportAllForm()
    {

        try {
            $programs = [];
            if (Auth::user()->hasRole('خدمة المجتمع')) {
                $programs = json_encode(Program::with("departments.majors.courses")->get());
            } else {
                return back()->with("error", " لا تملك الصلاحيات لدخول لهذه الصفحة ");
            }

            $baccCount = Student::where('program_id', 1)->where('credit_hours', '>', 0)->count();

            $baccSumWallets = Student::where('program_id', 1)->sum('wallet');

            $baccSumHours = Student::where('program_id', 1)->sum('credit_hours');


            $baccSumDeductions = Transaction::with("order.student")->whereHas("order.student", function ($res) {
                $res->where("program_id", 1);
            })->where("type", "deduction")->sum("amount");

            // $baccSumDiscount = Order::with('student')->whereHas('student', function ($res) {
            //     $res->where('program_id', 1);
            // })->sum('discount');

            $baccSumDiscount = $baccSumHours * 550 - $baccSumDeductions;
        
            $baccCommunityAmount = $baccSumDeductions * 0.15;
            $baccGeneralManageAmount = $baccSumDeductions * 0.05;
            $baccUnitAmount = $baccSumDeductions * 0.80;


            $baccSum = $baccSumWallets + $baccSumDeductions;

            // ###################################### Deplom #######################################

            $diplomCount = Student::where('program_id', 2)->where('credit_hours', '>', 0)->count();

            $diplomSumWallets = Student::where('program_id', 2)->sum('wallet');

            $diplomSumHours = Student::where('program_id', 2)->sum('credit_hours');

            $diplomSumDeductions = Transaction::with("order.student")->whereHas("order.student", function ($res) {
                $res->where("program_id", 2);
            })->where("type", "deduction")->sum("amount");

            // $diplomSumDiscount = Order::with('student')->whereHas('student', function ($res) {
            //     $res->where('program_id', 2);
            // })->sum('discount');

            $diplomSumDiscount = $diplomSumHours * 400 - $diplomSumDeductions;
            
            $diplomCommunityAmount = $diplomSumDeductions * 0.15;
            $diplomGeneralManageAmount = $diplomSumDeductions * 0.05;
            $diplomUnitAmount = $diplomSumDeductions * 0.80;
            $diplomSum = $diplomSumWallets + $diplomSumDeductions;

            return view("manager.community.reports.all")->with(compact(
                [
                    'baccCount',
                    'baccSumWallets',
                    'baccSumHours',
                    'baccSumDeductions',
                    'baccSumDiscount',
                    'baccCommunityAmount',
                    'baccUnitAmount',
                    'baccGeneralManageAmount',
                    'baccSum',

                    'diplomCount',
                    'diplomSumWallets',
                    'diplomSumHours',
                    'diplomSumDeductions',
                    'diplomSumDiscount',
                    'diplomCommunityAmount',
                    'diplomGeneralManageAmount',
                    'diplomUnitAmount',
                    'diplomSum',

                    'programs'
                ]
            ));
        } catch (Exception $e) {
            dd($e);
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with("error", "حدث خطأ غير معروف ");
        }
    }






    public function reportFilterdForm()
    {
        if (Auth::user()->hasRole('خدمة المجتمع')) {
            $programs = json_encode(Program::with("departments.majors.courses")->get());
            return view('manager.community.reports.filtered')->with(compact('programs'));
        } else {
            return view("error")->with("error", "لا تملك الصلاحيات لدخول لهذه الصفحة");
        }
    }

    public function reportFilterd(Request $request)
    {
        $requestData = $this->validate($request, [
            "prog_id"         => "required|numeric|exists:programs,id",
            "dept_id"         => "required|numeric|exists:departments,id",
            "major_id"         => "required|numeric|exists:majors,id",

        ]);
        try {
            $programs = [];
            $programObj = Program::findOrFail($requestData['prog_id']);
            $department = Department::findOrFail($requestData['dept_id']);
            $major = Major::findOrFail($requestData['major_id']);


            if (Auth::user()->hasRole('خدمة المجتمع')) {
                $programs = json_encode(Program::with("departments.majors.courses")->get());
            } else {
                return view("error")->with("error", "لا تملك الصلاحيات لدخول لهذه الصفحة");
            }



            $count = Student::where("program_id",  $requestData['prog_id'])
                ->where('credit_hours', '>', 0)
                ->where("department_id", $requestData['dept_id'])
                ->where("major_id", $requestData['major_id'])
                ->count();

            $sumHours = Student::where("program_id",  $requestData['prog_id'])
                ->where("department_id", $requestData['dept_id'])
                ->where("major_id", $requestData['major_id'])
                ->sum('credit_hours');

            $sumDeductions = Transaction::with("order.student")->whereHas("order.student", function ($res) use ($requestData) {
                $res->where("program_id", $requestData['prog_id'])
                    ->where("department_id", $requestData['dept_id'])
                    ->where("major_id", $requestData['major_id']);
            })->where("type", "deduction")->sum("amount");

            // $sumDiscount = Order::with('student')->whereHas('student', function ($res) use ($requestData) {
            //     $res->where("program_id", $requestData['prog_id'])
            //         ->where("department_id", $requestData['dept_id'])
            //         ->where("major_id", $requestData['major_id']);
            // })->sum('discount');

            $sumDiscount = $sumHours * $programObj->hourPrice - $sumDeductions;
            $communityAmount = $sumDeductions * 0.15;
            $unitAmount = $sumDeductions * 0.80;
            $generalManageAmount = $sumDeductions * 0.05;


            return view("manager.community.reports.filtered")->with(compact(
                [
                    'count',
                    'sumHours',
                    'sumDeductions',
                    'sumDiscount',
                    'communityAmount',
                    'unitAmount',
                    'generalManageAmount',

                    'programObj',
                    'department',
                    'major',

                    'programs',

                ]
            ));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with("error", "حدث خطأ غير معروف تعذر تعديل المقرر");
        }
    }



    public function getStudent($id)
    {
        try {
            $user = User::with('student.payments.transaction')->whereHas('student', function ($result) use ($id) {
                $result->where('national_id', $id)->orWhere('rayat_id', $id);
            })->first();
            if (!isset($user)) {
                return response()->json(["message" => "لا يوجد متدرب بهذا الرقم"], 422);
            }
            $waitingTransCount = $user->student->payments()->where("accepted", null)->count();
            if ($waitingTransCount > 0) {
                return response()->json(["message" => "يوجد طلب شحن قيد المراجعة لهذا المتدرب"], 422);
            }
            return response()->json($user, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            DB::rollBack();
            return response()->json(["message" => "لا يوجد متدرب بهذا الرقم"], 422);
        }
    }

    public function getStudentForReport($id)
    {
        try {
            $user = User::with('student.payments.transaction')->whereHas('student', function ($result) use ($id) {
                $result->where('national_id', $id)->orWhere('rayat_id', $id);
            })->first();
            if (!isset($user)) {
                return response()->json(["message" => "لا يوجد متدرب بهذا الرقم"], 422);
            }
            return response()->json($user, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            DB::rollBack();
            return response()->json(["message" => "لا يوجد متدرب بهذا الرقم"], 422);
        }
    }



    public function chargeForm()
    {
        return view('manager.charge_wallet');
    }

    public function charge(Request $request)
    {
        $semester = Semester::latest()->first();
        $paymentRequest = $this->validate($request, [
            "id"                => 'required|string|max:10|min:10',
            "amount"            => "required|numeric|min:0|max:20000",
            "note"              => "string|nullable",
            "payment_receipt"   => "required_if:action,charge|mimes:pdf,png,jpg,jpeg|max:4000",
            "action"            => "required|in:deduction,charge",

        ]);

        try {

            $user = User::with('student.courses')->whereHas('student', function ($result) use ($paymentRequest) {
                $result->where('national_id', $paymentRequest['id'])->orWhere('rayat_id', $paymentRequest['id']);
            })->first();
            if (!isset($user)) {
                return back()->with("error", "لا يوجد متدرب بهذا الرقم");
            }
            $waitingTransCount = $user->student->payments()->where("accepted", null)->count();
            if ($waitingTransCount > 0) {
                return back()->with("error", "تعذر ارسال الطلب يوجد طلب شحن رصيد قيد المراجعة");
            }

            DB::beginTransaction();
            if ($paymentRequest['action'] == "charge") {
                $randomId =  uniqid();
                $doc_name =  $randomId . '.' . $paymentRequest['payment_receipt']->getClientOriginalExtension();
                $payment = $user->student->payments()->create(
                    [
                        "amount"            => $paymentRequest["amount"],
                        "semester_id"        => $semester->id,
                        "receipt_file_id"   => $doc_name,
                    ]
                );
                Storage::disk('studentDocuments')->put('/' . $user->national_id . '/receipts/' . $doc_name, File::get($paymentRequest['payment_receipt']));
            }

            $transaction = $user->student->transactions()->create([
                "payment_id"    => $payment->id ?? null,
                "amount"        => $paymentRequest["amount"],
                "note"          => $paymentRequest['action'] == "charge" ? ' ( اضافة رصيد من قبل الادارة ) ' . $paymentRequest["note"] : ' ( خصم رصيد من قبل الادارة ) ' . $paymentRequest["note"],
                "type"          => $paymentRequest['action'] == "charge" ? "manager_recharge" : "manager_deduction",
                "manager_id"    => Auth::user()->manager->id,
                "semester_id"   => $semester->id,
            ]);

            if ($paymentRequest['action'] == "charge") {
                $payment->update([
                    "accepted"           => true,
                    "transaction_id"     => $transaction->id,
                ]);
                $user->student->wallet += $paymentRequest["amount"];
            } else {
                $user->student->wallet -= $paymentRequest["amount"];
            }

            $user->student->save();

            DB::commit();
            return  back()->with("success", "تمت المعالجة بنجاح");
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            DB::rollBack();
            return back()->with("error", "تعذر ارسال الطلب حدث خطا غير معروف");
        }





        // $transaction = $user->student->transactions()->create([
        //     "payment_id"    => $payment->id,
        //     "amount"        => $reviewedPayment["amount"],
        //     "note"          => $reviewedPayment["note"],
        //     "type"          => "recharge",
        //     "manager_id"       => Auth::user()->id,
        // ]);
        // $payment->update([
        //     "transaction_id" => $transaction->id,
        //     "note"          => $reviewedPayment["note"],
        // ]);






        // try {
        //     $user = User::with('student.courses')->whereHas('student', function ($result) use ($paymentRequest) {
        //         $result->where('national_id', $paymentRequest['id'])->orWhere('rayat_id', $paymentRequest['id']);
        //     })->first();
        //     if (!isset($user)) {
        //         return back()->with("error","لا يوجد متدرب بهذا الرقم");
        //     }
        //     DB::beginTransaction();
        //         $user->student->transactions()->create([
        //             "amount"        => $paymentRequest["amount"],
        //             "note"          => 'اضافة رصيد من قبل الادارة',
        //             "type"          => "manager_recharge",
        //             "manager_id"       => Auth::user()->id,
        //         ]);

        //         $user->student->wallet += $paymentRequest["amount"];
        //         $user->student->save();

        //     DB::commit();
        //     return back()->with("success","تم اضافة المبلغ في محفظة المتدرب بنجاح");
        // } catch (Exception $e) {
        //    Log::error($e->getMessage().' '.$e);
        //     DB::rollBack();
        //     return back()->with("error","تعذر ارسال الطلب حدث خطا غير معروف");

        // }
    }

    public function refundOrdersForm()
    {
        try {
            $refunds = RefundOrder::where('accepted', null)->get();
            return view('manager.community.refundOrders')->with(compact('refunds'));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with("error", "تعذر ارسال الطلب حدث خطا غير معروف");
        }
    }

    public function refundOrdersReport()
    {
        try {
            $refunds = RefundOrder::where('accepted', '!=', null)->get();
            return view('manager.community.reports.refund')->with(compact('refunds'));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with("error", "تعذر ارسال الطلب حدث خطا غير معروف");
        }
    }

    public function refundOrdersUpdate(Request $request)
    {
        $semester = Semester::latest()->first();
        $requestData = $this->validate($request, [
            "refund_id"         => "required|numeric",
            "note"              => "nullable|string",
            "range"             => "exclude_if:accepted,false|required|in:before-training,before-4th-week,refund-all-amount",
            "accepted"          => "required|boolean"
        ]);
        // return response(['message'=>$requestData, 200]);

        try {
            $refund = RefundOrder::where('id', $requestData['refund_id'])->first();
            if ($refund === null) {
                return response(['message' => "خطأ في بيانات الطلب"], 422);
            } else if ($refund->accepted !== null) {
                return response(['message' => "تمت معالجة هذا الطلب من قبل"], 422);
            }

            switch ($refund->reason) {
                case 'drop-out':
                    $reason = 'انسحاب';
                    break;
                case 'graduate':
                    $reason = 'خريج';
                    break;
                case 'exception':
                    $reason = 'استثناء';
                    break;
                case 'not-opened-class':
                    $reason = 'لم تتاح الشعبة';
                    break;
                case 'get-wallet-amount':
                    $reason = 'استرداد مبلغ المحفظة';
                    break;
                default:
                    $reason = 'لا يوجد';
            }


            DB::beginTransaction();
            if ($requestData['accepted']) {
                switch ($requestData['range']) {
                    case 'before-training':
                        $amount = $refund->amount - 300;
                        break;
                    case 'before-4th-week':
                        $amount = $refund->amount * 0.6;
                        break;
                    case 'refund-all-amount':
                        $amount = $refund->amount;
                        break;

                    default:
                        break;
                }

                $transaction = $refund->student->transactions()->create([
                    "refund_order_id"     => $refund->id,
                    "amount"        => $amount,
                    "note"          => ' مبلغ مسترد - السبب ' . $reason,
                    "type"          => $refund->refund_to == 'wallet' ? "refund-to-wallet" : "refund-to-bank",
                    "manager_id"       => Auth::user()->manager->id,
                    "semester_id"        => $semester->id,

                ]);

                if ($refund->refund_to == 'wallet') {
                    $refund->student->wallet += $amount;
                } else {
                    $refund->student->wallet -= $amount;
                }

                if (in_array($refund->reason, ['drop-out', 'exception', 'not-opened-class'])) {
                    $refund->student->credit_hours = 0;
                }

                $refund->student->save();

                $refund->update([
                    'transaction_id'    => $transaction->id,
                    'manager_note'      => $requestData['note'],
                    'amount'            => $amount,
                    'accepted'          => true,

                ]);
            } else {
                $refund->update([
                    'manager_note'      => $requestData['note'],
                    'accepted'          => false,
                ]);
            }
            DB::commit();
            return response(['message' => 'تمت معالجة طلب الاسترداد بنجاح'], 200);
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return response(['message' => $e], 422);
        }
    }
}
