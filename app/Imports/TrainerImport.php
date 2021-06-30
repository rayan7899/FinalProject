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

define('BCT_ID', 0);
define('NAME', 1);
define('EMPLOYER', 2);
define('EMAIL', 3);
define('PHONE', 4);

class TrainerImport implements ToCollection
{
    use Importable;
    static $type;

    public function __construct($type)
    {
        $this::$type = $type;
    }
    public function collection(Collection $rows)
    {
        $rows = $rows->slice(1);
        $duplicate = [];
        $errorsArr = [];
        $addedCount = 0;

        if (!isset($rows[1][BCT_ID]) || !isset($rows[1][NAME])) {
            return redirect(route('trainerImportForm'))->with('error', 'تعذر الحصول على الاسم او رقم الهوية يرجى التآكد من صحة الملف');
        } elseif (strlen((string) $rows[1][BCT_ID]) < 3  || !is_numeric($rows[1][BCT_ID]) || strlen((string) $rows[1][NAME]) < 10) {
            return redirect(route('trainerImportForm'))->with('error', ' تعذر الحصول على الاسم او رقم الهوية يرجى التآكد من صحة الملف');
        }

        // Validator::make($rows->toArray(), [
        //     '*.' . BCT_ID => 'required|digits_between:3,12',
        //     '*.' . NAME => 'required|string|max:100',
        //     '*.' . PHONE => 'required|digits_between:9,14',
        //     '*.' . EMAIL => 'required|email',

        // ])->validate();

        if ($this::$type == 'trainer') {
            $tableName = "trainers";
        } elseif ($this::$type == 'employee') {
            $tableName = "employees";
        } else {
            return redirect(route('trainerImportForm'))->with('error', ' نوع المستخدمين غير معروف');
        }

        foreach ($rows as $row) {
            if ($row[BCT_ID] == null && $row[NAME] == null) {
                continue;
            }

            $replaceKeys['bct_id']      = isset($row[BCT_ID])  ? trim($row[BCT_ID]) : 'لا يوجد';
            $replaceKeys['name']        = isset($row[NAME])  ? $row[NAME] : 'لا يوجد';
            $replaceKeys['employer']    = isset($row[EMPLOYER])  ? $row[EMPLOYER] : 'لا يوجد';
            $replaceKeys['phone']       = isset($row[PHONE])  ? trim($row[PHONE]) : null;
            $replaceKeys['email']       = isset($row[EMAIL])  ? trim($row[EMAIL]) : 'لا يوجد';

            try {
                Validator::make($replaceKeys, [
                    'bct_id' => 'required|digits_between:3,12|unique:' . $tableName . ',bct_id',
                    'name'        => 'required|string|max:100',
                    'employer'    => 'required|string|max:100',
                    'phone'       => 'nullable|digits_between:9,14|unique:users,phone',
                    'email'       => 'required|email',
                ])->validate();
            } catch (Exception $e) {
                $errMessages = "";
                if (isset($e->validator)) {
                    foreach ($e->validator->messages()->messages() as $mArr) {
                        foreach ($mArr as $m) {
                            $errMessages .= $m . ' ';
                        }
                    }
                }
                array_push($errorsArr, [
                    'userinfo' => [
                        'national_id'   => $row[BCT_ID],
                        'name'          => $row[NAME],
                        'code' => $e->getMessage(),
                        'messages' => $errMessages,
                    ]
                ]);
                continue;
            }
            try {
                $userinfo = [
                    'national_id'   => $row[BCT_ID],
                    'name'          => $row[NAME],
                    'email'         => $row[EMAIL],
                    'phone'         => $row[PHONE],
                    'password'      => Hash::make("bct12345")
                ];
                DB::beginTransaction();
                $user = User::create($userinfo);
                if ($this::$type == 'trainer') {
                    $user->trainer()->create([
                        'user_id' => $user->id,
                        'bct_id'  => $row[BCT_ID],
                        'employer'  => $row[EMPLOYER],
                    ]);
                } elseif ($this::$type == 'employee') {
                    $user->employee()->create([
                        'user_id' => $user->id,
                        'bct_id'  => $row[BCT_ID],
                        'employer'  => $row[EMPLOYER],

                    ]);
                } else {
                    return redirect(route('trainerImportForm'))->with('error', ' نوع المستخدمين غير معروف');
                }
                $addedCount++;
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
        if (count($duplicate) > 0  && count($errorsArr) > 0) {
            return redirect(route('trainerImportForm'))->with([
                // 'error' => ' تم أضافة جميع المستخدمين بنجاح, ماعدا المستخدمين التالية بياناتهم ',
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
                // 'error' => ' حدثت الاخطاء التالية اثناء اضافة المستخدمين ',
                'errorsArr' => $errorsArr,
                'addedCount' => $addedCount,
                'countOfUsers' => $countOfUsers
            ]);
        }

        return redirect(route('trainerImportForm'))->with('success', 'تم اضافة ' . $addedCount . ' مستخدم بنجاح ');
    }
}
