<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Major;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FalteringStudentsController extends Controller
{
    //
    public function index()
    {
        // $majors = Major::with('courses')->get();
        // return view('manager.departmentBoss.studentCourses')->with('majors', $majors);
        try {
            if (Auth::user()->isDepartmentManager()) {
                $programs =  json_encode(Auth::user()->manager->getMyDepartment());

                return view('manager.departmentBoss.studentCourses')->with(compact('programs'));
            } else {
                return view("error")->with("error", "لا تملك الصلاحيات لدخول لهذه الصفحة");
            }
        } catch (Exception $e) {
            return view("error")->with("error", "حدث خطأ غير معروف");
           Log::error($e->getMessage().$e);
        }
    }
}
