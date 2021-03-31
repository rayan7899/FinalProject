<?php

namespace App\Imports;

use App\Models\Student;
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

define('NATIONAL_ID', 18);
define('NAME', 19);;
define('PHONE', 7);
define('BIRTHDATE', 17);
define('HAS_IMPORTED_DOCS', 14);


class UsersImport implements ToCollection
{
    use Importable;

    static $deptMjr = null;



    public function __construct($deptMjr)
    {
        $this::$deptMjr = $deptMjr;
    }




    public function onError(\Throwable $e)
    {
        return back()->with('error', ' حدث خطأ غير معروف ');
    }



    public function collection(Collection $rows)
    {
        $rows = $rows->slice(7);
        $duplicate = [];
        $errorsArr = [];

        Validator::make($rows->toArray(), [
            '*.' . NATIONAL_ID => 'required|digits:10',
            '*.' . NAME => 'required|string|max:100', 
            '*.' . BIRTHDATE => 'required|digits:4', 
            '*.' . PHONE => 'required|digits_between:9,14',
            '*.' . HAS_IMPORTED_DOCS => 'required|string|max:255',

        ], [
            '*.' . BIRTHDATE . '.digits'           => 'يجب ان ان يكون تاريخ الميلاد 4 ارقام',
            '*.' . NATIONAL_ID . '.digits'           => ' يجب ان يكون رقم الهوية 10 ارقام',
            '*.' . NAME . '.max'              => 'يجب ان لا يتجاوز الاسم 255 حرف',
            '*.' . PHONE . '.digits_between'    => 'يجب ان يكون رقم الجوال بين 10 و 14 رقماَ',
        ])->validate();
        foreach ($rows as $row) {
            $studentDocsVerified = false;
            if(trim($row[HAS_IMPORTED_DOCS]) == "نعم")
            {
                $studentDocsVerified = true;
            }
            try {
                $userinfo = [
                    'national_id'   => $row[NATIONAL_ID],
                    'name'          => $row[NAME],
                    'email'         => NULL,
                    'phone'         => $row[PHONE],
                    'password' => Hash::make("bct12345")
                ];
                DB::beginTransaction();
                $user = User::create($userinfo);
                $user->student()->create([
                    'user_id'               => $user->id,
                    'birthdate'             => $row[BIRTHDATE],
                    'program_id'            => $this::$deptMjr['program'],
                    'department_id'         => $this::$deptMjr['department'],
                    'major_id'              => $this::$deptMjr['major'],
                    'student_docs_verified' => $studentDocsVerified,
                    'has_imported_docs'     => $row[HAS_IMPORTED_DOCS],
                    
                ]);
                DB::commit();
            } catch (QueryException $e) {
                Log::error($e);
                DB::rollback();

                if ($e->errorInfo[0] == "23000" && $e->errorInfo[1] == "1062") {
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
            return redirect(route('AddExcelForm'))->with([
                'error' => ' تم أضافة جميع المتدربين بنجاح, ماعدا المتدربين التالية بياناتهم ',
                'duplicate' => $duplicate,
                'errorsArr' => $errorsArr,
                'addedCount' => $addedCount,
            ]);
        }

        if (count($duplicate) > 0) {
            return redirect(route('AddExcelForm'))->with([
                'duplicate' => $duplicate,
                'addedCount' => $addedCount,
                'countOfUsers' => $countOfUsers
            ]);
        }

        if (count($errorsArr) > 0) {
            return redirect(route('AddExcelForm'))->with([
                'error' => ' حدثت الاخطاء التالية اثناء اضافة المتدربين ',
                'errorsArr' => $errorsArr,
                'addedCount' => $addedCount,
                'countOfUsers' => $countOfUsers
            ]);
        }
        return redirect(route('AddExcelForm'))->with('success', 'تم اضافة ' . $addedCount . ' متدرب بنجاح ');
    }
}
