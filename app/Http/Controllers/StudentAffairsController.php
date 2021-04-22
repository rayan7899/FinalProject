<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StudentAffairsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'errors']);
    }

    public function dashboard()
    {
        $links = [
            (object) [
                "name" => "القبول النهائي",
                "url" => route("finalAcceptedForm")
            ],
            (object) [
                "name" => "قائمة القبول النهائي",
                "url" => route("finalAcceptedList")
            ],
            (object) [
                "name" => "المتدربين المستجدين",
                "url" => route("NewStudents")
            ],

            (object) [
                "name" => "اضافة اكسل مستجدين",
                "url" => route("AddExcelForm")
            ],
            (object) [
                "name" => "اضافة اكسل مستمرين",
                "url" => route("OldForm")
            ],
            (object) [
                "name" => "الجداول المقترحة",
                "url" => route("coursesPerLevel")
            ],
            (object) [
                "name" => "شحن محفظة متدرب",
                "url" => route("chargeForm")
            ],
            (object) [
                "name" => "تقرير رايات",
                "url" => route("rayatReportForm")
            ],
            (object) [
                "name" => "الرفع لرايات",
                "url" => route("publishToRayatForm",["type" => "affairs"])
            ],
            // (object) [
            //     "name" => "متابعة حالات المتدربين",
            //     "url" => route("studentsStates")
            // ],
        ];
        return view("manager.studentsAffairs.dashboard")->with(compact("links"));
    }

    public function checkedStudents()
    {
        $users = User::with('student.payments')->whereHas('student', function ($result) {
            $result->whereHas('transactions');
        })->get();
        return view('manager.studentsAffairs.CheckedStudents')
            ->with('users', $users);
    }

    public function finalAcceptedForm()
    {
        try {
            $users = User::with('student')->whereHas('student', function ($result) {
                $result->where('level', 1)
                    ->where('data_updated', true);
            })->get();

            $fetch_errors = [];
            for ($i = 0; $i < count($users); $i++) {
                try {
                    $files = Storage::disk('studentDocuments')->files($users[$i]->national_id);
                    $users[$i]->student->identity = $files[array_key_first(preg_grep('/identity/', $files))];
                    $users[$i]->student->degree = $files[array_key_first(preg_grep('/degree/', $files))];
                } catch (Exception $err) {
                    Log::error($err);
                    array_push($fetch_errors, $users[$i]->name);
                    continue;
                }
            }
            return view('manager.studentsAffairs.studentFinalAccepted')->with(compact('users', 'fetch_errors'));
        } catch (Exception $err) {
            Log::error($err);
            return view('manager.studentsAffairs.studentFinalAccepted')->with('error', "تعذر جلب المتدربين");;
        }
    }

    public function finalAcceptedUpdate(Request $request)
    {

        $studentData = $this->validate($request, [
            "national_id"           => "required|numeric",
            "final_accepted"        => "required|boolean",
            "student_docs_verified" => "required|boolean"
        ]);

        try {
            $user = User::with('student')->where('national_id', $studentData['national_id'])->first();
            $user->student()->update([
                "final_accepted"        => $studentData['final_accepted'],
                "student_docs_verified" => $studentData['student_docs_verified'],
            ]);

            return response(json_encode(['message' => 'تم تغيير الحالة بنجاح']), 200);
        } catch (Exception $e) {
            Log::error($e);
            return response(json_encode(['message' => 'حدث خطأ غير معروف' . $e->getCode()]), 422);
        }
    }

    public function getFinalAcceptedStudents()
    {
        try {
            $users = User::with('student')->whereHas('student', function ($result) {
                $result->where('final_accepted', true)
                    ->where('student_docs_verified', true)
                    ->where("level", 1);
            })->get();
            return $users;
        } catch (Exception $e) {
            Log::error($e);
            return null;
        }
    }

    public function newStudents()
    {
        $users = User::with('student')->whereHas('student', function ($result) {
            $result->where('level', '1');
        })->get();
        if (isset($users)) {
            return view('manager.studentsAffairs.newStudents')
                ->with('users', $users);
        } else {
            return view('manager.studentsAffairs.newStudents')->with('error', "تعذر جلب المتدربين");
        }
    }

    public function finalAcceptedList()
    {
        $users = $this->getFinalAcceptedStudents();
        if (isset($users)) {
            return view('manager.studentsAffairs.studentFinalAcceptedList')
                ->with(compact('users'));
        } else {
            return view('manager.studentsAffairs.studentFinalAcceptedList')->with('error', "تعذر جلب المتدربين");;
        }
    }

    public function publishToRayatForm()
    {

        // $payments = Payment::where("transaction_id", "!=", null)->get();
        // $paymentIds = $payments->pluck('student_id')->toArray();
        // $users = User::with("student")->whereHas("student", function ($res) use ($paymentIds) {
        //         $res->where("traineeState", "!=", "privateState")
        //             ->where('level', '1')
        //             ->where('final_accepted', true)
        //             ->where("published", false)
        //             ->whereIn("id", $paymentIds);
        //     })->get();
        try {
            $users = User::with("student.orders")->whereHas("student", function ($res) {
                $res->where("traineeState", "!=", "privateState")
                    ->where('level', '1')
                    ->whereHas("orders", function ($res) {
                        $res->where("transaction_id", null);
                    })
                    ->whereDoesntHave('payments', function($res){
                        $res->where('transaction_id', null);
                    });
            })->get();

            if (isset($users)) {
                return view('manager.studentsAffairs.publishHoursToRayat')
                    ->with(compact('users'));
            } else {
                return view('manager.studentsAffairs.publishHoursToRayat')
                    ->with('error', "تعذر جلب المتدربين");
            }
        } catch (\Throwable $th) {
            return view('manager.studentsAffairs.publishHoursToRayat')
                ->with('error', $th);
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

            $amountAfterEdit = $studentData['hours'] * $user->student->program->hourPrice * $discount;
            $amountbeforeEdit = $order->amount * $user->student->program->hourPrice * $discount;

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

                $user->student->credit_hours += $studentData['hours'];
                $user->student->wallet -= $amountAfterEdit;
                $user->student->save();
            DB::commit();
            return response(['message' => 'تم رفع الساعات بنجاح'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response(['message' => 'حدث خطأ غير معروف' . $e->getCode()], 422);
        }
    }

    public function rayatReportForm()
    {
        // $payments = Payment::where("transaction_id", "!=", null)->get();
        // $paymentIds = $payments->pluck('student_id')->toArray();
        // $users = User::with("student")->whereHas("student", function ($res) use ($paymentIds) {
        //     $res->where("traineeState", "!=", "privateState")
        //         ->where('final_accepted', true)
        //         ->where('level', '1')
        //         ->where("published", true)
        //         ->whereIn("id", $paymentIds);
        // })->get();
        $users = User::with("student")->whereHas("student", function ($res) {
            $res->where('level', '1')
                ->where('credit_hours', '>', 0);
        })->get();
        if (isset($users)) {
            return view('manager.studentsAffairs.rayatReport')
                ->with(compact('users'));
        } else {
            return view('manager.studentsAffairs.rayatReport')
                ->with('error', "تعذر جلب المتدربين");
        }
    }
}
