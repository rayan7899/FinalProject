<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

class FileController extends Controller
{
    public function student_documents($national_id){
        //return response()->file( Storage::disk('studentDocuments')->path($national_id.'/'.'identity.pdf'));
       $documents = Storage::disk('studentDocuments')->files($national_id);
       $receipts = Storage::disk('studentDocuments')->files($national_id.'/receipts');
       return  array(
               'documents' => $documents,
               'receipts'  => $receipts
           );
       
    }

    public function get_student_document($path){
        
        return response()->file(Storage::disk('studentDocuments')->path($path));
      
    }
}
