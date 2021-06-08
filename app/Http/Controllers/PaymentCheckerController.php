<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Semester;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentCheckerController extends Controller
{
    public function dashboard()
    {
        $title = "مدقق ايصالات";
        $links = [
            (object) [
                "name" => "تدقيق الايصالات",
                "url" => route("checkerPaymentsReviewForm")
            ],
        ];
        return view("manager.paymentChecker.dashboard")->with(compact("links", "title"));
    }


    public function checkerPaymentsReviewForm()
    {
        return view('manager.paymentChecker.checkerPaymentsReview');
    }

    public function checkerPaymentsReport()
    {
        return view('manager.community.paymentsReport');
    }

    public function checkerPaymentsReviewJson($type)
    {
        // $fetch_errors = [];
        try {
            $cond = "=";
            if ($type == 'report') {
                $cond = "!=";
            }
            $payments = Payment::with(["student.user", "student.program", "student.department", "student.major", "transactions"])
            ->where("accepted", "!=", null)->where("checker_decision", null)->get();
            return response()->json(["data" => $payments->toArray()], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return view('manager.community.paymentsReview')->with('error', "تعذر جلب المتدربين");
        }
    }

    public function checkerPaymentsReviewUpdate(Request $request)
    {
        $reviewedPayment = $this->validate($request, [
            "national_id"        => "required|numeric",
            "payment_id"         => "required|numeric|exists:payments,id",
            "amount"             => "required|numeric",
            "decision"           => "required|in:accept,reject",
            "note"               => "required|string"
        ]);

        try {
            $decision = false;
            if ($reviewedPayment["decision"] == "accept") {
                $decision = true;
            }
            $user = User::with('student')->where('national_id', $reviewedPayment['national_id'])->first();
            $payment = Payment::where("id", $reviewedPayment["payment_id"])
                ->where("student_id", $user->student->id)->first() ?? null;
            if ($payment == null) {
                return response(json_encode(['message' => 'خطأ غير معروف']), 422);
            } else if ($payment->checker_decision !== null) {
                return response(['message' => "تمت معالجة هذا الطلب من قبل"], 422);
            }
            $payment->update([
                "checker_decision"       => $decision,
                "checker_note"          => $reviewedPayment["note"],
                "manager_id"            => Auth::user()->manager->id,
            ]);
            return response(json_encode(['message' => 'تمت معالجة الطلب بنجاح']), 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return response(json_encode(['message' => 'حدث خطأ غير معروف' . $e->getCode()]), 422);
        }
    }


    public function checkerPaymentsReviewVerifiyDocs(Request $request)
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


            $user = User::with('student')->where('national_id', $reviewedPayment['national_id'])->first();
            $payment = Payment::where("id", $reviewedPayment["payment_id"])
                ->where("student_id", $user->student->id)->first() ?? null;
                if ($payment == null) {
                    return response(json_encode(['message' => 'خطأ غير معروف']), 422);
                } else if ($payment->checker_decision !== null) {
                    return response(['message' => "تمت معالجة هذا الطلب من قبل"], 422);
                }

            $payment->update([
                "checker_decision"       => $decision,
                "manager_id"             => Auth::user()->manager->id
            ]);

            return response(json_encode(['message' => 'تمت معالجة الطلب بنجاح']), 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return response(json_encode(['message' => 'حدث خطأ غير معروف' . $e->getCode()]), 422);
        }
    }
}
