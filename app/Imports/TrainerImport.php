<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Trainer;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

define('EMPLOYEE_ID', 0);
define('NAME', 1);
define('EMAIL', 3);
define('PHONE', 4);

class TrainerImport implements ToCollection
{
    use Importable;


    public function collection(Collection $rows)
    {

        $rows = $rows->slice(1);
        $duplicate = [];
        $errorsArr = [];

        if (!isset($rows[1][EMPLOYEE_ID]) || !isset($rows[1][NAME])) {
            return redirect(route('trainerImportForm'))->with('error', 'تعذر الحصول على الاسم او رقم الهوية يرجى التآكد من صحة الملف');
        } elseif (strlen((string) $rows[1][EMPLOYEE_ID]) < 3  || !is_numeric($rows[1][EMPLOYEE_ID]) || strlen((string) $rows[1][NAME]) < 10) {
            return redirect(route('trainerImportForm'))->with('error', ' تعذر الحصول على الاسم او رقم الهوية يرجى التآكد من صحة الملف');
        }

        // Validator::make($rows->toArray(), [
        //     '*.' . EMPLOYEE_ID => 'required|digits_between:3,12',
        //     '*.' . NAME => 'required|string|max:100',
        //     '*.' . PHONE => 'required|digits_between:9,14',
        //     '*.' . EMAIL => 'required|email',

        // ])->validate();


        foreach ($rows as $row) {
            try {
                Validator::make($row->toArray(), [
                    EMPLOYEE_ID => 'required|digits_between:3,12',
                    NAME => 'required|string|max:100',
                    PHONE => 'required',
                    EMAIL => 'required|email',
                ])->validate();
            } catch (Exception $e) {
                continue;
            }
            try {
                $userinfo = [
                    'national_id'   => $row[EMPLOYEE_ID],
                    'name'          => $row[NAME],
                    'email'         => $row[EMAIL],
                    'phone'         => $row[PHONE],
                    'password'      => Hash::make("bct12345")
                ];
                DB::beginTransaction();
                $user = User::create($userinfo);
                $user->trainer()->create([
                    'user_id' => $user->id,
                    'bct_id'  => $row[EMPLOYEE_ID],
                ]);
                DB::commit();
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[0] == "23000" || $e->errorInfo[1] == "1062") {
                    array_push($duplicate, $userinfo);
                } else {
                    array_push($errorsArr, ['code' => $e->getCode(), 'userinfo' => $userinfo]);
                }
               continue;
            }
        }
        $countOfUsers = count($rows);
        $addedCount = count($rows) - (count($duplicate) + count($errorsArr));
        if (count($duplicate) > 0  && count($errorsArr) > 0) {
            return redirect(route('trainerImportForm'))->with([
                // 'error' => ' تم أضافة جميع المتدربين بنجاح, ماعدا المتدربين التالية بياناتهم ',
                'duplicate' => $duplicate,
                'errorsArr' => $errorsArr,
                'addedCount' => $addedCount,
            ]);
        }

        if (count($duplicate) > 0) {
            return redirect(route('trainerImportForm'))->with([
                'duplicate' => $duplicate,
                'addedCount' => $addedCount,
                'countOfUsers' => $countOfUsers
            ]);
        }

        if (count($errorsArr) > 0) {
            return redirect(route('trainerImportForm'))->with([
                // 'error' => ' حدثت الاخطاء التالية اثناء اضافة المتدربين ',
                'errorsArr' => $errorsArr,
                'addedCount' => $addedCount,
                'countOfUsers' => $countOfUsers
            ]);
        }

        return redirect(route('trainerImportForm'))->with('success', 'تم اضافة ' . $addedCount . ' متدرب بنجاح ');
    }
}
