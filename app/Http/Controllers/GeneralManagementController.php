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
                "name" => "تدقيق الايصالات",
                "url" => route("generalPaymentsReviewForm")
            ],
        ];
        return view("manager.community.dashboard")->with(compact("links", "title"));
    }


    public function generalPaymentsReviewForm()
    {
        return view('manager.generalManagement.generalPaymentsReview');
    }

    public function generalPaymentsReport()
    {
        return view('manager.community.paymentsReport');
    }

    public function generalPaymentsReviewJson($type)
    {
        // $fetch_errors = [];
        try {
            $cond = "=";
            if ($type == 'report') {
                $cond = "!=";
            }
            $payments = Payment::with(["student.user", "student.program", "student.department", "student.major", "transactions"])
            ->where("accepted", true)->where("checker_decision", true)->where("management_decision", null)->get();
            return response()->json(["data" => $payments->toArray()], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return response()->json(["error" => 'تعذر جلب البيانات خطأ غير معروف'], 200);
        }
    }

    public function generalPaymentsReviewUpdate(Request $request)
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
            $payment = Payment::where("id", $reviewedPayment["payment_id"])
                ->where("student_id", $user->student->id)->first() ?? null;
            if ($payment == null) {
                return response(json_encode(['message' => 'خطأ غير معروف']), 422);
            } else if ($payment->management_decision !== null) {
                return response(['message' => "تمت معالجة هذا الطلب من قبل"], 422);
            }
            $payment->update([
                "management_decision"       => $decision,
                "management_note"          => $reviewedPayment["note"],
                "manager_id"            => Auth::user()->manager->id,
            ]);
            return response(json_encode(['message' => 'تمت معالجة الطلب بنجاح']), 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return response(json_encode(['message' => 'حدث خطأ غير معروف' . $e->getCode()]), 422);
        }
    }


    public function generalPaymentsReviewVerifiyDocs(Request $request)
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
                } else if ($payment->management_decision !== null) {
                    return response(['message' => "تمت معالجة هذا الطلب من قبل"], 422);
                }

            $payment->update([
                "management_decision"       => $decision,
                "manager_id"             => Auth::user()->manager->id
            ]);

            return response(json_encode(['message' => 'تمت معالجة الطلب بنجاح']), 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return response(json_encode(['message' => 'حدث خطأ غير معروف' . $e->getCode()]), 422);
        }
    }
}
