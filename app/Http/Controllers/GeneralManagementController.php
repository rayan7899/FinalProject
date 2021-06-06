<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Semester;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GeneralManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $title = "الإدارة العامة";
        $links = [
            (object) [
                "name" => "تقرير طلبات الشحن",
                "url" => route("paymentsReport")
            ],
        ];
        return view("manager.community.dashboard")->with(compact("links", "title"));
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
            $payments = Payment::with(["student.user", "student.program", "student.department", "student.major", "transaction"])->where("accepted", $cond, null)->get();
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
}
