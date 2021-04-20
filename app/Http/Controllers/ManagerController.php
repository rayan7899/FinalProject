<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ManagerController extends Controller
{
    //
    public function chargeForm()
    {
        return view('manager.charge_wallet');
    }

    public function charge(Request $request)
    {
        $paymentRequest = $this->validate($request, [
            "amount"            => "required|numeric",
            "id"            => 'required|string|max:10|min:10',
        ]);
        try {
            $user = User::with('student.courses')->whereHas('student', function ($result) use ($paymentRequest) {
                $result->where('national_id', $paymentRequest['id'])->orWhere('rayat_id', $paymentRequest['id']);
            })->first();
            if (!isset($user)) {
                return back()->with("error","لا يوجد متدرب بهذا الرقم");
            }
            DB::beginTransaction();
                $user->student->transactions()->create([
                    "amount"        => $paymentRequest["amount"],
                    "note"          => 'تمت الاضافة عن طريق الادارة',
                    "type"          => "excepted_recharge",
                    "by_user"       => Auth::user()->id,
                ]);

                $user->student->wallet += $paymentRequest["amount"];
                $user->student->save();

            DB::commit();
            return back()->with("success","تم اضافة المبلغ في محفظة المتدرب بنجاح");
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return back()->with("error","تعذر ارسال الطلب حدث خطا غير معروف");
           
        }
    }
}
