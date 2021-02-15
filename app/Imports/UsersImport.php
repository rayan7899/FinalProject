<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\SkipsErrors;
// use Maatwebsite\Excel\Concerns\SkipsOnError;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Maatwebsite\Excel\Concerns\WithValidation;
// use Illuminate\Validation\Rule;
// use Maatwebsite\Excel\Concerns\ToModel;


class UsersImport implements ToCollection
{

    static $national_id = 18;
    static $name = 19;
    static $birthdate = 17;
    static $phone = 7;

    static $deptMjr = null;
    public function __construct($deptMjr)
    {
        $this::$deptMjr = $deptMjr;
    }



    use Importable;
    public function onError(\Throwable $e)
    {

        if ($e->errorInfo[0] == "23000" && $e->errorInfo[1] == "1062") {
            return back()->with('error', 'خطأ, يوجد تكرار في البيانات, واحد او اكثر من المستخدمين تم اضافته مسبقاً');
        }
        return back()->with('error', ' حدث خطأ غير معروف ' . $e->getMessage());
    }


    
    public function collection(Collection $rows)
    {

        $rows = $rows->slice(7);

        Validator::make($rows->toArray(), [
            '*.' . $this::$national_id => 'required|digits:10',      //national_id
            '*.' . $this::$name => 'required|string|max:100',  //name
            '*.' . $this::$birthdate => 'required|digits:4',        //birthdate
            '*.' . $this::$phone => 'required|digits:10',      //phone

        ])->validate();

        foreach ($rows as $row) {
            try {
                User::create([
                    'national_id'   => $row[$this::$national_id],
                    'name'          => $row[$this::$name],
                    'birthdate'     => $row[$this::$birthdate],
                    'program_id'    => $this::$deptMjr['program'],
                    'department_id'    => $this::$deptMjr['department'],
                    'major_id'         => $this::$deptMjr['major'],
                    'email'         => NULL,
                    'phone'         => $row[$this::$phone],
                    'password' => Hash::make("bct12345")
                ]);
            } catch (\Throwable $e) {

                if ($e->errorInfo[0] == "23000" && $e->errorInfo[1] == "1062") {
                    return back()->with('error', 'خطأ, يوجد تكرار في البيانات, واحد او اكثر من المستخدمين تم اضافته مسبقاً');
                }
                return back()->with('error', ' حدث خطأ غير معروف ' . $e->getMessage());
            }
        }
    }
}
