<?php

namespace App\Imports;

use App\Models\Order;
use App\Models\Semester;
use App\Models\Student;
use App\Models\User;
use Exception;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

define('NATIONAL_ID', 8);
define('NAME', 12);
define('CREDIT_HOURS', 3);


class UpdateCreditHours implements ToCollection
{
    use Importable;

    public function collection(Collection $rows)
    {
        $semester = Semester::latest()->first();
        if ($semester == null) {
            return back()->with('error', ' يرجى انشاء فصل تدريبي');
        }
        $rows = $rows->slice(1);

        $errorsArr = [];
        $restoreInfo = [];
        $addInfo = [];
        $updatedCount = 0;
        $restoreCount = 0;
        $addCount = 0;
        $notRegesterd = 0;
        $equal        = 0;

        $countOfStudents = count($rows);

        if (!isset($rows[1][NATIONAL_ID]) || !isset($rows[1][NAME])) {
            return redirect(route('UpdateCreditHoursForm'))->with('error', 'تعذر الحصول على الاسم او رقم الهوية يرجى التآكد من صحة الملف');
        } elseif (strlen((string) $rows[1][NATIONAL_ID]) < 10  || !is_numeric($rows[1][NATIONAL_ID]) || strlen((string) $rows[1][NAME]) < 10) {
            return redirect(route('UpdateCreditHoursForm'))->with('error', ' تعذر الحصول على الاسم او رقم الهوية يرجى التآكد من صحة الملف');
        }

        foreach ($rows->toArray() as $row) {
            try {
                $replaceKeys['national_id']     = isset($row[NATIONAL_ID])  ? $row[NATIONAL_ID] : 'لا يوجد';
                $replaceKeys['name']            = isset($row[NAME])  ? $row[NAME] : 'لا يوجد';
                $replaceKeys['credit_hours']    = isset($row[CREDIT_HOURS])  ? $row[CREDIT_HOURS] : 'لا يوجد';


                Validator::make($replaceKeys, [
                    "national_id"   => 'required|digits:10',
                    "name"          => 'required|string|max:100',
                    "credit_hours"  => 'required|numeric',
                ], [
                    'national_id.digits'        => '  يجب ان يكون رقم الهوية 10 ارقام',
                    'name.max'                  => ' يجب ان لا يتجاوز الاسم 255 حرف',
                    'credit_hours.required'     => '   الساعات المعتمدة حقل مطلوب',
                    'credit_hours.numeric'      => '  يجب ان يكون حقل الساعات المعتمدة رقماً',

                ])->validate();

                try {
                    $user = User::where('national_id', $row[NATIONAL_ID])->first() ?? null;
                    if ($user == null) {
                        array_push($errorsArr, ['message' => 'لا يوجد متدرب حسب البيانات المدخلة', 'userinfo' => $replaceKeys]);
                        continue;
                    } elseif ($user->student->credit_hours > 0) {
                        array_push($errorsArr, ['message' => 'تم تحديث الساعات المعتمدة مسبقاً', 'userinfo' => $replaceKeys]);
                        continue;
                    } else {
                        $updatedCount++;
                    }

                    if ($user->student->orders()->where("semester_id", $semester->id)->count() == 0 && $row[CREDIT_HOURS] == 0) {
                        $notRegesterd++;
                        continue;
                    } elseif ($user->student->available_hours == $row[CREDIT_HOURS]) {
                        $user->student->credit_hours = $row[CREDIT_HOURS];
                        $user->student->available_hours = 0;
                        $user->student->save();
                        $equal++;
                        continue;
                    }

                    $resoteHours = $user->student->available_hours - $row[CREDIT_HOURS];
                    if (
                        $resoteHours > $user->student->orders()
                        ->where("transaction_id", '!=', null)
                        ->where("semester_id", $semester->id)
                        ->sum("requested_hours")
                    ) {
                        array_push($errorsArr, ['message' => 'عدد الساعات المستردة اكبر من عدد الساعات المدفوعة', 'userinfo' => $replaceKeys]);
                        $updatedCount--;
                        continue;
                    }
                } catch (Exception $ex) {
                    array_push($errorsArr, ['message' => 'خطأ غير معروف', 'userinfo' => $replaceKeys]);
                    continue;
                }
                DB::beginTransaction();
                $updateInfo = [
                    "national_id"  => $user->national_id,
                    "name"         => $user->name,
                    "hours"        => $resoteHours > 0 ? $resoteHours : abs($resoteHours),
                    "amount"       => 0,
                    "traineeState" => 'لا يوجد',
                    "creditHours"  => $row[CREDIT_HOURS],
                ];
                if ($resoteHours > 0) {
                    $decreaseHours = 0;
                    while ($resoteHours != 0) {
                        $order = $user->student->orders()
                            ->where("transaction_id", '!=', null)
                            ->where("semester_id", $semester->id)
                            ->where("requested_hours", ">=", $resoteHours - $decreaseHours)->latest()->first();
                        if ($order == null) {
                            $decreaseHours++;
                        } else {
                            $data = self::editOrder([
                                'order' => $order,
                                'newHours' => $order->requested_hours - ($resoteHours - $decreaseHours),
                                'note'     => "اعادة احتساب المبلغ حسب الساعات المعتمدة"
                            ]);
                            if ($data == false) {
                                array_push($errorsArr, ['message' => 'خطأ غير معروف', 'userinfo' => $replaceKeys]);
                                DB::rollBack();
                                break;
                            }
                            $resoteHours = $decreaseHours;
                            $decreaseHours = 0;
                            $updateInfo['amount'] += $data['amount'];
                            $updateInfo['traineeState']  = $data['traineeState'];
                        }
                    }
                    array_push($restoreInfo, $updateInfo);
                    $restoreCount++;
                } elseif ($resoteHours < 0) {
                    $data = self::createOrder($user, abs($resoteHours));
                    if ($data == false) {
                        array_push($errorsArr, ['message' => 'خطأ غير معروف', 'userinfo' => $replaceKeys]);
                        DB::rollBack();
                        continue;
                    }
                    $updateInfo['amount'] += $data['amount'];
                    $updateInfo['traineeState']  = $data['traineeState'];
                    array_push($addInfo, $updateInfo);
                    $addCount++;
                }
                $user->student->credit_hours = $row[CREDIT_HOURS];
                $user->student->available_hours = 0;
                $user->student->save();
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                array_push($errorsArr, ['message' => "خطأ غير معروف", 'userinfo' => $replaceKeys]);
                continue;
            }
        }


        return redirect(route('UpdateCreditHoursForm'))->with([
            'errorsArr' => count($errorsArr) > 0 ? $errorsArr : null,
            'restoreInfo' => count($restoreInfo) > 0 ? $restoreInfo : null,
            'addInfo' => count($addInfo) > 0 ? $addInfo : null,
            'addCount' => $addCount,
            'restoreCount' => $restoreCount,
            'equal' => $equal,
            'notRegesterd' => $notRegesterd,
            'countOfStudents' => $countOfStudents,
            'updatedCount' => $updatedCount,
        ]);


        // return redirect(route('UpdateCreditHoursForm'))->with('success', 'تم تحديث الساعات المعتمدة لـ  ' . $updatedCount . ' متدرب بنجاح ');
    }

