<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Imports\UsersImport;
use App\Models\Department;
use App\Models\Major;
use Illuminate\Support\Facades\Redirect;

class ImportExcelController extends Controller
{

    // List all users from DB
    function index()
    {

        return;
    }

    // Show Import Excel file form
    function add()
    {

        $departments =  Department::with('majors')->get();
        return view('excel.form')->with(compact('departments'));
    }

    //Import Excel file to DB
    function import(Request $request)
    {
       $deptMjr = $this->validate($request, [
            "excel_file" => "required|mimes:xls,xlsx",
            "department" => "required|numeric|min:1",
            "major" => "required|numeric|min:1"
        ]);
       

        // Extaract users from Excel file and add them to the DB
        if (Excel::import(new UsersImport($deptMjr), request()->file('excel_file'))) {
            return back()->with('success', 'تم أضافة المستخدمين بنجاح');
        }
    }
}
