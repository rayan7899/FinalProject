<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
                "name" => "ادارة المدربين",
                "url" => route("manageTrainersForm")
            ],
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
                "name" => "تدقيق عقود التدريب",
                "url" => route("trainersInfoView")
            ],
            (object) [
                "name" => "الطلبات المعادة",
                "url" => route("rejectedTrainerCoursesOrdersView")
            ],
            (object) [
                "name" => "مراجعة بيانات المدربين",
                "url" => route("trainersReview")
            ],

            (object) [
                "name" => "تقرير بيانات المدربين",
                "url" => route("trainerReport")
            ],
        ];
        return view("manager.departmentBoss.dashboard")->with(compact("links", "title"));
    }

    public function trainerReport()
    {
        try {
            if (!Auth::user()->isDepartmentManager()) {
                return view("error")->with('error', 'ليس لديك صلاحيات لدخول الى هذه الصفحة');
            }
            $myDepartmentsIDs = [];
            foreach (Auth::user()->manager->getMyDepartment() as $program) {
                foreach ($program->departments as $department) {
                    array_push($myDepartmentsIDs, $department->id);
                }
            }
            $users = User::with('trainer')->whereHas("trainer", function ($res) use ($myDepartmentsIDs) {
                $res->where("data_updated", true)->where("data_verified", true)->whereIn("department_id", $myDepartmentsIDs);
            })->get();
            return view('manager.departmentBoss.trainersReport')->with(compact('users'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
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

    public function trainersReview()
    {
        try {
            if (!Auth::user()->isDepartmentManager()) {
                return view("error")->with('error', 'ليس لديك صلاحيات لدخول الى هذه الصفحة');
            }
            $myDepartmentsIDs = [];
            foreach (Auth::user()->manager->getMyDepartment() as $program) {
                foreach ($program->departments as $department) {
                    array_push($myDepartmentsIDs, $department->id);
                }
            }
            $users = User::with('trainer')->whereHas("trainer", function ($res) use ($myDepartmentsIDs) {
                $res->where("data_updated", true)->where("data_verified", false)->whereIn("department_id", $myDepartmentsIDs);
            })->get();
            return view('manager.departmentBoss.trainersReview')->with(compact('users'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function trainersReviewStore(Request $request)
    {
        $requestData = $request->validate([
            "national_id"   =>  'required|digits:10',
            'employer'      =>  'required|string|max:100|min:3',
            'qualification' =>  'required|in:bachelor,master,doctoral',
            'decision'      =>  'required|in:accept,reject,edit',
            'note'          =>  'nullable|string',


        ]);
        try {
            $myDepartmentsIDs = [];
            foreach (Auth::user()->manager->getMyDepartment() as $program) {
                foreach ($program->departments as $department) {
                    array_push($myDepartmentsIDs, $department->id);
                }
            }
            $user = User::with('trainer')->whereHas("trainer", function ($res) use ($myDepartmentsIDs) {
                $res->where("data_verified", false)->whereIn("department_id", $myDepartmentsIDs);
            })->where("national_id", $requestData['national_id'])->first() ?? null;
            if ($user == null) {
                return response()->json(['message' => 'خطأ غير معروف'], 422);
            }
            switch ($requestData['decision']) {
                case 'accept':
                    $user->trainer->data_verified = true;
                    $user->trainer->data_verify_note = null;
                    break;
                case 'edit':
                    $user->trainer->employer = $requestData['employer'];
                    $user->trainer->qualification = $requestData['qualification'];
                    $user->trainer->data_verified = true;
                    $user->trainer->data_verify_note = null;
                    break;
                case 'reject':
                    $user->trainer->data_updated = false;
                    $user->trainer->data_verified = false;
                    $user->trainer->data_verify_note = $requestData['note'];
                    break;
                default:
                    return response()->json(['message' => 'خطأ غير معروف'], 422);
            }
            $user->trainer->save();
            return response()->json(['message' => 'تم معالجة الطلب بنجاح'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    // public function getTrainer($national_id)
    // {
    //     try {

    //         $user = User::with('trainer')->where("national_id", $national_id)->whereHas("trainer")->first();
    //         if ($user == null) {
    //             return back()->with("error", "لا يوجد مدرب بهذا الرقم");
    //         }
    //         return view('manager.departmentBoss.trainers.editTrainerForm')->with(compact('user'));
    //     } catch (Exception $e) {
    //         Log::error($e->getMessage() . ' ' . $e);
    //         return view('error')->with("error", "حدث خطا غير معروف");
    //     }
    // }


    public function getTrainerInfo($id)
    {
        try {
            $deptIds = [];
            $programs = Auth::user()->manager->getMyDepartment();
            foreach ($programs as $program) {
                foreach ($program->departments as $department) {
                    array_push($deptIds, $department->id);
                }
            }
            $user = User::with('trainer.department')->where("national_id", $id)->whereHas("trainer", function ($res) use ($deptIds) {
                $res->whereIn('department_id', $deptIds);
            })->first();
            if ($user == null) {
                $user = User::with('trainer.department')->whereHas("trainer", function ($res) use ($id, $deptIds) {
                    $res->whereIn('department_id', $deptIds)->where("bct_id", $id);
                })->first();
                if ($user == null) {
                    return response()->json(['message' => 'لا يوجد مدرب بهذا الرقم'], 422);
                }
            }
            return response()->json($user, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return response()->json(["message" => "خطأ غير معروف"]);
        }
    }

    public function manageTrainersForm()
    {
        try {
            return view("manager.departmentBoss.trainers.manage");
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with('error', ' حدث خطأ غير معروف ' . $e->getCode());
        }
    }

    public function createTrainerForm()
    {
        try {
            $deptIds = [];
            $programs = Auth::user()->manager->getMyDepartment();
            foreach ($programs as $program) {
                foreach ($program->departments as $department) {
                    array_push($deptIds, $department->id);
                }
            }
            $departments = Department::whereIn('id', $deptIds)->get()->unique('name');
            return view("manager.departmentBoss.trainers.create")->with(compact('departments'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with('error', 'خطأ غير معرف');
        }
    }


    public function createTrainerStore(Request $request)
    {
        $requestData = $this->validate($request, [
            "national_id"   => 'required|digits:10',
            'bct_id'        => 'required|digits_between:3,20|unique:trainers,bct_id',
            'name'          => 'required|string|min:3|max:100',
            "phone"         => 'nullable|digits_between:9,14',
            "email"             => "required|email|unique:users,email",
            'employer'      => 'required|string|max:100|min:3',
            "department"    => "required|numeric|exists:departments,id",
            // 'qualification' => 'required|in:bachelor,master,doctoral',
            // "degree"        => 'required|mimes:pdf,png,jpg,jpeg|max:4000',


        ]);

        try {
            DB::beginTransaction();
            $user = User::create([

                'national_id' => $requestData['national_id'],
                'name'        => $requestData['name'],
                'phone'       => $requestData['phone'],
                'email'        => $requestData['email'],
                'password'    => Hash::make("bct12345"),
            ]);

            $user->trainer()->create([
                "bct_id"     => $requestData['bct_id'],
                "department_id" => $requestData['department'],
                "employer"   => $requestData['employer'],

            ]);
            // $user->trainer->department_id = $requestData['department'];
            // $user->trainer->qualification = $requestData['qualification'];
            // $user->trainer->employer      = $requestData['employer'];

            // $doc_name = 'degree.' . $requestData['degree']->getClientOriginalExtension();
            // Storage::disk('trainerDocuments')->put('/' . $user->national_id . '/' . $doc_name, File::get($requestData['degree']));

            DB::commit();
            return redirect(route("createTrainerForm"))->with('success', 'تم اضافة المتدرب بنجاح');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with('error', ' حدث خطأ غير معروف ' . $e->getCode());
        }
    }
    public function editTrainerForm()
    {
        try {
            $deptIds = [];
            $programs = Auth::user()->manager->getMyDepartment();
            foreach ($programs as $program) {
                foreach ($program->departments as $department) {
                    array_push($deptIds, $department->id);
                }
            }
            $departments = Department::whereIn('id', $deptIds)->get()->unique('name');
            return view("manager.departmentBoss.trainers.edit")->with(compact('departments'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with("error", "تعذر ارسال الطلب حدث خطا غير معروف");
        }
    }
    public function editTrainerUpdate(Request $request, User $user)
    {

        $requestData = $this->validate($request, [
            "national_id"   => 'required|digits:10',
            'bct_id'        => 'required|digits_between:3,20|unique:trainers,bct_id,' . $user->trainer->id,
            'name'          => 'required|string|min:3|max:100',
            "email"         => "required|email|unique:users,email," . $user->id,
            "phone"         => 'nullable|digits_between:9,14',
            "department"    => "required|numeric|exists:departments,id",
            'qualification' => 'required|in:bachelor,master,doctoral',
            'employer'      => 'required|string|max:100|min:3',
            // "degree"        => 'required|mimes:pdf,png,jpg,jpeg|max:4000',


        ]);

        try {
            DB::beginTransaction();

            $user->national_id = $requestData['national_id'];
            $user->name = $requestData['name'];
            $user->phone = $requestData['phone'];
            $user->email = $requestData['email'];
            $user->save();

            $user->trainer->bct_id = $requestData['bct_id'];
            $user->trainer->qualification = $requestData['qualification'];
            $user->trainer->employer      = $requestData['employer'];
            if ($user->trainer->department_id != $requestData['department']) {
                $deptIds = [];
                $programs = Auth::user()->manager->getMyDepartment();
                foreach ($programs as $program) {
                    foreach ($program->departments as $department) {
                        array_push($deptIds, $department->id);
                    }
                }
                if (!in_array($user->trainer->department_id, $deptIds)) {
                    $user->trainer->department_id = $requestData['department'];
                } else {
                    return back()->with('error', 'لا تملك صلاحية لهذا القسم');
                }
            }
            $user->trainer->save();

            // if (isset($requestData['degree'])) {
            //     $doc_name = 'degree.' . $requestData['degree']->getClientOriginalExtension();
            //     Storage::disk('trainerDocuments')->put('/' . $user->national_id . '/' . $doc_name, File::get($requestData['degree']));
            // }

            DB::commit();
            return redirect(route("editTrainerForm"))->with('success', 'تم التعديل المتدرب بنجاح');
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
                if ($courseOrder->accepted_by_dept_boss != true) {
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
