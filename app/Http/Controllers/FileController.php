<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function student_documents($national_id){
       $documents = Storage::disk('studentDocuments')->files($national_id);
       $receipts = Storage::disk('studentDocuments')->files($national_id.'/receipts');
       $privateDocs = Storage::disk('studentDocuments')->files($national_id.'/privateStateDoc');

       return  array(
           'documents' => $documents,
           'receipts'  => $receipts,
           'privateDocs' => $privateDocs,
       );
    }

    public function get_student_document($path){
        return response()->file(Storage::disk('studentDocuments')->path($path));
    }

    public function get_student_document_api($national_id,$filename){
        return response()->file(Storage::disk('studentDocuments')->path('/'.$national_id.'/receipts/'.$filename));
    }

    public function get_all_documents_api($national_id)
    {
        try {
            $user = User::where('national_id', $national_id)->first();
            $payments = $user->student->payments()->where('accepted', '=', 1)->get();
            $imgs = [];
            $paths = Storage::disk('studentDocuments')->allFiles('/'.$national_id.'/receipts/');
            foreach ($payments as $payment) {
                array_push($imgs, $payment->receipt_file_id);
            };
            return response($imgs);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return response(['message' => $e], 422);
        }
    }
}
