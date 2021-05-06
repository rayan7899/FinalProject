<?php

namespace App\Imports;

use App\Models\Program;
use App\Models\Transaction;
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
use Google_Service_Drive;
use Google_Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

define('PROGRAM', 0);
define('DEPARTMENT', 1);
define('MAJOR', 2);
define('NATIONAL_ID', 3);
define('NAME', 4);
define('RAYAT_ID', 5);
// define('TRAINEE_STATE', 6);
define('WALLET', 10);
// define('DOCUMENTS_VERIFIED', 11);
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
        $baseurl = "https://www.googleapis.com/drive/v3/files/";
        $suff = "?alt=media&key=" . env("GOOGLE_DRIVE_API");
        $parseUrl = parse_url($url);
        $query = $parseUrl['query'];
        parse_str($query, $queryParams);
        $newUrl = $baseurl . $queryParams['id'] . $suff;
        $newUrl = htmlspecialchars($newUrl);
        return $newUrl;
    }

    public static function getGDriveId($url)
    {
        $url = trim($url);
        $url = htmlspecialchars($url);
        $parseUrl = parse_url($url);
        $query = $parseUrl['query'];
        parse_str($query, $queryParams);
        return $queryParams['id'];
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        $programs =  Program::with('departments.majors')->get(['id', 'name']);
        $duplicate = [];
        $errorsArr = [];
        // $client = new Google_Client();
        // $client->useApplicationDefaultCredentials();
        // $client->addScope(Google_Service_Drive::DRIVE);
        // $service = new Google_Service_Drive($client);
        $rows = $rows->slice(1);
        if (!isset($rows[1][NATIONAL_ID]) || !isset($rows[1][NAME])) {
            return redirect(route('OldForm'))->with('error', 'تعذر الحصول على الاسم او رقم الهوية يرجى التآكد من صحة الملف');
        } elseif (strlen((string) $rows[1][NATIONAL_ID]) < 10  || !is_numeric($rows[1][NATIONAL_ID]) || strlen((string) $rows[1][NAME]) < 10) {
            return redirect(route('OldForm'))->with('error', ' تعذر الحصول على الاسم او رقم الهوية يرجى التآكد من صحة الملف');
        }
        foreach ($rows->toArray() as $row) {

            $userinfo = [];
            try {
                $replaceKeys['national_id'] = isset($row[NATIONAL_ID])  ? $row[NATIONAL_ID] : 'لا يوجد';
                $replaceKeys['name']        = isset($row[NAME])  ? $row[NAME] : 'لا يوجد';
                $replaceKeys['program']     = isset($row[PROGRAM])  ? $row[PROGRAM] : 'لا يوجد';
                $replaceKeys['department']  = isset($row[DEPARTMENT])  ? $row[DEPARTMENT] : 'لا يوجد';
                $replaceKeys['major']       = isset($row[MAJOR])  ? $row[MAJOR] : 'لا يوجد';
                $replaceKeys['rayat_id']    = isset($row[RAYAT_ID])  ? $row[RAYAT_ID] : 'لا يوجد';
                $replaceKeys['wallet']      = isset($row[WALLET])  ? $row[WALLET] : 'لا يوجد';
                $replaceKeys['phone']       = isset($row[PHONE])  ? $row[PHONE] : 'لا يوجد';


                Validator::make($replaceKeys, [
                    "national_id"  => 'required|digits:10',
                    "name"         => 'required|string|max:100',
                    "program"      => 'required|string|max:100',
                    "department"   => 'required|string|max:100',
                    "major"        => 'required|string|max:100',
                    "rayat_id"    => 'required|digits_between:9,10',
                    "wallet"       => 'required:traineState|numeric',
                    "phone"        => 'required|digits_between:9,14',
                ], [
                    'national_id.digits'   => '  يجب ان يكون رقم الهوية 10 ارقام',
                    'name.max'             => ' يجب ان لا يتجاوز الاسم 255 حرف',
                    'phone.digits_between' => '  يجب ان يكون رقم الجوال بين 10 و 14 رقماَ',
                    'wallet.required'      => '   الفائض / العجز (المحفظة) حقل مطلوب',
                    'wallet.numeric'      => '  يجب ان يكون حقل الفائض / العجز (المحفظة) رقماً',

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

            // switch ($row[TRAINEE_STATE]) {
            //     case "منسوب":
            //         $row[TRAINEE_STATE] = "employee";
            //         break;
            //     case "ابن منسوب":
            //         $row[TRAINEE_STATE]  = "employeeSon";
            //         break;
            //     case "لديه اعفاء":
            //         $row[TRAINEE_STATE]  = "privateState";
            //         break;
            //     default:
            //         $row[TRAINEE_STATE]  = "trainee";
            // }

            // if ($row[DOCUMENTS_VERIFIED] === "TRUE" || $row[DOCUMENTS_VERIFIED] === "true" || $row[DOCUMENTS_VERIFIED] === "1") {
            //     $row[DOCUMENTS_VERIFIED] = true;
            // } else {
            //     $row[DOCUMENTS_VERIFIED] = false;
            // }

            //############ Download recepit file from Google drive ##############

            // try {
            //     $success = false;
            //     if (filter_var($row[RECEIPT_URL], FILTER_VALIDATE_URL) != false) {
            //         $fileId = $this::getGDriveId($row[RECEIPT_URL]);
            //         $response = (object) $service->files->get($fileId, array("alt" => "media"));
            //         if ($response->hasHeader('Content-Type')) {
            //             $fileInfo  = $response->getHeader('Content-Type')[0];
            //             $fileExt = explode('/', $fileInfo)[1];
            //             if ($fileExt == 'jpeg' || $fileExt == 'png') {
            //                 $content = $response->getBody();
            //                 if ($row[TRAINEE_STATE] != 'privateState') {
            //                     $doc_name =  date('Y-m-d-H-i') . '_payment_receipt.' . $fileExt;
            //                     Storage::disk('studentDocuments')->put('/' . $row[NATIONAL_ID] . '/receipts/' . $doc_name, $content);
            //                     $success = true;
            //                 } else {
            //                     $doc_name =  date('Y-m-d-H-i') . '_privateStateDoc.' . $fileExt;
            //                     Storage::disk('studentDocuments')->put('/' . $row[NATIONAL_ID] . '/privateStateDoc/' . $doc_name, $content);
            //                     $success = true;
            //                 }
            //             } else {
            //                 array_push($errorsArr, ['message' => " ملف غير مدعوم لصورة الايصال " . $fileExt, 'userinfo' => $userinfo]);
            //             }
            //         }
            //     } elseif ($row[TRAINEE_STATE] == 'privateState') {
            //         $success = true;
            //     }
            //     if (!$success) {
            //         if ($row[TRAINEE_STATE] != 'privateState') {
            //             array_push($errorsArr, ['message' => "تعذر تحميل صورة الايصال", 'userinfo' => $userinfo]);
            //         } else {
            //             array_push($errorsArr, ['message' => "تعذر تحميل اثبات الاعفاء ", 'userinfo' => $userinfo]);
            //         }

            //         continue;
            //     }
            // } catch (Exception $e) {
            //    Log::error($e->getMessage().' '.$e);
            //     if ($row[TRAINEE_STATE] != 'privateState') {
            //         array_push($errorsArr, ['message' => " تعذر تحميل صورة الايصال " . $e->getCode(), 'userinfo' => $userinfo]);
            //     } else {
            //         array_push($errorsArr, ['message' => " تعذر تحميل اثبات الاعفاء " . $e->getCode(), 'userinfo' => $userinfo]);
            //     }
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
                    'rayat_id'              => $row[RAYAT_ID],
                    'program_id'            => $progId,
                    'department_id'         => $deptId,
                    'major_id'              => $mjrId,
                    // 'documents_verified'    => $row[DOCUMENTS_VERIFIED],
                    'traineeState'          => 'trainee',
                    'wallet'                => $row[WALLET],
                    'note'                  => $row[NOTE],
                    'student_docs_verified' => true,
                    'has_imported_docs'     => "نعم",
                    'final_accepted'        => true,
                    'data_updated'          => false,
                    'agreement'             => false,
                    'level'                 => 2,
                ]);
                $user->student->transactions()->create([
                    "amount"        => $row[WALLET],
                    "type"          => "manager_recharge",
                    "note"          => "رصيد سابق",
                    "by_user"       => Auth::user()->id,
                ]);
                // $transaction = Transaction::create([

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
        if (count($duplicate) > 0  && count($errorsArr) > 0) {
            return redirect(route('OldForm'))->with([
                // 'error' => ' تم أضافة جميع المتدربين بنجاح, ماعدا المتدربين التالية بياناتهم ',
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
                // 'error' => '  تعذر اضافة المتدربين التالية بياناتهم',
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
