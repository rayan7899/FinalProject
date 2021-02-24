<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAjaxController extends Controller
{
    

    public function update(Request $request){
        //return response(json_encode($request->all()),422);
        $studentData = $this->validate($request, [
            "national_id"        => "required|numeric",
            "wallet"             => "required|numeric",
            "documents_verified" => "required|boolean",
            "note"               => "string"
        ]);
        try{
            $user = User::with('student')->where('national_id', $studentData['national_id'])->first();
            $user->student()->update([
                "wallet"             => $studentData['wallet'],
                "documents_verified" => $studentData['documents_verified'],
                "note"               => $studentData['note'],
            ]);

            return response(json_encode(['message' => 'تم تحديث البيانات بنجاح']),200);
        }catch(Exception $e){
            return response(json_encode(['message' => 'حدث خطأ غير معروف'. $e->getCode()]),422);
        }   
    }

    public function updateDocsVerified(Request $request){

        $studentData = $this->validate($request, [
            "national_id"        => "required|numeric",
            "documents_verified" => "required|boolean"
        ]);

        try{
            $user = User::with('student')->where('national_id', $studentData['national_id'])->first();
            $user->student()->update([
                "documents_verified" => $studentData['documents_verified'],
            ]);

            return response(json_encode(['message' => 'تم تغيير الحالة بنجاح']),200);
        }catch(Exception $e){
            return response(json_encode(['message' => 'حدث خطأ غير معروف'. $e->getCode()]),422);
        }      
    }
}
