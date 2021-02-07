<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Redirect;

class ImportExcelController extends Controller
{

 // List all users from DB
    function index(){
     
        return;
    }

   // Show Import Excel file form
    function add(){
        return view('excel.form');
    }

    //Import Excel file to DB
    function import(Request $request){
        $this->validate($request, [
            "excel_file" => "required|mimes:xls,xlsx"
        ]);
        

        // Extaract users from Excel file and add them to the DB
        if(Excel::import(new UsersImport, request()->file('excel_file')))
        {
            return back()->with('success', 'تم أضافة المستخدمين بنجاح');
        }

      
        

    }
}
