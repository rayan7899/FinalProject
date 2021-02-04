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
    use Importable;
    public function onError(\Throwable $e)
    {
        
        If ($e->errorInfo[0] == "23000" && $e->errorInfo[1] == "1062"){
            return back()->with('error', 'خطأ, يوجد تكرار في البيانات, واحد او اكثر من المستخدمين تم اضافته مسبقاً');
        }
        return back()->with('error', ' حدث خطأ غير معروف '.$e->errorInfo[1]);
       
    }
    public function collection(Collection $rows)
    {
      
        Validator::make($rows->toArray(), [
             '*.0' => 'required|numeric',
             '*.1' => 'required|string',
             '*.2' => 'required|numeric',
         ])->validate();

        foreach ($rows as $row) {
            try{
            User::create([
            'national_id'   => $row[0],
            'name'          => $row[1],
            'email'         => $row[0]."@tvtc.edu.sa",
            'phone'         => $row[2],
            'password' => Hash::make("bct12345")
            ]);
            }
            catch(\Throwable $e){
                 
        If ($e->errorInfo[0] == "23000" && $e->errorInfo[1] == "1062"){
            return back()->with('error', 'خطأ, يوجد تكرار في البيانات, واحد او اكثر من المستخدمين تم اضافته مسبقاً');
        }
        return back()->with('error', ' حدث خطأ غير معروف '.$e->errorInfo[1]);
            }
        }
    }

}






// class UsersImport implements ToModel, WithHeadingRow,WithValidation
// {
//     use Importable,WithValidation;
//     /**
//     * @param array $row
//     *
//     * @return \Illuminate\Database\Eloquent\Model|null
//     */

//     public function onError(\Throwable $e)
//     {
        
//         If ($e->errorInfo[0] == "23000" && $e->errorInfo[1] == "1062"){
//             return back()->with('error', 'خطأ, يوجد تكرار في البيانات, واحد او اكثر من المستخدمين تم اضافته مسبقاً');
//         }
//         return back()->with('error', ' حدث خطأ غير معروف '.$e->errorInfo[1]);
       
//     }


//     public function model(array $row)
//     {
//         if(!is_int($row[0])){
//             return back()->with('error', ' خطأ في ترتيب الاعمدة  ');
//         }
//         return new User([
//             'national_id'   => $row[0],
//             'name'          => $row[1],
//             'email'         => $row[0]."@tvtc.edu.sa",
//             'phone'         => $row[2],
//             'password' => Hash::make("btc12345")
//         ]);
//     }
//     public function rules(): array
//     {
//         return [
//             '1' => Rule::in(['patrick@maatwebsite.nl']),

//              // Above is alias for as it always validates in batches
//              '*.1' => Rule::in(['patrick@maatwebsite.nl']),
             
//              // Can also use callback validation rules
//              '0' => function($attribute, $value, $onFailure) {
//                   if ($value !== 'Patrick Brouwers') {
//                        $onFailure('Name is not Patrick Brouwers');
//                   }
//               }
//         ];
//     }

// }


