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
use App\Models\Student;
use App\Models\Transaction;
use App\Models\User;
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
                "name" => "شحن محفظة متدرب",
                "url" => route("chargeForm")
            ],

            (object) [
                "name" => "الرفع لرايات",
                "url" => route('publishToRayatForm', ["type" => "community"])
            ],

            (object) [
                "name" => "تقرير رايات",
                "url" => route("rayatReportFormCommunity")
            ],
            (object) [
                "name" => "جميع المتدربين المستمرين",
                "url" => route("oldStudentsReport")
            ],
            (object) [
                "name" => "جميع المتدربين المستجدين",
                "url" => route("newStudentsReport")
            ],
            (object) [
                "name" => "ادارة المستخدمين",
                "url" => route("manageUsersForm")
            ],

            (object) [
                "name" => "ادارة المقررات",
                "url" => route("coursesIndex")
            ],
            (object) [
                "name" => "فصل دراسي جديد",
                "url" => route("newSemester")
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



    public function manageUsersForm()
    {
        try {
            $users = User::with("manager.permissions")->whereHas("manager")->where("id", ">", 1)->get();
            return view("manager.community.users.manage")->with(compact('users'));
        } catch (Exception $e) {
            Log::error($e);
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
            Log::error($e);
            return back()->with('error', ' حدث خطأ غير معروف ' . $e->getCode());
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
            Log::error($e);
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
            Log::error($e);
            DB::rollBack();
            return back()->with("error", "تعذر ارسال الطلب حدث خطا غير معروف");
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
            Log::error($e);
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
            Log::error($e);
            return back()->with('error', ' حدث خطأ غير معروف ' . $e->getCode());
        }
    }

    public function deleteUserPermission(Permission $permission)
    {
        try {
            $permission->delete();
            return back()->with('success', 'تم ازالة الصلاحية بنجاح');
        } catch (Exception $e) {
            Log::error($e);
            return back()->with('error', ' حدث خطأ غير معروف ' . $e->getCode());
        }
    }

    public function paymentsReviewForm()
    {
        $fetch_errors = [];
        try {


            $users = User::with("student.payments")
                ->whereHas("student", function ($res) {
                    $res->whereHas("payments", function ($res) {
                        $res->where("accepted", null);
                    });
                })->get();
            for ($i = 0; $i < count($users); $i++) {
                foreach ($users[$i]->student->payments as $payment) {
                    try {
                        if ($payment->accepted == null) {

                            $users[$i]->student->payment = $payment;
                            $users[$i]->student->receipt = Storage::disk('studentDocuments')->files(
                                $users[$i]->national_id . '/receipts/' . $payment->receipt_file_id
                            )[0];
                            break;
                        }
                    } catch (Exception $e) {
                        Log::error($e);
                        array_push($fetch_errors, $users[$i]->name);
                        continue;
                    }
                }
            }
        } catch (Exception $e) {
            Log::error($e);
            return view('manager.community.paymentsReview')->with('error', "تعذر جلب المتدربين");
        }
        return view('manager.community.paymentsReview')->with(compact('users'));
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
            $decision = false;
            if ($reviewedPayment["decision"] == "accept") {
                $decision = true;
            }
            $user = User::with('student')->where('national_id', $reviewedPayment['national_id'])->first();
            $payment = Payment::where("id", $reviewedPayment["payment_id"])->first();

            DB::beginTransaction();

            if ($decision == true) {
                $transaction = $user->student->transactions()->create([
                    "payment_id"    => $payment->id,
                    "amount"        => $reviewedPayment["amount"],
                    "note"          => $reviewedPayment["note"],
                    "type"          => "recharge",
                    "by_user"       => Auth::user()->id,
                ]);
                $payment->update([
                    "transaction_id" => $transaction->id,
                    "note"          => $reviewedPayment["note"],
                    "accepted"       => true
                ]);
            } else {
                $payment->update([
                    "note"          => $reviewedPayment["note"],
                    "accepted"       => false
                ]);
            }

            $user->student->wallet += $reviewedPayment["amount"];
            $user->student->save();
            DB::commit();
            return response(json_encode(['message' => 'تم ارسال الطلب بنجاح']), 200);
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return response(json_encode(['message' => 'حدث خطأ غير معروف' . $e->getMessage()]), 422);
        }
    }


    public function paymentsReviewVerifiyDocs(Request $request)
    {
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
                    "by_user"    => Auth::user()->id,
                ]);
                $payment->update([
                    "transaction_id" => $transaction->id,
                    "accepted"       => $decision
                ]);
                $user->student->wallet += $payment->amount;
                $user->student->save();

            } else {
                $payment->update([
                    "accepted"       => false
                ]);
            }

            DB::commit();
            return response(json_encode(['message' => 'تم ارسال الطلب بنجاح']), 200);
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return response(json_encode(['message' => 'حدث خطأ غير معروف' . $e->getMessage()]), 422);
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

        ]);

        try {


            if (!Hash::check($requestData["password"], Auth::user()->password) && $requestData["national_id"] != Auth::user()->national_id) {
                return  back()->with('error', 'البيانات المدخلة لا تتطابق مع سجلاتنا');
            }
            DB::beginTransaction();

            DB::table('students')
                ->update([
                    'credit_hours' => 0,
                ]);

            DB::table('students')
                ->where('level', "<", 5)
                ->update([
                    'level' => DB::raw('level + 1'),
                ]);

            DB::commit();
            return redirect(route("communityDashboard"))->with("success", "تم معالجة الطلب بنجاح");
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return back()->with('error', 'حدث خطأ غير معروف');
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
            
        foreach ($users as $user) {
            foreach ($user->student->orders as $order) {
                if ($order->transaction_id == null && $order->private_doc_verified == true) {
                    $user->student->order = $order;
                    break;
                }
            }
        }
        if (isset($users)) {
            return view('manager.community.publishHoursToRayat')
                ->with(compact('users'));
        } else {
            return view('manager.community.publishHoursToRayat')
                ->with('error', "تعذر جلب المتدربين");
        }
    }

    public function publishToRayat(Request $request)
    {
        $requestData = $this->validate($request, [
            "national_id"        => "required|digits:10",
            "requested_hours"    => "required|numeric|min:0|max:21",
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

            $hoursCost = $requestData['requested_hours'] * ($user->student->program->hourPrice * $discount);
            $canAddHours = $requestData['requested_hours'];
            $note = null;

            if ($user->student->traineeState != 'privateState') {
                if ($hoursCost >= $user->student->wallet) {
                    $canAddHours = floor($user->student->wallet / ($user->student->program->hourPrice * $discount));
                }
            }

            if ($requestData['requested_hours'] > $canAddHours) {
                return response(['message' => "عدد الساعات اكبر من الحد الاعلى"], 422);
            }

            if ($requestData['requested_hours'] > $order->requested_hours) {
                return response(['message' => "عدد الساعات اكبر من الحد الاعلى"], 422);
            }

            if ($requestData['requested_hours'] < $order->requested_hours) {
                $note = " تم تغيير عدد الساعات من " .
                    $order->requested_hours .
                    " الى " . $requestData['requested_hours'] .
                    " لعدم امكانية اضافتها الى رايات او عدم كفاية الرصيد ";
            }

            $transaction = $user->student->transactions()->create([
                "order_id"    => $order->id,
                "amount"        => $hoursCost,
                "type"          => "deduction",
                "by_user"       => Auth::user()->id,
            ]);

            $order->update([
                "amount" => $hoursCost,
                "requested_hours" => $requestData['requested_hours'],
                "note"          => $note,
                "transaction_id" => $transaction->id,
            ]);

            $user->student->wallet -= $hoursCost;
            $user->student->credit_hours += $requestData['requested_hours'];
            $user->student->save();

            return response(['message' => 'تم قبول الطلب بنجاح'], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response(['message' => 'حدث خطأ غير معروف' . $e->getCode()], 422);
        }
    }

    public function rayatReportForm()
    {
        $users = User::with('student')->whereHas('student', function ($result) {
            $result->where('level', '>', '1')
                ->where('credit_hours', '>', 0);
        })->get();

        if (isset($users)) {
            return view('manager.community.rayatReport')
                ->with(compact('users'));
        } else {
            return view('manager.community.rayatReport')
                ->with('error', "تعذر جلب المتدربين");
        }
    }




    public function oldStudentsReport()
    {
        $users = User::with('student')->whereHas('student', function ($result) {
            $result->where('level', '>', '1');
        })->get();
        return view('manager.community.oldStudentsReport')
            ->with(compact('users'));
    }

    public function newStudentsReport()
    {
        $users = User::with('student')->whereHas('student', function ($result) {
            $result->where('level', '1');
        })->get();
        return view('manager.community.newStudentsReport')
            ->with(compact('users'));
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
            Log::error($e);
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
            Log::error($e);
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
            Log::error($e);
            return back()->with("error", "حدث خطأ غير معروف تعذر انشاء المقرر");
        }
    }


    public function deleteCourse(Course $course)
    {
        try {
            $course->delete();
            return response()->json(["message" => "تم حذف المقرر بنجاح"], 200);
        } catch (Exception $e) {
            Log::error($e);
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
            Log::error($e);
            return back()->with("error", "حدث خطأ غير معروف تعذر تعديل المقرر");
        }
    }



    public function reportAllForm()
    {


        try {
            $programs = [];
            if (Auth::user()->hasRole('خدمة المجتمع')) {
                $programs = json_encode(Program::with("departments.majors.courses")->get());
            }

            $baccCount = User::with("student")->whereHas("student", function ($res) {
                $res->where("program_id", 1);
            })->get()->count();

            $baccSumWallets = User::with("student")->whereHas("student", function ($res) {
                $res->where("program_id", 1);
            })->get()->sum("student.wallet");


            $baccSumDeductions = Transaction::with("order.student")->whereHas("order.student", function ($res) {
                $res->where("program_id", 1);
            })->where("type", "deduction")->get()->sum("amount");


            $diplomCount = User::with("student")->whereHas("student", function ($res) {
                $res->where("program_id", 2);
            })->get()->count();

            $diplomSumWallets = User::with("student")->whereHas("student", function ($res) {
                $res->where("program_id", 2);
            })->get()->sum("student.wallet");


            $diplomSumDeductions = Transaction::with("order.student")->whereHas("order.student", function ($res) {
                $res->where("program_id", 2);
            })->where("type", "deduction")->get()->sum("amount");

            $baccSum = $baccSumWallets + $baccSumDeductions;
            $diplomSum = $diplomSumWallets + $diplomSumDeductions;
            return view("manager.community.reports.all")->with(compact(
                [
                    'baccCount',
                    'baccSumWallets',
                    'baccSumDeductions',
                    'diplomCount',
                    'diplomSumWallets',
                    'diplomSumDeductions',
                    'baccSum',
                    'diplomSum',
                    'programs'
                ]
            ));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with("error", "حدث خطأ غير معروف تعذر تعديل المقرر");
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
            }

            $count = User::with("student")->whereHas("student", function ($res) use ($requestData) {
                $res->where("program_id",  $requestData['prog_id'])
                    ->where("department_id", $requestData['dept_id'])
                    ->where("major_id", $requestData['major_id']);
            })->get()->count();

            $sumWallets = User::with("student")->whereHas("student", function ($res) use ($requestData) {
                $res->where("program_id",  $requestData['prog_id'])
                    ->where("department_id", $requestData['dept_id'])
                    ->where("major_id", $requestData['major_id']);
            })->get()->sum("student.wallet");


            $sumDeductions = Transaction::with("order.student")->whereHas("order.student", function ($res)  use ($requestData) {
                $res->where("program_id",  $requestData['prog_id'])
                    ->where("department_id", $requestData['dept_id'])
                    ->where("major_id", $requestData['major_id']);
            })->where("type", "deduction")->get()->sum("amount");

            $sum = $sumWallets + $sumDeductions;
            return view("manager.community.reports.filtered")->with(compact(
                [
                    'count',
                    'sumWallets',
                    'sumDeductions',
                    'sum',
                    'programs',
                    'programObj',
                    'department',
                    'major'
                ]
            ));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with("error", "حدث خطأ غير معروف تعذر تعديل المقرر");
        }
    }



    public function getStudent($id)
    {
        try {
            $user = User::with('student.courses')->whereHas('student', function ($result) use ($id) {
                $result->where('national_id', $id)->orWhere('rayat_id', $id);
            })->first();
            if (!isset($user)) {
                return response()->json(["message" => "لا يوجد متدرب بهذا الرقم"], 422);
            }
            $waitingTransCount = $user->student->payments()->where("transaction_id", "=", null)->count();
            if ($waitingTransCount > 0) {
                return response()->json(["message" => "يوجد طلب شحن قيد المراجعة لهذا المتدرب"], 422);
            }
            return response()->json($user, 200);
        } catch (Exception $e) {
            Log::error($e);
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
        $paymentRequest = $this->validate($request, [
            "id"            => 'required|string|max:10|min:10',
            "amount"            => "required|numeric|min:0|max:20000",
            "note"               => "string|nullable",
            "payment_receipt"   => "required|mimes:pdf,png,jpg,jpeg|max:4000",


        ]);

        try {

            $user = User::with('student.courses')->whereHas('student', function ($result) use ($paymentRequest) {
                $result->where('national_id', $paymentRequest['id'])->orWhere('rayat_id', $paymentRequest['id']);
            })->first();
            if (!isset($user)) {
                return back()->with("error", "لا يوجد متدرب بهذا الرقم");
            }
            $waitingTransCount = $user->student->payments()->where("transaction_id", "=", null)->count();
            if ($waitingTransCount > 0) {
                return back()->with("error", "تعذر ارسال الطلب يوجد طلب شحن رصيد قيد المراجعة");
            }

            DB::beginTransaction();

            $randomId =  uniqid();
            $payment = $user->student->payments()->create(
                [
                    "amount"            => $paymentRequest["amount"],
                    "receipt_file_id"   => $randomId
                ]
            );

            $transaction = $user->student->transactions()->create([
                "payment_id"    => $payment->id,
                "amount"        => $paymentRequest["amount"],
                "note"          => ' ( اضافة رصيد من قبل الادارة ) ' . $paymentRequest["note"],
                "type"          => "manager_recharge",
                "by_user"       => Auth::user()->id,
            ]);

            $payment->update([
                "transaction_id" => $transaction->id,
                "note"          => ' ( اضافة رصيد من قبل الادارة ) ' . $paymentRequest["note"],
            ]);

            $user->student->wallet += $paymentRequest["amount"];
            $user->student->save();

            $doc_name =  date('Y-m-d-H-i') . '_payment_receipt.' . $paymentRequest['payment_receipt']->getClientOriginalExtension();
            Storage::disk('studentDocuments')->put('/' . $user->national_id . '/receipts/' . $randomId . '/' . $doc_name, File::get($paymentRequest['payment_receipt']));
            DB::commit();
            return  back()->with("success", "تم اضافة المبلغ الي محفظة المتدرب بنجاح");
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return back()->with("error", "تعذر ارسال الطلب حدث خطا غير معروف");
        }





        // $transaction = $user->student->transactions()->create([
        //     "payment_id"    => $payment->id,
        //     "amount"        => $reviewedPayment["amount"],
        //     "note"          => $reviewedPayment["note"],
        //     "type"          => "recharge",
        //     "by_user"       => Auth::user()->id,
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
        //             "by_user"       => Auth::user()->id,
        //         ]);

        //         $user->student->wallet += $paymentRequest["amount"];
        //         $user->student->save();

        //     DB::commit();
        //     return back()->with("success","تم اضافة المبلغ في محفظة المتدرب بنجاح");
        // } catch (Exception $e) {
        //     Log::error($e);
        //     DB::rollBack();
        //     return back()->with("error","تعذر ارسال الطلب حدث خطا غير معروف");

        // }
    }

    public function refundOrdersForm()
    {
        try {
            $orders = RefundOrder::where('accepted', null)->get();
            return view('manager.community.refundOrders')->with(compact('orders'));
        } catch (\Throwable $th) {
            Log::error();
            throw $th;
        }
    }

    public function refundOrdersUpdate(Request $request)
    {
        $requestData = $this->validate($request, [
            "refund_id"         => "required|numeric",
            "national_id"       => "required|numeric",
            "accepted"          => "required"
        ]);

        try {
            $refund = RefundOrder::where('id', $requestData['refund_id'])->first();
            $user = User::where('national_id', $requestData['national_id'])->first();
            switch($refund->reason){
                case 'drop-out':
                    $reason = 'انسحاب';
                    break;
                case 'graduate':
                    $reason = 'خريج';
                    break;
                case 'exception':
                    $reason = 'استثناء';
                    break;
                defaul:
                $reason = 'لا يوجد';
            }
            
            DB::beginTransaction();
                if($requestData['accepted']){
                    $transaction = $user->student->transactions()->create([
                        "refund_id"     => $refund->id,
                        "amount"        => $refund->amount,
                        "note"          => ' مبلغ مسترد - السبب ' . $reason,
                        "type"          => "refund",
                        "by_user"       => Auth::user()->id,
                    ]);
                    $refund->update([
                        'transaction_id'    => $transaction->id,
                        'accepted'          => $requestData['accepted']
                    ]);
                    $refund->student->wallet -= $refund->amount;
                    $refund->student->save();
                }else{
                    $refund->update([
                        'accepted'          => $requestData['accepted']
                    ]);
                }
            DB::commit();
            return response(['message'=>'تمت معالجة طلب الاسترداد بنجاح'], 200);
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return response(['message'=>$e], 422);
        }
    }
}
