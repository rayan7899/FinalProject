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
        if ($user->student->credit_hours <= 0 && $user->student->wallet <= 0) {
            return back()->with(['error'=>'لا يوجد ساعات معتمدة ولا رصيد لاسترداده']);
        }
        $isHasActiveRefund = $user->student->refunds->where('accepted', null)->first() !== null;
        $isHasActivePayment = $user->student->payments->where('accepted', null)->first() !== null;
        $isHasActiveOrder = $user->student->orders->where('transaction_id', null)->first() !== null;
        
        if($isHasActiveRefund){
            return back()->with(['error'=>'لا يمكن طلب استرداد مع وجود طلب استرداد اخر قيد المراجعة']);
        }else if($isHasActivePayment){
            return back()->with(['error'=>'لا يمكن طلب استرداد مع وجود دفع معلق']);
        }else if($isHasActiveOrder){
            return back()->with(['error'=>'لا يمكن طلب استرداد مع وجود طلب اضافة مقررات قيد المراجعة']);
        }
        else{
            return view('student/refund_order')->with(compact('user'));
        }
    }

    public function store(Request $request)
    {
        $requestData = $this->validate($request, [
            "reason"           => "required|in:drop-out,not-opened-class,exception,graduate,get-wallet-amount",
            "refund_to"        => "required|in:bank,wallet",
            "IBAN"             => "required|digits:22",
            "bank"             => "required|string",
            "note"             => "string|nullable"
        ], [
            'IBAN.digits' => 'رقم الايبان غير صحيح',
            'IBAN.required' => 'رقم الايبان مطلوب',
            'bank.required' => 'اسم البنك مطلوب',
            'reason.required' => 'يجبب تحديد سبب الاسترداد',
            'reason.in' => 'سبب الاسترداد غير معروف',
            ]);

        if(in_array($requestData['reason'], ['graduate', 'get-wallet-amount']) && $requestData['refund_to'] == 'wallet'){
            return back()->with(['error' => 'خطآ غير معروف']);
        }

        try {
            $user = Auth::user();
            if ($user->student->wallet <= 0 && $requestData['reason'] == 'graduate') {
                return back()->with(['error' => 'خطآ غير معروف']);
            }

            switch ($user->student->traineeState) {
                case 'privateState':
                    $discount = 0; // = %100 discount
                    break;
                case 'employee':
                    $discount = 0.25; // = %75 discount
                    break;
                case 'employeeSon':
                    $discount = 0.5; // = %50 discount
                    break;
                default:
                    $discount = 1; // = %0 discount
            }
            
            $creditHoursCost = $user->student->credit_hours*$user->student->program->hourPrice*$discount;

            $amount = 0;
            if(in_array($requestData['reason'], ['drop-out', 'not-opened-class', 'exception'])){
                $amount = $requestData['refund_to'] == 'bank' 
                    ? $creditHoursCost + $user->student->wallet 
                    : $creditHoursCost;
            }else if(in_array($requestData['reason'], ['graduate', 'get-wallet-amount'])){
                $amount = $user->student->wallet;
            }else{
                return back()->with(['error' => 'خطآ غير معروف']);
            }

            DB::beginTransaction();
                $user->student->refunds()->create([
                    'amount'        => $amount,
                    'reason'        => $requestData['reason'],
                    'refund_to'     => $requestData['refund_to'],
                    'IBAN'          => $requestData['IBAN'],
                    'bank'          => $requestData['bank'],
                    'student_note'  => $requestData['note'],
                ]);
            DB::commit();

            return redirect(route('home'))->with(['success'=>'تم ارسال طلب الاسترداد بنجاح']);
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollBack();
            return back()->with(['error' => 'خطآ غير معروف']);
        }
    }
}
