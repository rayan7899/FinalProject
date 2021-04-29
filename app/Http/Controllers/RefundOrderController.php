<?php

namespace App\Http\Controllers;

use App\Models\RefundOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RefundOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function form()
    {
        $user = Auth::user();
        $isHasActiveRefund = $user->student->refunds->where('accepted', null)->first() !== null;
        $isHasActivePayment = $user->student->payments->where('accepted', null)->first() !== null;
        $isHasActiveOrder = $user->student->orders->where('transaction_id', null)->first() !== null;
        
        if($isHasActiveRefund){
            return back()->with(['error'=>'لا يمكن طلب استرداد مع وجود طلب استرداد اخر معلق']);
        }else if($isHasActivePayment){
            return back()->with(['error'=>'لا يمكن طلب استرداد مع وجود دفع معلق']);
        }else if($isHasActiveOrder){
            return back()->with(['error'=>'لا يمكن طلب استرداد مع وجود طلب اضافة مقررات معلق']);
        }
        else{
            return view('student/refund_order')->with(compact('user'));
        }
    }

    public function store(Request $request)
    {
        $requestData = $this->validate($request, [
            "reason"           => "required|in:drop-out,exception,graduate",
            "IBAN"             => "required|digits:22",
            "bank"             => "required|string",
            "note"               => "string|nullable"
         ], [
            'IBAN.digits' => 'رقم الايبان غير صحيح',
            'reason.required' => 'يجبب تحديد سبب الاسترداد',
            'reason.in' => 'سبب الاسترداد غير معروف',
         ]);

        try {
            DB::beginTransaction();
                $user = Auth::user();
                $user->student->refunds()->create([
                    'amount'    => $user->student->wallet,
                    'reason'    => $requestData['reason'],
                    'IBAN'  => $requestData['IBAN'],
                    'bank'  => $requestData['bank'],
                    'note'  => $requestData['note'],
                ]);
            DB::commit();

            return redirect(route('home'))->with(['success'=>'تم ارسال طلب الاسترداد بنجاح']);
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollBack();
            return back()->with(['error' => $th]);
        }
    }
}
