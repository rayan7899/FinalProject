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
            "reason"           => "required|in:drop-out,exception,graduate",
            "IBAN"             => "required_if:reason,graduate|digits:22",
            "bank"             => "required_if:reason,graduate|string",
            "note"             => "string|nullable"
        ], [
            'IBAN.digits' => 'رقم الايبان غير صحيح',
            'IBAN.required_if' => 'رقم الايبان مطلوب',
            'bank.required_if' => 'اسم البنك مطلوب',
            'reason.required' => 'يجبب تحديد سبب الاسترداد',
            'reason.in' => 'سبب الاسترداد غير معروف',
            ]);
            
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

            DB::beginTransaction();
                $user->student->refunds()->create([
                    'amount'    => $requestData['reason'] == 'graduate' ? $user->student->wallet : $creditHoursCost,
                    'reason'    => $requestData['reason'],
                    'IBAN'  => $requestData['IBAN'] ?? null,
                    'bank'  => $requestData['bank'] ?? null,
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
