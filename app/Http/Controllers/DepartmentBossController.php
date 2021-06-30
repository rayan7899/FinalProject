<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Major;
use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Semester;
use App\Models\Trainer;
use App\Models\TrainerCoursesOrders;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            Log::error($e->getMessage() . ' ' . $e);
            return view("error")->with("error", "حدث خطأ غير معروف");
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
            (object) [
                "name" => "اضافة متدرب",
                "url" => route("deptCreateStudentForm")
            ],
            (object) [
                "name" => "تقرير رايات",
                "url" => route("rayatReportFormCommunity", ["type" => "departmentBoss"])
            ],
            (object) [
                "name" => "بيانات المدربين",
                "url" => route("trainersInfoView")
            ],
            (object) [
                "name" => "الطلبات المعادة",
                "url" => route("rejectedTrainerCoursesOrdersView")
            ],
        ];
        return view("manager.departmentBoss.dashboard")->with(compact("links", "title"));
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
            Log::error($e->getMessage() . ' ' . $e);
            return response(['message' => 'حدث خطأ غير معروف تعذر جلب البيانات'], 500);
        }
    }

    public function apiGetMajorCourses(Major $major)
    {
        try {
            return json_encode($major->courses->toArray());
        } catch (QueryException $e) {
            Log::error($e->getMessage() . ' ' . $e);
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
            Log::error($e->getMessage() . ' ' . $e);
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
            "major"                  => "required|numeric|exists:majors,id",
            "name"                   => "required|string|min:3|max:100",
            "code"                   => "required|string|min:3|max:15",
            "level"                  => "required|numeric|min:1|max:5",
            "credit_hours"           => "required|numeric|min:1|max:20",
            "contact_hours"          => "required|numeric|min:1|max:20",
            "theoretical_hours"      => "required|numeric|min:1|max:20",
            "practical_hours"        => "required|numeric|min:1|max:20",
            "exam_theoretical_hours" => "required|numeric|min:1|max:20",
            "exam_practical_hours"   => "required|numeric|min:1|max:20",
        ]);
        $major = Major::findOrFail($requestData["major"]);

        try {
            $major->courses()->create([
                'name'                   => $requestData["name"],
                'code'                   => $requestData["code"],
                'level'                  => $requestData["level"],
                'suggested_level'        => 0,
                'credit_hours'           => $requestData["credit_hours"],
                'contact_hours'          => $requestData["contact_hours"],
                'theoretical_hours'      => $requestData["theoretical_hours"],
                'practical_hours'        => $requestData["practical_hours"],
                'exam_theoretical_hours' => $requestData["exam_theoretical_hours"],
                'exam_practical_hours'   => $requestData["exam_practical_hours"],
            ]);
            return redirect(route("deptCoursesIndex"))->with("success", "تم انشاء المقرر بنجاح");
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with("error", "حدث خطأ غير معروف تعذر انشاء المقرر");
        }
    }

    public function editCourseForm(Course $course)
    {
        try {
            if (Auth::user()->hasRole("خدمة المجتمع")) {
                $programs = json_encode(Program::with("departments.majors.courses")->get());
                return view("manager.community.courses.edit")->with(compact('programs','course'));
            } else if (Auth::user()->isDepartmentManager()) {
                $programs =  json_encode(Auth::user()->manager->getMyDepartment());
                return view('manager.community.courses.edit')->with(compact('programs', 'course'));
            } else {
                return view("error")->with("error", "لا تملك الصلاحيات لدخول لهذه الصفحة");
            }
        } catch (Exception $e) {
            return view("error")->with("error", "حدث خطأ غير معروف");
            Log::error($e->getMessage() . ' ' . $e);
        }
    }

    public function editCourse(Request $request)
    {
        $requestData = $this->validate($request, [
            "id"                     => "required|numeric|exists:courses,id",
            "major"                  => "nullable|numeric|exists:majors,id",
            "name"                   => "required|string|min:3|max:100",
            "code"                   => "required|string|min:3|max:15",
            "level"                  => "required|numeric|min:1|max:5",
            "credit_hours"           => "required|numeric|min:1|max:20",
            "contact_hours"          => "required|numeric|min:1|max:20",
            "theoretical_hours"      => "required|numeric|min:1|max:20",
            "practical_hours"        => "required|numeric|min:1|max:20",
            "exam_theoretical_hours" => "required|numeric|min:1|max:20",
            "exam_practical_hours"   => "required|numeric|min:1|max:20",
        ]);
        $course = Course::findOrFail($requestData["id"]);
        $major = Major::find($requestData["major"] ?? null);

        try {
            $course->update([
                'name'                   => $requestData["name"],
                'code'                   => $requestData["code"],
                'level'                  => $requestData["level"],
                'major_id'               => $major != null ? $requestData["major"] : $course->major_id,
                'credit_hours'           => $requestData["credit_hours"],
                'contact_hours'          => $requestData["contact_hours"],
                'theoretical_hours'      => $requestData["theoretical_hours"],
                'practical_hours'        => $requestData["practical_hours"],
                'exam_theoretical_hours' => $requestData["exam_theoretical_hours"],
                'exam_practical_hours'   => $requestData["exam_practical_hours"],
            ]);
            return redirect(route("deptCoursesIndex"))->with("success", "تم تعديل المقرر بنجاح");
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with("error", "حدث خطأ غير معروف تعذر تعديل المقرر");
        }
    }

    public function createStudentForm()
    {
        $programs =  json_encode(Auth::user()->manager->getMyDepartment());
        return view("manager.community.students.create")->with(compact('programs'));
    }

    public function createStudentStore(Request $request)
    {
        $requestData = $this->validate($request, [
            'national_id' => 'required|digits:10|unique:users,national_id',
            "rayat_id"    => 'nullable|digits_between:9,10|unique:students,rayat_id',
            'name'     => 'required|string|min:3|max:100',
            "phone"        => 'required|digits_between:9,14|unique:users,phone',
            "major"         => "required|numeric|exists:majors,id",
            "level"         => "required|numeric|min:1|max:5",
        ]);

        try {
            if (Auth::user()->isDepartmentManager()) {

                $password = Hash::make("bct12345");
                $major = Major::find($requestData['major']) ?? null;
                $prog_id = $major->department->program->id;
                $dept_id = $major->department->id;
                if ($major == null) {
                    return back()->with("error", "لا يوجد قسم حسب المعلومات المرسله");
                }
                DB::beginTransaction();
                User::create([
                    "national_id" => $requestData['national_id'],
                    "name" => $requestData['name'],
                    "phone" => $requestData['phone'],
                    "password" => $password
                ])->student()->create([
                    "rayat_id" => $requestData['rayat_id'],
                    "program_id" => $prog_id,
                    "department_id" => $dept_id,
                    "has_imported_docs" => false,
                    "major_id" => $requestData['major'],
                    "level"    => $requestData['level'],
                ]);
                DB::commit();
                return redirect(route("deptCreateStudentForm"))->with('success', 'تم اضافة المتدرب بنجاح');
            } else {
                return back()->with("error", "ليس لديك صلاحيات لتنفيذ هذا الامر");
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with('error', ' حدث خطأ غير معروف ' . $e->getCode());
        }
    }

    public function trainersInfoView()
    {
        try {
            $myDepartmentsIDs = [];
            foreach (Auth::user()->manager->getMyDepartment() as $program) {
                foreach ($program->departments as $department) {
                    array_push($myDepartmentsIDs, $department->id);
                }
            }
            $semester = Semester::latest()->first();
            $users = User::with('trainer')->whereHas('trainer.coursesOrders.course.major.department', function ($res) use ($myDepartmentsIDs, $semester) {
                $res->where('accepted_by_dept_boss', null)
                    ->where('accepted_by_community', null)
                    ->where('semester_id', $semester->id)
                    ->whereIn('departments.id', $myDepartmentsIDs);
            })->get();
            return view('manager.departmentBoss.trainersInfo')->with(compact('users'));
        } catch (Exception $e) {
            return back()->with('error', $e);
        }
    }

    public function getCoursesByTrainer(Trainer $trainer)
    {
        try {
            $myDepartmentsIDs = [];
            foreach (Auth::user()->manager->getMyDepartment() as $program) {
                foreach ($program->departments as $department) {
                    array_push($myDepartmentsIDs, $department->id);
                }
            }
            $semester = Semester::latest()->first();
            $orders = $trainer->coursesOrders()->with('course')
            ->where('accepted_by_dept_boss', null)
            ->where('semester_id', $semester->id)
            ->whereHas('course.major.department', function ($res) use ($myDepartmentsIDs) {
                $res->whereIn('departments.id', $myDepartmentsIDs);
            })->get();
            return response(['message' => 'تم جلب البيانات بنجاح', 'orders' => $orders], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . $e);
            return response(['error' => ' حدث خطأ غير معروف ' . $e], 422);
        }
    }
    
    public function acceptTrainerCourseOrder(Request $request)
    {
        $requestData = $this->validate($request, [
            "orders"                      => "required|array|min:1",
            "orders.*.order_id"           => "required|numeric|exists:trainer_courses_orders,id",
            "orders.*.count_of_students"  => "required|numeric|min:1",
            "orders.*.division_number"    => "required|numeric|min:1",
        ]);
        try {
            DB::beginTransaction();
            foreach ($requestData['orders'] as $order) {
                $courseOrder = TrainerCoursesOrders::find($order['order_id']);
                if($courseOrder->accepted_by_dept_boss != true){
                    $courseOrder->update([
                        'accepted_by_dept_boss' =>  true,
                        'count_of_students'     =>  $order['count_of_students'],
                        'division_number'       =>  $order['division_number'],
                    ]);
                }
            }
            DB::commit();
            return response(['message' => 'تم قبول الطلب بنجاح'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage() . $e);
            return response(['error' => ' حدث خطأ غير معروف ' . $e], 422);
        }
        
    }

    public function rejectTrainerCourseOrder(Request $request)
    {
        $requestData = $this->validate($request, [
            "order_id"           => "required|numeric|exists:trainer_courses_orders,id",
            "note"               => "string|nullable"
        ]);
        try {
            DB::beginTransaction();
            TrainerCoursesOrders::find($requestData['order_id'])->update([
                'accepted_by_dept_boss' => false,
                'dept_boss_note'        => $requestData['note'],
            ]);
            DB::commit();
            return response(['message' => 'تم رفض الطلب بنجاح'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage() . $e);
            return response(['message' => ' حدث خطأ غير معروف ' . $e], 422);
        }
    }

    public function editExamHours(Request $request)
    {
        $requestData = $this->validate($request, [
            "id"                     => "required|numeric|exists:courses,id",
            "course_type"            => "required|in:عملي,نظري",
            "exam_hours"             => "required|numeric|min:0|max:20",
        ]);
        $course = Course::findOrFail($requestData["id"]);
        try {
            if($requestData["course_type"] == 'نظري'){
                if($requestData["exam_hours"] == $course->exam_theoretical_hours){
                    return response(['message' => 'عدد ساعات الاختبار مطابق لعدد ساعات الاختبار السابق'], 422);
                }else{
                    $course->update([
                        'exam_theoretical_hours' => $requestData["exam_hours"],
                    ]);
                }
            }else{
                if($requestData["exam_hours"] == $course->exam_practical_hours){
                    return response(['message' => 'عدد ساعات الاختبار مطابق لعدد ساعات الاختبار السابق'], 422);
                }else{
                    $course->update([
                        'exam_practical_hours'   => $requestData["exam_hours"],
                    ]);
                }
            }
            return response(['message' => "تم تعديل ساعات الاختبار بنجاح"], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return response(['message' => $e->getMessage()], 422);
        }
    }

    public function rejectedTrainerCoursesOrdersView()
    {
        try {
            $myDepartmentsIDs = [];
            foreach (Auth::user()->manager->getMyDepartment() as $program) {
                foreach ($program->departments as $department) {
                    array_push($myDepartmentsIDs, $department->id);
                }
            }
            $semester = Semester::latest()->first();
            $users = User::with('trainer')->whereHas('trainer.coursesOrders.course.major.department', function ($res) use ($myDepartmentsIDs, $semester) {
                $res->where('accepted_by_dept_boss', true)
                    ->where('accepted_by_community', false)
                    ->where('semester_id', $semester->id)
                    ->whereIn('departments.id', $myDepartmentsIDs);
            })->get();
            return view('manager.departmentBoss.rejectedTrainerCoursesOrders')->with(compact('users'));
        } catch (Exception $e) {
            return back()->with('error', $e);
        }
    }

    public function getRejectedCoursesByTrainer(Trainer $trainer)
    {
        try {
            $myDepartmentsIDs = [];
            foreach (Auth::user()->manager->getMyDepartment() as $program) {
                foreach ($program->departments as $department) {
                    array_push($myDepartmentsIDs, $department->id);
                }
            }
            $semester = Semester::latest()->first();
            $orders = $trainer->coursesOrders()->with('course')
            ->where('accepted_by_dept_boss', true)
            ->where('accepted_by_community', false)
            ->where('semester_id', $semester->id)
            ->whereHas('course.major.department', function ($res) use ($myDepartmentsIDs) {
                $res->whereIn('departments.id', $myDepartmentsIDs);
            })->get();
            return response(['message' => 'تم جلب البيانات بنجاح', 'orders' => $orders], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . $e);
            return response(['error' => ' حدث خطأ غير معروف ' . $e], 422);
        }
    }
    
    public function acceptRejectedTrainerCourseOrder(Request $request)
    {
        $requestData = $this->validate($request, [
            "orders"                      => "required|array|min:1",
            "orders.*.order_id"           => "required|numeric|exists:trainer_courses_orders,id",
            "orders.*.count_of_students"  => "required|numeric|min:1",
            "orders.*.division_number"    => "required|numeric|min:1",
        ]);
        try {
            DB::beginTransaction();
            foreach ($requestData['orders'] as $order) {
                $courseOrder = TrainerCoursesOrders::find($order['order_id']);
                if($courseOrder->accepted_by_dept_boss == true){
                    $courseOrder->update([
                        'accepted_by_community' =>  null,
                        'count_of_students'     =>  $order['count_of_students'],
                        'division_number'       =>  $order['division_number'],
                    ]);
                }
            }
            DB::commit();
            return response(['message' => 'تم قبول الطلب بنجاح'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage() . $e);
            return response(['error' => ' حدث خطأ غير معروف ' . $e], 422);
        }
        
    }
}
