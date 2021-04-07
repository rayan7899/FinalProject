<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            (object) [
                "name" => "المتدربين المدققة ايصالاتهم",
                "url" => route("CheckedStudents")
            ],
            (object) [
                "name" => "انشاء مستخدم",
                "url" => route("createUser")
            ],
            // (object) [
            //     "name" => "فصل دراسي جديد",
            //     "url" => route("newSemester")
            // ],
            (object) [
                "name" => "متابعة حالات المتدربين",
                "url" => route("studentsStates")
            ],
            (object) [
                "name" => "المتدربين المستمرين",
                "url" => route("oldStudentsReport")
            ],
            (object) [
                "name" => "المتدربين المستجدين",
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
            (object) [
                "name" => "متابعة حالات المتدربين",
                "url" => route("studentsStates")
            ],
        ];
        return view("manager.private.dashboard")->with(compact("links"));
    }

    public function createUser()
    {
        return view("manager.community.createUser");
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
                if ($users[$i]->id == $payments[$i]->student_id) {
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
        $studentData = $this->validate($request, [
            "national_id"        => "required|numeric",
            "wallet"             => "required|numeric",
            "documents_verified" => "required|boolean",
            "note"               => "string|nullable"
        ]);



        try {
            $user = User::with('student')->where('national_id', $studentData['national_id'])->first();
            if ($user->student->traineeState == 'privateState') {
                $user->student()->update([
                    "documents_verified" => $studentData['documents_verified'],
                    "note"               => $studentData['note'],
                ]);
            } else {
                $user->student()->update([
                    "wallet"             => $studentData['wallet'],
                    "documents_verified" => $studentData['documents_verified'],
                    "note"               => $studentData['note'],
                ]);
            }


            return response(json_encode(['message' => 'تم تحديث البيانات بنجاح']), 200);
        } catch (Exception $e) {
            return response(json_encode(['message' => 'حدث خطأ غير معروف' . $e->getCode()]), 422);
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


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\  $
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\  $
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\  $
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\  $
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }

    public function publishToRayatForm()
    {   
        $newUsers = User::with('student')->whereHas('student', function ($result) {
            $result->where('final_accepted', true)
                ->where('documents_verified', true)
                ->where('level', '>', '1');
        })->get();
        $users = [];
        foreach ($newUsers as $user) {
            if(!$user->student->published){
                array_push($users, $user);
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
        $studentData = $this->validate($request, [
            "national_id"        => "required|numeric",
            'state'              => 'required',
        ]);
        try {
            $user = User::with('student')->where('national_id', $studentData['national_id'])->first();
            $user->student()->update([
                "published" => $studentData['state'],
            ]);
            return response(['message' => 'تم تغيير الحالة بنجاح'], 200);
        } catch (Exception $e) {
            return response(['message' => 'حدث خطأ غير معروف' . $e->getCode()], 422);
        }
    }

    public function rayatReportForm()
    {
        $newUsers = User::with('student')->whereHas('student', function ($result) {
            $result->where('final_accepted', true)
                ->where('documents_verified', true)
                ->where('level', '>', '1');
        })->get();

        $users = [];
        foreach ($newUsers as $user) {
            if($user->student->published){
                array_push($users, $user);
            }
        }
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
