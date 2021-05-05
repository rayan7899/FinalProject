<?php

namespace App\Http\Controllers;

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
        if ($waitingPaymentssCount > 0) {
            return redirect(route("home"))->with("error", "تعذر ارسال الطلب يوجد طلب اضافة مقررات او شحن رصيد تحت المراجعة");
        }
        return view("student.wallet.payment");
    }

    public function store(Request $request)
    {
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
            $doc_name =  $randomId .'.'. $paymentRequest['payment_receipt']->getClientOriginalExtension();
            $user->student->payments()->create(
                [
                    "amount"            => $paymentRequest["amount"],
                    "receipt_file_id"   => $doc_name
                ]
            );
            Storage::disk('studentDocuments')->put('/' . $user->national_id . '/receipts/' . $doc_name, File::get($paymentRequest['payment_receipt']));
            DB::commit();
            return redirect(route("home"))->with("success", "تم ارسال الطلب بنجاح");
        } catch (Exception $e) {
           Log::error($e->getMessage().' '.$e);
            DB::rollBack();
            return redirect(route("home"))->with("error", "تعذر ارسال الطلب حدث خطا غير معروف");
        }
    }
}
