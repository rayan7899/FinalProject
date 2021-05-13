<?php

namespace App\Imports;

use App\Models\Semester;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

define('NATIONAL_ID', 0);
define('NAME', 1);;
define('RAYAT_ID', 2);
define('WALLET', 3);


class UpdateStudentsWallet implements ToCollection
{
    use Importable;

    public function collection(Collection $rows)
    {
        $semester = Semester::latest()->first();
        $rows = $rows->slice(1);
        $errorsArr = [];
        $updatedCount = 0;
        $countOfStudents = count($rows);

        if (!isset($rows[1][NATIONAL_ID]) || !isset($rows[1][NAME])) {
            return redirect(route('UpdateStudentsWalletForm'))->with('error', 'تعذر الحصول على الاسم او رقم الهوية يرجى التآكد من صحة الملف');
        } elseif (strlen((string) $rows[1][NATIONAL_ID]) < 10  || !is_numeric($rows[1][NATIONAL_ID]) || strlen((string) $rows[1][NAME]) < 10) {
            return redirect(route('UpdateStudentsWalletForm'))->with('error', ' تعذر الحصول على الاسم او رقم الهوية يرجى التآكد من صحة الملف');
        }


        foreach ($rows->toArray() as $row) {
            try {
                $replaceKeys['national_id'] = isset($row[NATIONAL_ID])  ? $row[NATIONAL_ID] : 'لا يوجد';
                $replaceKeys['name']        = isset($row[NAME])  ? $row[NAME] : 'لا يوجد';
                $replaceKeys['rayat_id']    = isset($row[RAYAT_ID])  ? $row[RAYAT_ID] : 'لا يوجد';
                $replaceKeys['wallet']      = isset($row[WALLET])  ? $row[WALLET] : 'لا يوجد';


                Validator::make($replaceKeys, [
                    "national_id"  => 'required|digits:10',
                    "name"         => 'required|string|max:100',
                    "wallet"       => 'required:traineState|numeric',
                ], [
                    'national_id.digits'   => '  يجب ان يكون رقم الهوية 10 ارقام',
                    'name.max'             => ' يجب ان لا يتجاوز الاسم 255 حرف',
                    'wallet.required'      => '   الفائض / العجز (المحفظة) حقل مطلوب',
                    'wallet.numeric'      => '  يجب ان يكون حقل الفائض / العجز (المحفظة) رقماً',

                    ])->validate();

                   
                    $user = User::where('national_id',$row[NATIONAL_ID])->first() ?? null;
                    if($user == null){
                        array_push($errorsArr, ['message' => 'لا يوجد متدرب حسب البيانات المدخلة', 'userinfo' => $replaceKeys]);
                        continue;
                    }elseif($user->student->walletUpdated){
                        array_push($errorsArr, ['message' => 'تم تحديث المحفظة مسبقاً', 'userinfo' => $replaceKeys]);
                        continue;
                    }
                    DB::beginTransaction();
                    $user->student->transactions()->create([
                    "amount"        => $row[WALLET],
                    "type"          => "manager_recharge",
                    "note"          => "رصيد سابق",
                    "by_user"       => Auth::user()->id,
                    "semester_id"   => $semester->id,
                ]);
                $user->student->wallet += $row[WALLET];
                $user->student->walletUpdated = true;
                $user->student->save();
                DB::commit();
                $updatedCount++;
            } catch (Exception $e) {
                DB::rollBack();
                if (isset($e->validator)) {
                    array_push($errorsArr, ['message' => implode(", ", $e->validator->errors()->all()), 'userinfo' => $replaceKeys]);
                } else {
                    array_push($errorsArr, ['message' =>  $e->getMessage(), 'userinfo' => $replaceKeys]);
                }
                continue;
            }
        }
        if (count($errorsArr) > 0) {
            return redirect(route('UpdateStudentsWalletForm'))->with([
                'errorsArr' => $errorsArr,
                'updatedCount' => $updatedCount,
                'countOfStudents' => $countOfStudents
            ]);
        }

        return redirect(route('UpdateStudentsWalletForm'))->with('success', 'تم تحديث المحفظة لـ  ' . $updatedCount . ' متدرب بنجاح ');

    }
}
