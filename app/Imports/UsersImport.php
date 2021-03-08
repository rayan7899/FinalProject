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
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;


class UsersImport implements ToCollection
{
    use Importable;

    static $national_id = 18;
    static $name = 19;
    static $birthdate = 17;
    static $phone = 7;
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

        Validator::make($rows->toArray(), [
            '*.' . $this::$national_id => 'required|digits:10',      //national_id
            '*.' . $this::$name => 'required|string|max:100',  //name
            '*.' . $this::$birthdate => 'required|digits:4',        //birthdate
            '*.' . $this::$phone => 'required|digits:10',      //phone

        ])->validate();


        // foreach ($rows as $row) {

        //     $user = User::where('national_id', $row[$this::$national_id])->first();
        //     if (isset($user)) {
        //         array_push($duplicate, $user->toArray());
        //     }
        // }



        foreach ($rows as $row) {
            $userinfo = [
                'national_id'   => $row[$this::$national_id],
                'name'          => $row[$this::$name],
                'email'         => NULL,
                'phone'         => $row[$this::$phone],
                'password' => Hash::make("bct12345")
            ];

            try {
                DB::beginTransaction();
                $user = User::create($userinfo);
                $user->student()->create([
                    'user_id'          => $user->id,
                    'birthdate'        => $row[$this::$birthdate],
                    'program_id'       => $this::$deptMjr['program'],
                    'department_id'    => $this::$deptMjr['department'],
                    'major_id'         => $this::$deptMjr['major'],
                ]);
                DB::commit();
            } catch (QueryException $e) {
                DB::rollback();

                if ($e->errorInfo[0] == "23000" && $e->errorInfo[1] == "1062") {
                    array_push($duplicate, $userinfo);
                }
                continue;
            }
        }

        if (count($duplicate) > 0) {
            return redirect(route('AddExcelForm'))->with([
                'error' => ' تم أضافة جميع المستخدمين بنجاح, ماعدا المتدربين التالية بياناتهم بسبب اضافتهم مسبقأً ',
                'duplicate' => $duplicate,
            ]);
        }
        return redirect(route('AddExcelForm'))->with('success', 'تم أضافة المتدربين بنجاح');
    }
}
