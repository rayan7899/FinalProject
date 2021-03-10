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
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Validation\ValidationException as Vali;

class OldUsers implements ToCollection
{
    use Importable;

    static $program = 0;
    static $department = 1;
    static $major = 2;
    static $national_id = 3;
    static $name = 4;
    static $rayat_id = 5;
    static $traineeState = 6;
    static $wallet = 10;
    static $documents_verified = 11;
    static $phone = 17;
    static $note = 19;
    static $receipt_url = 20;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function collection(Collection $rows)
    {
        // // $baseurl = "https://drive.google.com/uc?id=";
        // // $suff = "&export=download";
        // // $url = $users[10][20];
        // //  $parseUrl = parse_url($url);
        // //  $query = $parseUrl['query'];
        // //  parse_str($query, $queryParams); 
        // // need $queryParams['id']
        // //$newUrl = $baseurl . queryParams['id'] . $suff;

        // Validator::make($rows->toArray(), [
        //     '*.' . $this::$national_id  => 'required|digits:10',
        //     '*.' . $this::$name         => 'required|string|max:100',
        //     '*.' . $this::$program      => 'required|string|max:100',
        //     '*.' . $this::$department   => 'required|string|max:100',
        //     '*.' . $this::$major        => 'required|string|max:100',
        //     '*.' . $this::$rayat_id     => 'required|digits_between:9,10',
        //     '*.' . $this::$traineeState => 'nullable|string|max:20',
        //     '*.' . $this::$wallet       => 'required|numeric',
        //     '*.' . $this::$documents_verified => "required|boolean",
        //     "*." . $this::$note         => "nullable|string",
        //     '*.' . $this::$phone        => 'required|digits_between:9,14',
        //     '*.' . $this::$receipt_url  => 'nullable|string',



        // ], [
        //     '*.' . $this::$national_id . '.digits'  => ' يجب ان يكون رقم الهوية 10 ارقام',
        //     '*.' . $this::$name . '.max' => 'يجب ان لا يتجاوز الاسم 255 حرف',
        //     '*.' . $this::$phone . '.digits_between' => 'يجب ان يكون رقم الجوال بين 10 و 14 رقماَ',
        // ])->validate();

        $programs =  Program::with('departments.majors')->get(['id', 'name']);
        $rows = $rows->slice(1)->toArray();
        $duplicate = [];
        $errorsArr = [];

        foreach ($rows as $row) {
            $progId = 0;
            $deptId = 0;
            $mjrId = 0;
            $messages = [];
            $userinfo = [
                'national_id'   => $row[$this::$national_id],
                'name'          => $row[$this::$name],
                'email'         => NULL,
                'phone'         => $row[$this::$phone],
                'password' => Hash::make("bct12345")
            ];
            if ($row[$this::$documents_verified] === "TRUE" || $row[$this::$documents_verified] === "true" || $row[$this::$documents_verified] === "1") {
                $row[$this::$documents_verified] = true;
            } else {
                $row[$this::$documents_verified] = false;
            }
           

            switch ($row[$this::$traineeState]) {
                case "منسوب":
                    $row[$this::$traineeState] = "employee";
                    break;
                case "ابن منسوب":
                    $row[$this::$traineeState]  = "employeeSon";
                    break;
                case "لديه اعفاء":
                    $row[$this::$traineeState]  = "privateState";
                    break;
                default:
                    $row[$this::$traineeState]  = "trainee";
            }



            $progSplit = trim($row[$this::$program]);
            foreach ($programs as $key => $prog) {

                if (stristr($prog['name'], $progSplit) === false) {
                    //echo "not found";
                } else {
                    $progId = $prog['id'];
                    $progKey = $key;
                }
            }
            if ($progId != 0) {
                $deptSplit = trim($row[$this::$department]);
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
                $mjrExplodeDash = explode("-", $row[$this::$major]);
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
                // dump($userinfo);
                DB::beginTransaction();
                $user = User::create($userinfo);
                $student = $user->student()->create([
                    'user_id'               => $user->id,
                    'program_id'            => $progId,
                    'department_id'         => $deptId,
                    'major_id'              => $mjrId,
                    'documents_verified'    => $row[$this::$documents_verified],
                    'traineeState'          => $row[$this::$traineeState],
                    'wallet'                => $row[$this::$wallet],
                    'note'                  => $row[$this::$note],
                    'data_updated'          => true,
                    'agreement'             => true

                ]);
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                //   dump($e);
                if ($e->errorInfo[0] == "23000" && $e->errorInfo[1] == "1062") {
                    array_push($duplicate, $userinfo);
                } else {
                    array_push($errorsArr, ['message' => $e->getCode(), 'userinfo' => $userinfo]);
                }

                continue;
            }
        }


        $countOfUsers = count($rows);
        $addedCount = count($rows) - (count($duplicate) + count($errorsArr));
        if (count($duplicate) > 0  && count($errorsArr) > 0) {
            return redirect(route('OldForm'))->with([
                'error' => ' تم أضافة جميع المتدربين بنجاح, ماعدا المتدربين التالية بياناتهم ',
                'duplicate' => $duplicate,
                'errorsArr' => $errorsArr,
                'addedCount' => $addedCount,
            ]);
        }

        if (count($duplicate) > 0) {
            return redirect(route('OldForm'))->with([
                'duplicate' => $duplicate,
                'addedCount' => $addedCount,
                'countOfUsers' => $countOfUsers
            ]);
        }

        if (count($errorsArr) > 0) {
            return redirect(route('OldForm'))->with([
                'error' => ' حدثت الاخطاء التالية اثناء اضافة المتدربين ',
                'errorsArr' => $errorsArr,
                'addedCount' => $addedCount,
                'countOfUsers' => $countOfUsers
            ]);
        }
        return redirect(route('AddExcelForm'))->with('success', 'تم اضافة ' . $addedCount . ' متدرب بنجاح ');









        // $countOfUsers = count($rows);
        // $addedCount = count($rows) - (count($duplicate) + count($errorsArr));
        // if (count($duplicate) > 0  && count($errorsArr) > 0) {
        //     throw  new ValidationException(new Vali(true),[
        //         'error' => ' تم أضافة جميع المتدربين بنجاح, ماعدا المتدربين التالية بياناتهم ',
        //         'duplicate' => $duplicate,
        //         'errorsArr' => $errorsArr,
        //         'addedCount' => $addedCount,
        //     ]);

        // }

        // if (count($duplicate) > 0) {
        //     throw  new ValidationException(new Vali(true),[
        //         'duplicate' => $duplicate,
        //         'addedCount' => $addedCount,
        //         'countOfUsers' => $countOfUsers
        //     ]);

        // }

        // if (count($errorsArr) > 0) {
        //     throw  new ValidationException(new Vali(true),[
        //         'error' => ' حدثت الاخطاء التالية اثناء اضافة المتدربين ',
        //         'errorsArr' => $errorsArr,
        //         'addedCount' => $addedCount,
        //         'countOfUsers' => $countOfUsers
        //     ]);

        // }
    }
}
