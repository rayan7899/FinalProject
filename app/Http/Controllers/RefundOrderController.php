<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\RefundOrder;
use App\Models\Semester;
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
        // return back()->with(['error' => 'سيكون طلب الاسترداد متاح عندما يتم اعتماد الساعات']);
        $user = Auth::user();
        if ($user->student->credit_hours <= 0 && $user->student->wallet <= 0) {
            return back()->with(['error' => 'لا يوجد ساعات معتمدة ولا رصيد لاسترداده']);
        }
        $isHasActiveRefund = $user->student->refunds()->where('accepted', null)->first() !== null;
        $isHasActivePayment = $user->student->payments()->where('accepted', null)->first() !== null;
        $isHasActiveOrder = $user->student->orders()->where('transaction_id', null)->first() !== null;
        if ($isHasActiveRefund) {
            return back()->with(['error' => 'لا يمكن طلب استرداد مع وجود طلب استرداد اخر قيد المراجعة']);
        } else if ($isHasActivePayment) {
            return back()->with(['error' => 'لا يمكن طلب استرداد مع وجود دفع معلق']);
        } else if ($isHasActiveOrder) {
            return back()->with(['error' => 'لا يمكن طلب استرداد مع وجود طلب اضافة مقررات قيد المراجعة']);
        } else {
            $semester = Semester::latest()->first();
            $orders = $user->student->orders()->where('semester_id', $semester->id)->where('requested_hours', '>', 0)->get();
            return view('student/refund_order')->with(compact('user', 'orders'));
        }
    }

    public function store(Request $request)
    {
        $semester = Semester::latest()->first();
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

        if (in_array($requestData['reason'], ['graduate', 'get-wallet-amount']) && $requestData['refund_to'] == 'wallet') {
            return back()->with(['error' => 'خطآ غير معروف']);
        }

        try {
            $user = Auth::user();
            $semester = Semester::latest()->first();
            $orders = $user->student->orders()->where('semester_id', $semester->id)->where('requested_hours', '>', 0)->get();
            if ($user->student->wallet <= 0 && $requestData['reason'] == 'graduate') {
                return back()->with(['error' => 'خطآ غير معروف']);
            }

            $isHasActiveRefund = $user->student->refunds()->where('accepted', null)->first() !== null;
            if ($isHasActiveRefund) {
                return redirect(route('home'))->with(['error' => 'خطآ غير معروف']);
            }

            // switch ($user->student->traineeState) {
            //     case 'privateState':
            //         $discount = 0; // = %100 discount
            //         break;
            //     case 'employee':
            //         $discount = 0.25; // = %75 discount
            //         break;
            //     case 'employeeSon':
            //         $discount = 0.5; // = %50 discount
            //         break;
            //     default:
            //         $discount = 1; // = %0 discount
            // }

            $creditHoursCost = 0;
            foreach ($orders as $order) {
                if ($order->amount / $order->requested_hours == 0) { //private state
                    $creditHourCost = 0;
                } elseif (in_array($order->amount / $order->requested_hours, [550, 400])) { //defualt state
                    $creditHourCost = $order->student->program->hourPrice;
                } elseif (in_array($order->amount / $order->requested_hours, [275, 200])) { //employee's son state
                    $creditHourCost = $order->student->program->hourPrice * 0.5;
                } elseif (in_array($order->amount / $order->requested_hours, [137.5, 100])) { //employee state
                    $creditHourCost = $order->student->program->hourPrice * 0.25;
                } else {
                    return response(json_encode(['message' => 'خطأ غير معروف']), 422);
                }
                $creditHoursCost += $creditHourCost*$order->requested_hours;
            }

            // $creditHoursCost = $user->student->credit_hours * $user->student->program->hourPrice * $discount;

            $amount = 0;
            if (in_array($requestData['reason'], ['drop-out', 'not-opened-class', 'exception'])) {
                if ($user->student->credit_hours <= 0) {
                    return back()->with(['error' => 'لا يوجد ساعات معتمدة لاسترداد مبلغها']);
                }
                $amount = $requestData['refund_to'] == 'bank'
                    ? $creditHoursCost + $user->student->wallet
                    : $creditHoursCost;
            } else if (in_array($requestData['reason'], ['graduate', 'get-wallet-amount'])) {
                $amount = $user->student->wallet;
            } else {
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
                "semester_id"   => $semester->id,


            ]);
            DB::commit();

            return redirect(route('home'))->with(['success' => 'تم ارسال طلب الاسترداد بنجاح']);
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollBack();
            return back()->with(['error' => 'خطآ غير معروف']);
        }
    }
}
