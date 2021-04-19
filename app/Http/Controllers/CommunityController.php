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
            // (object) [
            //     "name" => "فصل دراسي جديد",
            //     "url" => route("newSemester")
            // ],
            // (object) [
            //     "name" => "متابعة حالات المتدربين",
            //     "url" => route("studentsStates")
            // ],

            (object) [
                "name" => "الرفع لرايات",
                "url" => route("publishToRayatFormCommunity")
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

        ];
        return view("manager.community.dashboard")->with(compact("links"));
    }

    public function privateDashboard()
    {
        $links = [
            (object) [
                "name" => "تدقيق المستندات(ظروف خاصة)",
                "url" => route("PrivateAllStudentsForm")
            ],
            // (object) [
            //     "name" => "متابعة حالات المتدربين",
            //     "url" => route("studentsStates")
            // ],
        ];
        return view("manager.private.dashboard")->with(compact("links"));
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

    public function editUserUpdate(Request $request,User $user)
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
            $user->delete();
            return back()->with('success', 'تم حذف المستخدم بنجاح');
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
            // $users = User::with("student")->whereHas(
            //     "student",
            //     function ($res) {
            //         $res->where("traineeState", "!=", "privateState");
            //     }
            // )->get();
            $payments = Payment::where("transaction_id", null)->get();
            $paymentIds = $payments->pluck('student_id')->toArray();
            $users = User::with("student")->whereHas(
                "student",
                function ($res) use ($paymentIds) {
                    $res->where("traineeState", "!=", "privateState")
                        ->whereIn("id", $paymentIds);
                }
            )->get();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
            return view('manager.community.paymentsReview')->with('error', "تعذر جلب المتدربين");
        }
        for ($i = 0; $i < count($payments); $i++) {
            try {
                if ($users[$i]->student->id == $payments[$i]->student_id) {
                    $users[$i]->student->payment = $payments[$i];
                    $users[$i]->student->receipt = Storage::disk('studentDocuments')->files(
                        $users[$i]->national_id . '/receipts/' . $users[$i]->student->payments[0]->receipt_file_id
                    )[0];
                }
            } catch (Exception $e) {
                Log::error($e);
                array_push($fetch_errors, $users[$i]->name);
                continue;
            }
        }
        return view('manager.community.paymentsReview')->with(compact('users'));
    }


    public function paymentsReviewJson()
    {

        $users = User::with('student')->whereHas('student', function ($result) {
            $result->where('traineeState', '!=', 'privateState');
        })->get();

        for ($i = 0; $i < count($users); $i++) {
            // $documents = Storage::disk('studentDocuments')->files($user->national_id);
            $users[$i]['receipts'] = Storage::disk('studentDocuments')->files($users[$i]->national_id . '/receipts');
            $users[$i]->progname = $users[$i]->student->program->name;
            $users[$i]->deptname = $users[$i]->student->department->name;
            $users[$i]->mjrname = $users[$i]->student->major->name;
        }
        //return view('manager.community.paymentsReview')->with(compact('users'));
        return response(\json_encode(['data' => $users]), 200);
    }

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

    public function newSemester()
    {
        try {
            DB::table('students')->update([
                'documents_verified'    => false,
                'student_docs_verified' => false,
                'final_accepted'    => false,
                'published' => false,
            ]);
            return response(200);
        } catch (Exception $e) {
            return response(json_encode(['message' => 'حدث خطأ غير معروف' . $e->getCode()]), 422);
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

    public function private_all_student_form()
    {
        $users = User::with('student')->whereHas('student', function ($result) {
            $result->where('traineeState', 'privateState');
        })->get();

        for ($i = 0; $i < count($users); $i++) {
            $users[$i]['docs'] = Storage::disk('studentDocuments')->files($users[$i]->national_id . '/privateStateDoc');
        }

        return view('manager.private.private_student')->with(compact('users'));
    }



    public function publishToRayatForm()
    {
        // $users = User::with('student')->whereHas('student', function ($result) {
        //     $result->where('final_accepted', true)
        //         ->where('documents_verified', true)
        //         ->where('level', '>', '1')
        //         ->where("published", false);
        // })->get();

        // $payments = Payment::where("transaction_id", "!=", null)->get();
        // $paymentIds = $payments->pluck('student_id')->toArray();
        // $users = User::with("student")->whereHas("student", function ($res) use ($paymentIds) {
        //     $res->where("traineeState", "!=", "privateState")
        //         ->where('level', '>', '1')
        //         ->where("published", false)
        //         ->whereIn("id", $paymentIds);
        // })->get();

        try {
            $users = User::with("student.orders")->whereHas("student", function ($res) {
                $res->where("traineeState", "!=", "privateState")
                    ->where('level', ">", '1')
                    ->whereHas("orders", function ($res) {
                        $res->where("transaction_id", null);
                    })
                    ->whereDoesntHave('payments', function($res){
                        $res->where('transaction_id', null);
                    });
            })->get();
            return view('manager.community.publishHoursToRayat')
            ->with(compact('users'));
            if (isset($users)) {
                return view('manager.community.publishHoursToRayat')
                    ->with(compact('users'));
            } else {
                return view('manager.community.publishHoursToRayat')
                    ->with('error', "تعذر جلب المتدربين");
            }

        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function publishToRayat(Request $request)
    {
        $studentData = $this->validate($request, [
            "national_id"        => "required|numeric",
            'hours'              => 'required',
        ]);
        try {
            $user = User::with('student.orders')->where('national_id', $studentData['national_id'])->first();
            switch ($user->student->traineeState) {
                case 'employee':
                    $discount = 0.25;
                    break;
                case 'employeeSon':
                    $discount = 0.5;
                    break;
                case 'privateState':
                    $discount = 0.0;
                    break;
                default:
                    $discount = 1.0;
                    break;
            }
            $order = $user->student->orders->where("transaction_id", null)->first();

            if ($order->requested_hours < $studentData['hours']) {
                return response(['message' => 'لا يمكن ادخال ساعات اكثر من الساعات في الطلب'], 422);
            }

            $amountAfterEdit = $studentData['hours'] * 550 * $discount;
            $amountbeforeEdit = $order->amount * 550 * $discount;

            DB::beginTransaction();
                $transaction = $user->student->transactions()->create([
                    "order_id"      => $order->id,
                    "amount"        => $amountAfterEdit,
                    "type"          => "deduction",
                    "by_user"       => Auth::user()->id,
                ]);
                $order->update([
                    "transaction_id" => $transaction->id,
                    "requested_hours" => $studentData['hours'],
                ]);
                
                if($amountAfterEdit != $amountbeforeEdit){
                    $user->student->transactions()->create([
                        "amount"        => $amountbeforeEdit - $amountAfterEdit,
                        "type"          => "recharge",
                        "by_user"       => Auth::user()->id,
                        "note"          => "رصيد مسترد",
                    ]);
                }

                $user->student->credit_hours += $studentData['hours'];
                $user->student->wallet -= $amountAfterEdit;
                $user->student->save();
            DB::commit();
            return response(['message' => 'تم رفع الساعات بنجاح'], 200);
        } catch (Exception $e) {
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


    public function studentsStates()
    {
        $users = User::with('student')->get();
        return view('manager.community.studentsStates')
            ->with(compact('users'));
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
}
