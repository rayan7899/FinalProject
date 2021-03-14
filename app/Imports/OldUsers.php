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
// use Maatwebsite\Excel\Validators\ValidationException;
// use Illuminate\Validation\ValidationException as Vali;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

define('PROGRAM', 0);
define('DEPARTMENT', 1);
define('MAJOR', 2);
define('NATIONAL_ID', 3);
define('NAME', 4);
define('RAYAT_ID', 5);
define('TRAINEE_STATE', 6);
define('WALLET', 10);
define('DOCUMENTS_VERIFIED', 11);
define('PHONE', 17);
define('NOTE', 19);
define('RECEIPT_URL', 20);

class OldUsers implements ToCollection
{
    use Importable;

    /**
     * @param string $url
     *
     * @return "url"
     */

    public static function getImgUrl($url)
    {
        $url = trim($url);
        $url = htmlspecialchars($url);
        $baseurl = "https://drive.google.com/uc?id=";
        $suff = "&export=download";
        $parseUrl = parse_url($url);
        $query = $parseUrl['query'];
        parse_str($query, $queryParams);
        $newUrl = $baseurl . $queryParams['id'] . $suff;
        return $newUrl;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {

     
        Validator::make($rows->slice(1)->toArray(), [
            '*.' . NATIONAL_ID  => 'required|digits:10',
            '*.' . NAME         => 'required|string|max:100',
            '*.' . PROGRAM      => 'required|string|max:100',
            '*.' . DEPARTMENT   => 'required|string|max:100',
            '*.' . MAJOR        => 'required|string|max:100',
            '*.' . RAYAT_ID    => 'required|digits_between:9,10',
            '*.' . TRAINEE_STATE => 'nullable|string|max:20',
            '*.' . WALLET       => 'required|numeric',
            '*.' . DOCUMENTS_VERIFIED => "required|in:TRUE,FALSE",
            "*." . NOTE         => "nullable|string",
            '*.' . PHONE        => 'required|digits_between:9,14',
            '*.' . RECEIPT_URL => 'nullable|string',

        ], [
            '*.' . NATIONAL_ID . '.digits'  => ' يجب ان يكون رقم الهوية 10 ارقام',
            '*.' . NAME . '.max' => 'يجب ان لا يتجاوز الاسم 255 حرف',
            '*.' . PHONE . '.digits_between' => 'يجب ان يكون رقم الجوال بين 10 و 14 رقماَ',
        ])->validate();

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
                'national_id'   => $row[NATIONAL_ID],
                'name'          => $row[NAME],
                'email'         => NULL,
                'phone'         => $row[PHONE],
                'password' => Hash::make("bct12345")
            ];
            if (Storage::disk('studentDocuments')->exists($row[NATIONAL_ID])) {
                array_push($duplicate, $userinfo);
                continue;
            }
            switch ($row[TRAINEE_STATE]) {
                case "منسوب":
                    $row[TRAINEE_STATE] = "employee";
                    break;
                case "ابن منسوب":
                    $row[TRAINEE_STATE]  = "employeeSon";
                    break;
                case "لديه اعفاء":
                    $row[TRAINEE_STATE]  = "privateState";
                    break;
                default:
                    $row[TRAINEE_STATE]  = "trainee";
            }

            if ($row[DOCUMENTS_VERIFIED] === "TRUE" || $row[DOCUMENTS_VERIFIED] === "true" || $row[DOCUMENTS_VERIFIED] === "1") {
                $row[DOCUMENTS_VERIFIED] = true;
            } else {
                $row[DOCUMENTS_VERIFIED] = false;
            }



            // try {
            //     $success = false;
            //     if (filter_var($row[RECEIPT_URL], FILTER_VALIDATE_URL)) {
            //         $file = file_get_contents($this::getImgUrl($row[RECEIPT_URL]));
            //         $fileInfo  = getimagesizefromstring($file);
            //         if ($fileInfo !== false) {
            //             $fileExt = explode('/', $fileInfo['mime'])[1];
            //             if ($fileExt == 'jpeg' || $fileExt == 'png') {
            //                 if ($row[TRAINEE_STATE] != 'privateState') {
            //                     $doc_name =  date('Y-m-d-H-i') . '_payment_receipt.' . $fileExt;
            //                     Storage::disk('studentDocuments')->put('/' . $row[NATIONAL_ID] . '/receipts/' . $doc_name, $file);
            //                     $success = true;
            //                 } else {
            //                     $doc_name =  date('Y-m-d-H-i') . '_privateStateDoc.' . $fileExt;
            //                     Storage::disk('studentDocuments')->put('/' . $row[NATIONAL_ID] . '/privateStateDoc/' . $doc_name, $file);
            //                     $success = true;
            //                 }
            //             }
            //         }
            //     }else{
            //         $success = true;
            //     }
            //     if (!$success) {
            //         array_push($errorsArr, ['message' => "تعذر تحميل صورة الايصال", 'userinfo' => $userinfo]);
            //         continue;
            //     }
            // } catch (Exception $e) {
            //     array_push($errorsArr, ['message' => "تعذر تحميل صورة الايصال", 'userinfo' => $userinfo]);
            //     continue;
            // }

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
                $deptSplit = trim($row[DEPARTMENT]);
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
                $mjrExplodeDash = explode("-", $row[MAJOR]);
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
                $user->student()->create([
                    'user_id'               => $user->id,
                    'program_id'            => $progId,
                    'department_id'         => $deptId,
                    'major_id'              => $mjrId,
                    'documents_verified'    => $row[DOCUMENTS_VERIFIED],
                    'traineeState'          => $row[TRAINEE_STATE],
                    'wallet'                => $row[WALLET],
                    'note'                  => $row[NOTE],
                    'data_updated'          => true,
                    'agreement'             => true

                ]);
                DB::commit();
            } catch (QueryException $e) {
                DB::rollback();
                //   dump($e);
                if ($e->errorInfo[0] == "23000" || $e->errorInfo[1] == "1062") {
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
        return redirect(route('OldForm'))->with('success', 'تم اضافة ' . $addedCount . ' متدرب بنجاح ');



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
