<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Models\Department;
use App\Models\Program;

class ExcelController extends Controller
{

    // List all users from DB
    function index()
    {

        return;
    }

    // Show Import Excel file form
    function add()
    {

        $programs =  json_encode(Program::with('departments.majors')->orderBy('name','asc')->get());

        return view('excel.form')->with(compact('programs'));
    }

    //Import Excel file to DB
    function import(Request $request)
    {
       $deptMjr = $this->validate($request, [
            "excel_file" => "required|mimes:xls,xlsx",
            "program" => "required|numeric|min:1",
            "department" => "required|numeric|min:1",
            "major" => "required|numeric|min:1",
            
        ]);
       

        // Extaract users from Excel file and add them to the DB
        Excel::import(new UsersImport($deptMjr), request()->file('excel_file'));
        return back();
            //return back()->with('success', 'تم أضافة المتدربين بنجاح');

    }

    public function Export(){
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
