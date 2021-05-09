<?php

namespace App\Imports;

use App\Models\Program;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

define('NATIONAL_ID', 0);
define('PHONE', 1);
define('NAME', 2);
define('RAYAT_ID', 3);
define('MAJOR', 4);
define('DEPARTMENT', 5);
define('PROGRAM', 6);



class OldUsers implements ToCollection
{
    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        set_time_limit(120);
        $programs =  Program::with('departments.majors')->get(['id', 'name']);
        $duplicate = [];
        $errorsArr = [];
        $rows = $rows->slice(2);
        if (!isset($rows[2][NATIONAL_ID]) || !isset($rows[2][NAME])) {
            return redirect(route('OldForm'))->with('error', 'تعذر الحصول على الاسم او رقم الهوية يرجى التآكد من صحة الملف');
        } elseif (strlen((string) $rows[2][NATIONAL_ID]) < 10  || !is_numeric($rows[2][NATIONAL_ID]) || strlen((string) $rows[2][NAME]) < 10) {
            return redirect(route('OldForm'))->with('error', ' تعذر الحصول على الاسم او رقم الهوية يرجى التآكد من صحة الملف');
        }
        foreach ($rows->toArray() as $row) {
            if (count($errorsArr) >= 50) {
                return redirect(route('OldForm'))->with([
                    'hasOtherMessage' => true,
                    'error' => '  تم ايقاف عملية الاضافة لوجود اكثر من ٥٠ خطأ يرجى التآكد من صحة الملف',
                    'errorsArr' => $errorsArr,
                    'countOfUsers' => count($rows)
                ]);
            }

            $userinfo = [];
            try {
                $replaceKeys['national_id'] = isset($row[NATIONAL_ID])  ? $row[NATIONAL_ID] : 'لا يوجد';
                $replaceKeys['name']        = isset($row[NAME])  ? $row[NAME] : 'لا يوجد';
                $replaceKeys['program']     = isset($row[PROGRAM])  ? $row[PROGRAM] : 'لا يوجد';
                $replaceKeys['department']  = isset($row[DEPARTMENT])  ? $row[DEPARTMENT] : 'لا يوجد';
                $replaceKeys['major']       = isset($row[MAJOR])  ? $row[MAJOR] : 'لا يوجد';
                $replaceKeys['rayat_id']    = isset($row[RAYAT_ID])  ? $row[RAYAT_ID] : 'لا يوجد';
                $replaceKeys['phone']       = isset($row[PHONE])  ? $row[PHONE] : 'لا يوجد';


                Validator::make($replaceKeys, [
                    "national_id"  => 'required|digits:10',
                    "name"         => 'required|string|max:100',
                    "program"      => 'required|string|max:100',
                    "department"   => 'required|string|max:100',
                    "major"        => 'required|string|max:100',
                    "rayat_id"    => 'required|digits_between:9,10|unique:students,rayat_id',
                    "phone"        => 'required|digits_between:9,14',
                ], [
                    'national_id.digits'   => '  يجب ان يكون رقم الهوية 10 ارقام',
                    'name.max'             => ' يجب ان لا يتجاوز الاسم 255 حرف',
                    'phone.digits_between' => '  يجب ان يكون رقم الجوال بين 9 و 14 رقماَ',

                ])->validate();
            } catch (Exception $e) {
                if (isset($e->validator)) {
                    array_push($errorsArr, ['message' => implode(", ", $e->validator->errors()->all()), 'userinfo' => $replaceKeys]);
                } else {
                    array_push($errorsArr, ['message' =>  $e->getMessage(), 'userinfo' => $replaceKeys]);
                }
                continue;
            }

            $progId = 0;
            $deptId = 0;
            $mjrId = 0;
            $messages = [];
            $userinfo = [
                'national_id'   => $row[NATIONAL_ID],
                'name'          => $row[NAME],
                'email'         => NULL,
                'phone'         => $row[PHONE],
                'password' => Hash::make("bct12345")
            ];
            try {
                $user = User::where('national_id', $row[NATIONAL_ID])->exists();
                if ($user) {
                    array_push($duplicate, $userinfo);
                    continue;
                }
            } catch (Exception $e) {
                Log::error($e->getMessage() . $e);
                array_push($errorsArr, ['message' => ' خطأ غير معروف ' . $e->getCode(), 'userinfo' => $userinfo]);
                continue;
            }

            $progSplit = trim($row[PROGRAM]);
            foreach ($programs as $key => $prog) {
                if (stristr($prog['name'], $progSplit) === false) {
                    //echo "not found";
                } else {
                    $progId = $prog['id'];
                    $progKey = $key;
                }
            }
            if ($progId != 0) {
                $deptSplit = explode('-', $row[DEPARTMENT], 2)[0] ?? null;
                $deptSplit = trim($deptSplit);
                foreach ($programs[$progKey]->departments as $key => $dept) {
                    if (stristr($dept['name'], $deptSplit) === false) {
                        //echo "not found";
                    } else {
                        $deptId = $dept->id;
                        $deptKey = $key;
                    }
                }
            } else {
                array_push($messages, "خطأ في اسم البرنامج");
            }

            if ($deptId != 0) {
                $mjrExplodeDash = explode("-", $row[MAJOR])[0] ?? null;
                $mjrExplode = explode(" ", $mjrExplodeDash[0]);
                $mjrSplit = trim($mjrExplode[0]);

                foreach ($programs[$progKey]->departments[$deptKey]->majors as $key => $mjr) {
                    if (stristr(trim($mjr['name']), $mjrSplit) === false) {
                        //echo "not found";
                    } else {
                        $mjrId = $mjr->id;
                    }
                }
            } elseif ($progId != 0) {
                array_push($messages, "خطأ في اسم القسم");
            }
            if ($mjrId == 0 && $deptId != 0) {
                array_push($messages, "خطأ في اسم التخصص");
            }
            if ($progId == 0 || $deptId == 0 || $mjrId == 0) {
                array_push($errorsArr, ['message' => implode(", ", $messages), 'userinfo' => $userinfo]);
                continue;
            }

            try {
                DB::beginTransaction();
                $user = User::create($userinfo);
                $user->student()->create([
                    'user_id'               => $user->id,
                    'rayat_id'              => $row[RAYAT_ID],
                    'program_id'            => $progId,
                    'department_id'         => $deptId,
                    'major_id'              => $mjrId,
                    'traineeState'          => 'trainee',
                    'student_docs_verified' => true,
                    'has_imported_docs'     => "نعم",
                    'final_accepted'        => true,
                    'data_updated'          => false,
                    'agreement'             => false,
                    'level'                 => 2,
                ]);
                // $user->student->transactions()->create([
                //     "amount"        => $row[WALLET],
                //     "type"          => "manager_recharge",
                //     "note"          => "رصيد سابق",
                //     "by_user"       => Auth::user()->id,
                // ]);
                DB::commit();
            } catch (QueryException $e) {
                Log::error($e->getMessage() . $e);
                DB::rollback();
                try {
                    $dir = Storage::disk('studentDocuments')->exists($userinfo['national_id']);
                    if ($dir) {
                        Storage::disk('studentDocuments')->deleteDirectory($userinfo['national_id']);
                    }
                } catch (Exception $e) {
                    Log::error($e->getMessage() . $e);
                }
                if ($e->errorInfo[1] == "1062") {
                    array_push($duplicate, $userinfo);
                } else {
                    array_push($errorsArr, ['message' => $e->getCode(), 'userinfo' => $userinfo]);
                }
                continue;
            }
        }

