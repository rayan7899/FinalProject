<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
