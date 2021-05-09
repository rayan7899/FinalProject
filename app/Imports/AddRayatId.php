<?php

namespace App\Imports;

use App\Models\User;
use Exception;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

class AddRayatId implements ToCollection
{
    use Importable;

    public function collection(Collection $rows)
    {
        $rows = $rows->slice(1);
        $errorsArr = [];
        $updatedCount = 0;
        $countOfStudents = count($rows);

        for ($i = 0; $i < count($rows[1]); $i++) {
            if (is_numeric($rows[1][$i])) {
                if (strlen((string) $rows[1][$i]) == 10) {
                    if (substr((string) $rows[1][$i], 0, 2) == "10" || substr((string) $rows[1][$i], 0, 2) == "11") {
                        $national_key = $i;
                    }
                }

                if (strlen((string) $rows[1][$i]) > 8 && strlen((string) $rows[1][$i]) < 11) {
                    if (substr((string) $rows[1][$i], 0, 1) == "4") {
                        $rayat_key = $i;
                    }
                }
            }
        }

        if (!isset($national_key)) {
            return redirect(route('addRayatIdForm'))->with('error', 'تعذر الحصول رقم الهوية يرجى التآكد من صحة الملف');
        } elseif (!isset($rayat_key)) {
            return redirect(route('addRayatIdForm'))->with('error', ' تعذر الحصول الرقم التدريبي يرجى التآكد من صحة الملف');
        }

        foreach ($rows->toArray() as $row) {
            try {
                Validator::make($row, [
                    $national_key  => 'required|digits:10',
                    $rayat_key    => 'required|digits_between:9,10|unique:students,rayat_id',
                ], [
                    $national_key . '.digits'   => '  يجب ان يكون رقم الهوية 10 ارقام',
                ], [
                    $national_key  => 'رقم الهوية',
                    $rayat_key    => 'الرقم التدريبي'
                ])->validate();

                $user = User::where('national_id', $row[$national_key])->first() ?? null;
                if ($user == null) {
                    array_push($errorsArr, ['message' => 'لا يوجد متدرب حسب البيانات المدخلة', 'national_id' => $row[$national_key]]);
                    continue;
                } elseif ($user->student->rayat_id != null) {
                    array_push($errorsArr, ['message' => ' الرقم التدريبي مضاف مسبقاً', 'national_id' => $row[$national_key]]);
                    continue;
                }
                DB::beginTransaction();
                $user->student->rayat_id = $row[$rayat_key];
                $user->student->save();
                DB::commit();
                $updatedCount++;
            } catch (Exception $e) {
                DB::rollBack();
                if (isset($e->validator)) {
                    array_push($errorsArr, ['message' => implode(", ", $e->validator->errors()->all()), 'national_id' => $row[$national_key]]);
                } else {
                    array_push($errorsArr, ['message' =>  $e->getMessage(), 'national_id' => $row[$national_key]]);
                }
                continue;
            }
        }
        if (count($errorsArr) > 0) {
            return redirect(route('addRayatIdForm'))->with([
                'errorsArr' => $errorsArr,
                'updatedCount' => $updatedCount,
                'countOfStudents' => $countOfStudents
            ]);
        }

        return redirect(route('addRayatIdForm'))->with('success', 'تم اضافة الرقم التدريبي لـ  ' . $updatedCount . ' متدرب بنجاح ');
    }
}