        $countOfUsers = count($rows);
        $addedCount = count($rows) - (count($duplicate) + count($errorsArr));

        if (count($duplicate) > 0  || count($errorsArr) > 0) {
            return redirect(route('OldForm'))->with([
                // 'error' => ' تم أضافة جميع المتدربين بنجاح, ماعدا المتدربين التالية بياناتهم ',
                'duplicate' => $duplicate,
                'errorsArr' => $errorsArr,
                'addedCount' => $addedCount,
                'countOfUsers' => $countOfUsers
            ]);
        }

        // if (count($duplicate) > 0) {
        //     return redirect(route('OldForm'))->with([
        //         'duplicate' => $duplicate,
        //         'addedCount' => $addedCount,
        //         'countOfUsers' => $countOfUsers
        //     ]);
        // }

        // if (count($errorsArr) > 0) {
        //     return redirect(route('OldForm'))->with([
        //         // 'error' => '  تعذر اضافة المتدربين التالية بياناتهم',
        //         'errorsArr' => $errorsArr,
        //         'addedCount' => $addedCount,
        //         'countOfUsers' => $countOfUsers
        //     ]);
        // }
        return redirect(route('OldForm'))->with('success', 'تم اضافة ' . $addedCount . ' متدرب بنجاح ');
    }
}
