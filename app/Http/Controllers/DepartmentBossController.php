<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Program;
use Illuminate\Database\QueryException;

class DepartmentBossController extends Controller
{
    public function index()
    {
        $programs =  json_encode(Program::with('departments.majors.courses')->orderBy('name', 'asc')->get());
        // dd($programs);
        return view('departmentBoss.manageCourses')->with(compact('programs'));
    }

    public function getCoursesData()
    { 
        try{
            $programs =  json_encode(Program::with('departments.majors.courses')->orderBy('name', 'asc')->get());
            return response($programs,200);
        }catch(QueryException $e){
            return response(['message' => 'حدث خطأ غير معروف تعذر جلب البيانات'],500);
        }
    }

    public function updateCoursesLevel(Request $request)
    {
        $coursesData = $this->validate($request, [
         "suggested_level" => "required|numeric",
         "courses.*"         => "required|numeric"
        ]);
        try{
            Course::whereIn('id', $coursesData['courses'])->update(['suggested_level' => $coursesData['suggested_level']]);
            $programs =  json_encode(Program::with('departments.majors.courses')->orderBy('name', 'asc')->get());
            return response(['message' => 'تم تحديث الجدول المقترح بنجاح', 'programs' => $programs],200);
        }catch(QueryException $e){
            return response(['message' => 'حدث خطأ غير معروف اثناء تحديث الجدول المقترح'],512);
        }
    }
    
}
