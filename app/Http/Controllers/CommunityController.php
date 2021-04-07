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
                "name" => "انشاء مستخدم",
                "url" => route("createUser")
            ],
        ];
        return view("manager.community.dashboard")->with(compact("links"));
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
}
