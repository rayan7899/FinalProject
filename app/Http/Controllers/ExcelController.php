<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Imports\OldUsersImport;
use App\Models\Department;
use App\Models\Program;
use Exception;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Validators\ValidationException;
use Throwable;

class ExcelController extends Controller
{

    // List all users from DB
    function index()
    {

        return;
    }

    // Show Import Excel file form
    function importNewForm()
    {

        $programs =  json_encode(Program::with('departments.majors')->orderBy('name', 'asc')->get());

        return view('excel.newUsersForm')->with(compact('programs'));
    }

    //Import Excel file to DB
    function importNewUsers(Request $request)
    {
        $deptMjr = $this->validate($request, [
            "excel_file" => "required|mimes:xls,xlsx,ods",
            "program" => "required|numeric|min:1",
            "department" => "required|numeric|min:1",
            "major" => "required|numeric|min:1",

        ]);


        // Extaract users from Excel file and add them to the DB
        try{
            Excel::import(new UsersImport($deptMjr),  $request->file('excel_file'));
        }catch(Exception $e){
            Log::error($e);
            return back()->with('error',"حدث خطأ غير معروف");
        }
        return back();
       
        //return back()->with('success', 'تم أضافة المتدربين بنجاح');

    }

    public function exportNewUsers()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }



    public function importOldForm()
    {
        return view('excel.oldUsersForm');
    }

    public function importOldUsers(Request $request)
    {
        $request->validate([
            "excel_file" => "mimes:xls,xlsx,ods",
        ]);
        Excel::import(new OldUsersImport, $request->file('excel_file'));
        return back();
    }

    public function exportOldUsers()
    {
    }

}
