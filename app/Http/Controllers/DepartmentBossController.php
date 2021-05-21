<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Major;
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
            (object) [
                "name" => "ادارة المقررات",
                "url" => route("deptCoursesIndex")
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

    public function coursesIndex()
    {
        try {
            if (Auth::user()->isDepartmentManager()) {
                $programs =  json_encode(Auth::user()->manager->getMyDepartment());
                return view('manager.community.courses.index')->with(compact('programs'));
            } else {
                return view("error")->with("error", "لا تملك الصلاحيات لدخول لهذه الصفحة");
            }
        } catch (Exception $e) {
            return view("error")->with("error", "حدث خطأ غير معروف");
            Log::error($e->getMessage() . ' ' . $e);
        }
    }
    
    public function createCourseForm()
    {

        try {
            if (Auth::user()->isDepartmentManager()) {
                $programs =  json_encode(Auth::user()->manager->getMyDepartment());
                return view('manager.community.courses.create')->with(compact('programs'));
            } else {
                return view("error")->with("error", "لا تملك الصلاحيات لدخول لهذه الصفحة");
            }
        } catch (Exception $e) {
            return view("error")->with("error", "حدث خطأ غير معروف");
            Log::error($e->getMessage() . ' ' . $e);
        }
    }

    public function createCourse(Request $request)
    {
        $requestData = $this->validate($request, [
            "major"         => "required|numeric|exists:majors,id",
            "name"          => "required|string|min:3|max:100",
            "code"          => "required|string|min:3|max:15",
            "level"         => "required|numeric|min:1|max:5",
            "credit_hours"  => "required|numeric|min:1|max:20",
            "contact_hours" => "required|numeric|min:1|max:20",
        ]);
        $major = Major::findOrFail($requestData["major"]);

        try {
            $major->courses()->create([
                'name' => $requestData["name"],
                'code' => $requestData["code"],
                'level' => $requestData["level"],
                'suggested_level' => 0,
                'credit_hours' => $requestData["credit_hours"],
                'contact_hours' => $requestData["contact_hours"],
            ]);
            return redirect(route("deptCoursesIndex"))->with("success", "تم انشاء المقرر بنجاح");
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with("error", "حدث خطأ غير معروف تعذر انشاء المقرر");
        }
    }

    public function editCourse(Request $request)
    {
        $requestData = $this->validate($request, [
            "id"         => "required|numeric|exists:courses,id",
            "name"          => "required|string|min:3|max:100",
            "code"          => "required|string|min:3|max:15",
            "level"         => "required|numeric|min:1|max:5",
            "credit_hours"  => "required|numeric|min:1|max:20",
            "contact_hours" => "required|numeric|min:1|max:20",
        ]);

        $course = Course::findOrFail($requestData["id"]);
        try {
            $course->update([
                'name' => $requestData["name"],
                'code' => $requestData["code"],
                'level' => $requestData["level"],
                'credit_hours' => $requestData["credit_hours"],
                'contact_hours' => $requestData["contact_hours"],
            ]);
            return redirect(route("deptCoursesIndex"))->with("success", "تم تعديل المقرر بنجاح");
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with("error", "حدث خطأ غير معروف تعذر تعديل المقرر");
        }
    }
}
