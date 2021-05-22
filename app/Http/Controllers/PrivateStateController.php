<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Semester;
use App\Models\StudentCourse;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrivateStateController extends Controller
{

    public function privateDashboard()
    {
        $title = "الارشاد";
        $links = [
            (object) [
                "name" => "تدقيق المستندات",
                "url" => route("PrivateAllStudentsForm")
            ],
            (object) [
                "name" => "تقرير الطلبات المدققة",
                "url" => route("PrivateStudentsReport")
            ],
            // (object) [
            //     "name" => "متابعة حالات المتدربين",
            //     "url" => route("studentsStates")
            // ],
        ];
        return view("manager.private.dashboard")->with(compact("links", "title"));
    }


    public function privateAllStudentsForm()
    {
        $users = User::with('student.orders')->whereHas('student', function ($result) {
            $result->where('traineeState', 'privateState')
                ->whereHas('orders', function ($result) {
                    $result->where('private_doc_verified', null);
                });
        })->get();

        foreach ($users as $user) {
            foreach ($user->student->orders as $order) {
                if ($order->private_doc_verified == null) {
                    $user->student->order = $order;
                    break;
                }
            }
        }

        for ($i = 0; $i < count($users); $i++) {
            $users[$i]->student->docs = Storage::disk('studentDocuments')->files($users[$i]->national_id . '/privateStateDocs/' . $users[$i]->student->order->private_doc_file_id);
        }

        return view('manager.private.private_student')->with(compact('users'));
    }

    public function privateStudentsReport()
    {
        $orders = Order::where("private_doc_verified", '!=', null)
            ->where("private_doc_file_id", '!=', null)->get();

        for ($i = 0; $i < count($orders); $i++) {
            $orders[$i]->student->docs = Storage::disk('studentDocuments')->files($orders[$i]->student->user->national_id . '/privateStateDocs/' . $orders[$i]->private_doc_file_id);
        }
        return view('manager.private.private_student_report')->with(compact('orders'));
    }





    public function privateDocDecision(Request $request)
    {
        $reviewedOrder = $this->validate($request, [
            "national_id"        => "required|numeric",
            "order_id"           => "required|numeric|exists:orders,id",
            "decision"           => "required|in:accept,reject",
            "note"               => "string|nullable"

        ]);

        $decision = false;
        if ($reviewedOrder["decision"] == "accept") {
            $decision = true;
        }
        try {
            DB::beginTransaction();
            $user = User::with('student')->where('national_id', $reviewedOrder['national_id'])->first();
            $order = Order::where("id", $reviewedOrder["order_id"])->where("student_id", $user->student->id)->first();
            // if($decision == false){
            //   $user->student->studentCourses()->delete();
            // }
            $order->update([
                "private_doc_verified" => $decision,
                "note"                 => $reviewedOrder["note"],
            ]);

            DB::commit();
            return response(json_encode(['message' => 'تم ارسال الطلب بنجاح']), 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            DB::rollBack();
            return response(json_encode(['message' => 'حدث خطأ غير معروف' . $e->getCode()]), 422);
        }
    }
}
