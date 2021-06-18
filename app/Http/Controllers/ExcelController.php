<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Imports\AddRayatId;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Imports\OldUsersImport;
use App\Imports\UpdateCreditHours;
use App\Imports\UpdateStudentsWallet;
use App\Imports\UpdateWalletImport;
use App\Models\Department;
use App\Models\Program;
use App\Models\Semester;
use App\Models\User;
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

    public function exportMainStudentData()
    {
        try {
            $filename='bct_main_data_backup_'.date('m-d-Y_h_i_a');
            return Excel::download(new UsersExport, $filename.'.xlsx');
            // return view('excel.exportAllStudent', [
            //     'users' => User::whereHas("student")->get(),
            // ]);
        } catch (Exception $e) {
           Log::error($e->getMessage().' '.$e);
           return view('error')->with('error','حدث خطأ غير معروف');
        }
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

    function updateStudentsWalletForm()
    {
        return view('excel.updateStudentsWallet');
    }

    function updateStudentsWalletStore(Request $request)
    {
        try{
            Excel::import(new UpdateStudentsWallet(),  $request->file('excel_file'));
        }catch(Exception $e){
            Log::error($e->getMessage().' '.$e);
            return back()->with('error',"حدث خطأ غير معروف");
        }
        return back();
    }



    function addRayatIdForm()
    {
        return view('excel.addRayatId');
    }

    function addRayatIdStore(Request $request)
    {
        try{
            Excel::import(new AddRayatId(),  $request->file('excel_file'));
        }catch(Exception $e){
            Log::error($e->getMessage().' '.$e);
            return back()->with('error',"حدث خطأ غير معروف");
        }
        return back();
    }

    function updateCreditHoursForm()
    {
        return view('excel.updateCreditHours');
    }

    function updateCreditHoursStore(Request $request)
    {
        try{
            Excel::import(new UpdateCreditHours(),  $request->file('excel_file'));
        }catch(Exception $e){
            Log::error($e->getMessage().' '.$e);
            return back()->with('error',"حدث خطأ غير معروف");
        }
        return back();
    }

}
