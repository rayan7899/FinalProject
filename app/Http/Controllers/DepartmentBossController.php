<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Program;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DepartmentBossController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        try {
            if (Auth::user()->isDepartmentManager()) {
                $programs = json_encode(Auth::user()->manager->getMyDepartment());

                return view('manager.departmentBoss.coursesPerLevel')->with(compact('programs'));
            } else if (Auth::user()->hasRole('شؤون المتدربين')) {
                $programs = json_encode(Program::with("departments.majors.courses")->get());
                return view('manager.departmentBoss.coursesPerLevel')->with(compact('programs'));
            } else {
                return view("error")->with("error", "لا تملك الصلاحيات لدخول لهذه الصفحة");
            }
        } catch (Exception $e) {
            return view("error")->with("error", "حدث خطأ غير معروف");
           Log::error($e->getMessage().' '.$e);
        }
    }

    public function dashboard()
    {
        $title = "رئيس القسم";
        $links = [
            (object) [
                "name" => "المتدربين المتعثرين",
                "url" => route("studentCourses")
            ],
            (object) [
                "name" => "الجداول المقترحة",
                "url" => route("coursesPerLevel")
            ],
        ];
        return view("manager.departmentBoss.dashboard")->with(compact("links","title"));
    }

    //todo response level 2 and upper for dept boss and level 1 only for student affairs
    public function apiGetCourses()
    {
        try {
            if (Auth::user()->isDepartmentManager()) {
                $programs =  json_encode(Auth::user()->manager->getMyDepartment());
                return response($programs, 200);
            } else if (Auth::user()->hasRole('شؤون المتدربين')) {
                $programs = json_encode(Program::with("departments.majors.courses")->get());
                return response($programs, 200);
            }
        } catch (QueryException $e) {
           Log::error($e->getMessage().' '.$e);
            return response(['message' => 'حدث خطأ غير معروف تعذر جلب البيانات'], 500);
        }
    }

    public function updateCoursesLevel(Request $request)
    {
        $coursesData = $this->validate($request, [
            "suggested_level" => "required|numeric",
            "courses.*"         => "required|numeric"
        ]);
        try {
            Course::whereIn('id', $coursesData['courses'])->update(['suggested_level' => $coursesData['suggested_level']]);
            $programs =  json_encode(Program::with('departments.majors.courses')->orderBy('name', 'asc')->get());
            return response(['message' => 'تم تحديث الجدول المقترح بنجاح', 'programs' => $programs], 200);
        } catch (QueryException $e) {
           Log::error($e->getMessage().' '.$e);
            return response(['message' => 'حدث خطأ غير معروف اثناء تحديث الجدول المقترح'], 422);
        }
    }
}
