<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use app\Models\User;
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

    function import(Request $request){

        $this->validate($request, [
            "excel_file" => "required|mimes:xls"
        ]);
        
        // Extaract users from Excel file and add them to the DB
        Excel::import(new UsersImport, request()->file('excel_file'));

        return back()->with('success', 'تم أضافة المستخدمين بنجاح');
        

    }
}