    // ################################################# end of excel import function #################################################

    public static function editOrder(array $orderData)
    {

        try {
            $semester = Semester::latest()->first();
            $order = $orderData['order'];
            if ($order->requested_hours == $orderData['newHours']) {
                return false;
            } elseif ($orderData['newHours'] < 0) {
                return false;
            } elseif ($order->requested_hours == 0) {
                return false;
            }
            if ($order->amount / $order->requested_hours == 0) { //private state
                $hourCost = 0;
                $traineeState = "ظروف خاصة";
            } elseif (in_array($order->amount / $order->requested_hours, [550, 400])) { //defualt state
                $hourCost = $order->student->program->hourPrice;
                $traineeState = "متدرب";
            } elseif (in_array($order->amount / $order->requested_hours, [275, 200])) { //employee's son state
                $hourCost = $order->student->program->hourPrice * 0.5;
                $traineeState = "ابن منسوب";
            } elseif (in_array($order->amount / $order->requested_hours, [137.5, 100])) { //employee state
                $hourCost = $order->student->program->hourPrice * 0.25;
                $traineeState = "منسوب";
            } else {
                return false;
            }
            DB::beginTransaction();
            if ($orderData['newHours'] > $order->requested_hours) {
                //increase hours
                $diffCost = ($orderData['newHours'] - $order->requested_hours) * $hourCost;
                $type = 'editOrder-deduction';
                $order->student->wallet -= $diffCost;
            } else {
                // decrease hours
                $diffCost = ($order->requested_hours - $orderData['newHours']) * $hourCost;
                $type = 'editOrder-charge';
                $order->student->wallet += $diffCost;
            }
            $order->student->save();
            $transaction = $order->student->transactions()->create([
                "order_id"      => $order->id,
                "amount"        => $diffCost,
                "type"          => $type,
                "manager_id"    => Auth::user()->manager->id,
                "semester_id"   => $semester->id,
                "note"          => $orderData["note"] ?? null,
            ]);
            $order->update([
                "amount"            => $orderData['newHours'] * $hourCost,
                "requested_hours"   => $orderData['newHours'],
                "discount"          => $orderData['newHours'] * $order->student->program->hourPrice - $orderData['newHours'] * $hourCost,
                "transaction_id"    => $transaction->id,
                "note"              => "تم تغيير عدد الساعات من " . $order->requested_hours . " إلى " . $orderData['newHours'],
            ]);
            DB::commit();
            return ['type' => $type, 'amount' => $diffCost, 'traineeState' => $traineeState];
        } catch (Exception $e) {
           
            DB::rollBack();
            return false;
        }
    }

