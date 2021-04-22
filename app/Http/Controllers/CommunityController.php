<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CommunityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $links = [
            (object) [
                "name" => "تدقيق الايصالات",
                "url" => route("paymentsReviewForm")
            ],
            // (object) [
            //     "name" => "المتدربين المدققة ايصالاتهم",
            //     "url" => route("CheckedStudents")
            // ],
            // (object) [
            //     "name" => "انشاء مستخدم",
            //     "url" => route("createUserForm")
            // ],
            (object) [
                "name" => "فصل دراسي جديد",
                "url" => route("newSemester")
            ],
            // (object) [
            //     "name" => "متابعة حالات المتدربين",
            //     "url" => route("studentsStates")
            // ],

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
                "name" => "شحن محفظة متدرب",
                "url" => route("chargeForm")
            ],

        ];
        return view("manager.community.dashboard")->with(compact("links"));
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
        $roles = Role::all();
        if (Auth::user()->hasRole("خدمة المجتمع")) {
            return view("manager.community.users.edit")->with(compact('roles', 'user'));
        } else {
            return back()->with("error", "ليس لديك صلاحيات لتنفيذ هذا الامر");
        }
    }

    public function editUserPermissionsUpdate(Request $request, User $user)
    {
        $requestData = $this->validate($request, [
            "roles"      => "required|array|min:1",
            "roles.*"    => "required|numeric|distinct|exists:roles,id",
        ]);
        if (Auth::user()->hasRole("خدمة المجتمع")) {
            foreach ($requestData['roles'] as $role_id) {
                $user->manager->permissions()->create(array('role_id' => $role_id));
            }
            return redirect(route("manageUsersForm"))->with("success", "تم تعديل الصلاحيات بنجاح");
        } else {
            return back()->with("error", "ليس لديك صلاحيات لتنفيذ هذا الامر");
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
                            $res->where("transaction_id", null);
                        });
                })->get();
            for ($i = 0; $i < count($users); $i++) {
                foreach ($users[$i]->student->payments as $payment) {
                    try {
                        if ($payment->transaction_id == null) {

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


    // public function paymentsReviewJson()
    // {

    //     $users = User::with('student')->whereHas('student', function ($result) {
    //         $result->where('traineeState', '!=', 'privateState');
    //     })->get();

    //     for ($i = 0; $i < count($users); $i++) {
    //         // $documents = Storage::disk('studentDocuments')->files($user->national_id);
    //         $users[$i]['receipts'] = Storage::disk('studentDocuments')->files($users[$i]->national_id . '/receipts');
    //         $users[$i]->progname = $users[$i]->student->program->name;
    //         $users[$i]->deptname = $users[$i]->student->department->name;
    //         $users[$i]->mjrname = $users[$i]->student->major->name;
    //     }
    //     //return view('manager.community.paymentsReview')->with(compact('users'));
    //     return response(\json_encode(['data' => $users]), 200);
    // }

    public function paymentsReviewUpdate(Request $request)
    {
        $reviewedPayment = $this->validate($request, [
            "national_id"        => "required|numeric",
            "payment_id"         => "required|numeric|exists:payments,id",
            "amount"             => "required|numeric",
            "note"               => "string|nullable"
        ]);
        try {
            DB::beginTransaction();
            $user = User::with('student')->where('national_id', $reviewedPayment['national_id'])->first();

            $payment = Payment::where("id", $reviewedPayment["payment_id"])->first();
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
            ]);

            $user->student->wallet += $reviewedPayment["amount"];
            $user->student->save();
            DB::commit();
            return response(json_encode(['message' => 'تم قبول الطلب بنجاح']), 200);
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
        ]);

        try {
            DB::beginTransaction();
            $user = User::with('student')->where('national_id', $reviewedPayment['national_id'])->first();

            $payment = Payment::where("id", $reviewedPayment["payment_id"])->first();
            $transaction = $user->student->transactions()->create([
                "payment_id"    => $payment->id,
                "amount"    => $payment->amount,
                "type"    => "recharge",
                "by_user"    => Auth::user()->id,
            ]);
            $payment->update([
                "transaction_id" => $transaction->id,
            ]);

            $user->student->wallet += $payment->amount;
            $user->student->save();
            DB::commit();
            return response(json_encode(['message' => 'تم قبول الطلب بنجاح']), 200);
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
                        $res->where("transaction_id", null);
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
}
