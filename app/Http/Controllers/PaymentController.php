<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Semester;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function main()
    {
        $user = Auth::user();
        return view("student.wallet.main")->with(compact('user'));
    }


    public function form()
    {
        $user = Auth::user();
        $waitingPaymentssCount = $user->student->payments()->where("accepted", null)->count();
        $waitingOrdersCount = $user->student->orders()->where("transaction_id", null)->count();
        $isHasActiveRefund = $user->student->refunds()->where('accepted', null)->first() !== null;
        if ($waitingPaymentssCount > 0) {
            return redirect(route("home"))->with("error", "تعذر ارسال الطلب يوجد طلب اضافة مقررات او شحن رصيد تحت المراجعة");
        } elseif ($isHasActiveRefund) {
            return redirect(route("home"))->with("error", "تعذر ارسال الطلب يوجد طلب استرداد تحت المراجعة");
        } elseif($waitingOrdersCount == 0){
            return redirect(route("home"))->with("error", "لا يمكن شحن المحفظة في الوقت الحالي, لدفع الرسوم استخدم ايقونة اضافة المقررات");
        }
        return view("student.wallet.payment");
    }

    public function store(Request $request)
    {
        $semester = Semester::latest()->first();
        $user = Auth::user();
        $waitingPaymentssCount = $user->student->payments()->where("accepted", null)->count();
        if ($waitingPaymentssCount > 0) {
            return redirect(route("home"))->with("error", "تعذر ارسال الطلب يوجد طلب اضافة مقررات او شحن رصيد تحت المراجعة");
        }

        $paymentRequest = $this->validate($request, [
            "amount"            => "required|numeric|min:0|max:50000",
            "payment_receipt"   => "required|mimes:pdf,png,jpg,jpeg|max:4000",
        ]);

        try {
            DB::beginTransaction();
            $randomId =  uniqid();

            // $doc_name =  date('Y-m-d-H-i') . '_payment_receipt.' . $paymentRequest['payment_receipt']->getClientOriginalExtension();
            $doc_name =  $randomId . '.' . $paymentRequest['payment_receipt']->getClientOriginalExtension();
            $user->student->payments()->create(
                [
                    "amount"            => $paymentRequest["amount"],
                    "receipt_file_id"   => $doc_name,
                    "semester_id"        => $semester->id,
                ]
            );
            Storage::disk('studentDocuments')->put('/' . $user->national_id . '/receipts/' . $doc_name, File::get($paymentRequest['payment_receipt']));
            DB::commit();
            return redirect(route("home"))->with("success", "تم ارسال الطلب بنجاح");
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            DB::rollBack();
            return redirect(route("home"))->with("error", "تعذر ارسال الطلب حدث خطا غير معروف");
        }
    }

    public function deletePayment(Request $request)
    {
       $requestData = $this->validate($request, [
          "payment_id"    => "required|numeric|distinct|exists:payments,id",
       ]);
       try {
          $payment = Payment::where('id', $requestData['payment_id'])->first();
          $user = Auth::user();
          if($user->id !== $payment->student->user->id && !$user->hasRole('خدمة المجتمع')){
             return response()->json(["message" => "ليس لديك صلاحيات لتنفيذ هذا الامر"], 422);
          }
          if($payment->accepted == 1 || $payment->accepted == true){
              return response()->json(["message" => "لا يمكن حذف طلب تم تدقيقه"], 422);
            }else{
              $payment->delete();
              Storage::disk('studentDocuments')->delete('/'.$payment->student->user->national_id.'/receipts/'.$payment->receipt_file_id);
          }
          return response()->json(["message" => "تم حذف الطلب بنجاح"], 200);
       } catch (Exception $e) {
          Log::error($e->getMessage() . ' ' . $e);
          return response()->json(["message" => "حدث خطأ غير معروف تعذر حذف الطلب"], 422);
       }
    }
}
