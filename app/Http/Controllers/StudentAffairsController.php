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
        $title = "شؤون المتدربين";
        $links = [
            (object) [
                "name" => "القبول النهائي",
                "url" => route("finalAcceptedForm")
            ],
            (object) [
                "name" => "تقرير القبول النهائي",
                "url" => route("finalAcceptedReport")
            ],
            (object) [
                "name" => "الرفع لرايات",
                "url" => route("publishToRayatFormAffairs", ["type" => "affairs"])
            ],
            (object) [
                "name" => "تقرير رايات",
                "url" => route("rayatReportFormAffairs", ["type" => "affairs"])
            ],
            (object) [
                "name" => "الجداول المقترحة",
                "url" => route("coursesPerLevel")
            ],
            (object) [
                "name" => " تقرير المتدربين المستجدين",
                "url" => route("NewStudents",["type" => "Affairs"])
            ],


            (object) [
                "name" => "اضافة اكسل مستجدين",
                "url" => route("AddExcelForm")
            ],

            (object) [
                "name" => "اضافة الرقم التدريبي للمستجدين",
                "url" => route("addRayatIdForm")
            ],

            // (object) [
            //     "name" => "شحن محفظة متدرب",
            //     "url" => route("chargeForm")
            // ],

            // (object) [
            //     "name" => "متابعة حالات المتدربين",
            //     "url" => route("studentsStates")
            // ],
        ];
        return view("manager.studentsAffairs.dashboard")->with(compact("links", "title"));
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
                    ->where('data_updated', true)
                    ->whereHas('orders', function ($res) {
                        $res->where('transaction_id', null)
                            ->where('private_doc_verified', true);
                    });
            })->get();

            $usersCount = count($users);
            for ($i = 0; $i < $usersCount; $i++) {
                for ($j = 0; $j < count($users[$i]->student->orders); $j++) {
                    if ($users[$i]->student->traineeState != "privateState") {
                        $order = $users[$i]->student->orders[$j];
                        if ($order->transaction_id == null && $users[$i]->student->wallet < $order->amount) {
                            unset($users[$i]);
                            break;
                        }
                    }
                }
            }

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
    public function finalAcceptedJson()
    {
        try {
            $users = User::with(['student', 'student.program', 'student.department', 'student.major'])->whereHas('student', function ($result) {
                $result->where('level', 1)
                    ->where('data_updated', true)
                    ->whereHas('orders', function ($res) {
                        $res->where('transaction_id', null)
                            ->where('private_doc_verified', true);
                    });
            })->get();

            $usersCount = count($users);
            for ($i = 0; $i < $usersCount; $i++) {
                for ($j = 0; $j < count($users[$i]->student->orders); $j++) {
                    if ($users[$i]->student->traineeState != "privateState") {
                        $order = $users[$i]->student->orders[$j];
                        if ($order->transaction_id == null && $users[$i]->student->wallet < $order->amount) {
                            unset($users[$i]);
                            break;
                        }
                    }
                }
            }

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
            return response()->json(["data" => $users->toArray()], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
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
            Log::error($e->getMessage() . ' ' . $e);
            return response(json_encode(['message' => 'حدث خطأ غير معروف' . $e->getCode()]), 422);
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

    public function finalAcceptedReport()
    {
        return view('manager.studentsAffairs.studentFinalAcceptedReport');
    }

    public function finalAcceptedReportJson()
    {
        try {
            $users = User::with(['student', 'student.program', 'student.department', 'student.major'])->whereHas('student', function ($result) {
                $result->where('final_accepted', true)
                    ->where('student_docs_verified', true)
                    ->where("level", 1);
            })->get();
            return response()->json(["data" => $users->toArray()], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return view('manager.studentsAffairs.studentFinalAcceptedReport')->with('error', "تعذر جلب المتدربين");
        }
    }
}
