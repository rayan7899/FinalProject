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
use Maatwebsite\Excel\Validators\ValidationException;

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
            "excel_file" => "required|mimes:xls,xlsx",
            "program" => "required|numeric|min:1",
            "department" => "required|numeric|min:1",
            "major" => "required|numeric|min:1",

        ]);


        // Extaract users from Excel file and add them to the DB
        Excel::import(new UsersImport($deptMjr),  $request->file('excel_file'));
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

        try {
            $coll =  Excel::import(new OldUsersImport, $request->file('excel_file'));
            return back();
        } catch (Exception $e) {
            return redirect(route('OldForm'))->with('error',$e->getMessage());
         // dd($e);
            // $js = json_decode($e->getMessage(), true);
            // $duplicate = $js['duplicate'] ?? null;
            // $errorsArr = $js['errorsArr'] ?? null;
            // $addedCount = $js['addedCount'] ?? null;
            // $countOfUsers = $js['countOfUsers'] ?? null;
            // return redirect(route('OldForm'))->with([
            //     'duplicate' => $duplicate,
            //     'addedCount' => $addedCount,
            //     'countOfUsers' => $countOfUsers,
            //     'errorsArr'    => $errorsArr
            // ]);
        }
    }

    public function exportOldUsers()
    {
    }

    static function hanError($errors)
    {
       
        return redirect(route('OldForm'))->with($errors);
    }
    
}