    public static function createOrder(User $user, $hours)
    {

        try {
            $semester = Semester::latest()->first();
            $order = $user->student->orders()
                ->where("transaction_id", '!=', null)
                ->where("requested_hours", '>', 0)
                ->where("semester_id", $semester->id)->latest()->first();
            if ($order == null) {
                $hourCost = $user->student->program->hourPrice;
                $traineeState = "متدرب";
            } else {
                if ($order->amount / $order->requested_hours == 0) { //private state
                    $hourCost = 0;
                $traineeState = "ظروف خاصة";
                } elseif (in_array($order->amount / $order->requested_hours, [550, 400])) { //defualt state
                    $hourCost = $order->student->program->hourPrice;
                $traineeState = "متدرب";
                } elseif (in_array($order->amount / $order->requested_hours, [275, 200])) { //employee's son state
                    $hourCost = $order->student->program->hourPrice * 0.5;
                $traineeState = "ابن منسوب";
                } elseif (in_array($order->amount / $order->requested_hours, [137.5, 100])) { //employee state
                    $hourCost = $order->student->program->hourPrice * 0.25;
                $traineeState = "منسوب";
                } else {
                    return false;
                }
            }
            $amount = $hours * $hourCost;
            $discountAmount = $hours * $user->student->program->hourPrice - $hours * $hourCost;
        } catch (Exception $e) {
           
            return false;
        }
        try {
            DB::beginTransaction();
            $newOrder = $user->student->orders()->create([
                "amount"                => $amount,
                "discount"              => $discountAmount,
                "requested_hours"       => $hours,
                "private_doc_verified"  => true,
                "semester_id"           => $semester->id,
            ]);

            $transaction = $user->student->transactions()->create([
                "order_id"    => $newOrder->id,
                "amount"        => $amount,
                "type"          => "deduction",
                "manager_id"       => Auth::user()->manager->id,
                "semester_id"   => $semester->id,

            ]);
            $newOrder->transaction_id = $transaction->id;
            $newOrder->save();
            $user->student->wallet -= $amount;
            $user->student->save();
            DB::commit();
            return ['amount' => $amount, 'traineeState' => $traineeState];
        } catch (Exception $e) {
           
            DB::rollBack();
            return false;
        }
    }
}
