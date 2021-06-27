<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Major;
use App\Models\Program;
use App\Models\Semester;
use App\Models\Trainer;
use App\Models\TrainerCoursesOrders;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class TrainerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $title = "مدرب";
        $links = [
            (object) [
                "name" => "اضافة المقررات",
                "url" => route("addCoursesToTrainerView")
            ],
            (object) [
                "name" => "المعلومات الشخصية",
                "url" => route("trainerInfo")
            ],
        ];
        return view("trainer.dashboard")->with(compact("links", "title"));
    }

    public function info()
    {
        $user = Auth::user();
        return view("trainer.info")->with(compact("user"));
    }


    public function updateNewForm()
    {
        if (Auth::user()->trainer->data_updated == false) {
            $user = Auth::user();
            $departments = Department::all()->unique('name');
            return view("trainer.updateNew")->with(compact('user', 'departments'));
        } else {
            return redirect(route("trainerDashboard"))->with('error', 'تم تحديث البيانات مسبقاً');
        }
    }

    /** @var User $user */
    public function updateNewStore(Request $request)
    {
        $requestData = $this->validate($request, [
            "national_id"   => 'required|digits:10',
            "phone"         => 'nullable|digits_between:9,14',
            "department"    => 'required|numeric|exists:departments,id',
            'qualification' =>  'required|in:bachelor,master,doctoral',
            'employer'      =>   'required|string|max:100|min:3',
            //"identity"      => "required|mimes:pdf,png,jpg,jpeg|max:4000",
            "degree"        => 'required|mimes:pdf,png,jpg,jpeg|max:4000',
            'password'      => 'string|min:8|confirmed',

        ]);
        if (isset($requestData['password'])) {
            if ($requestData['password'] == "bct12345") {
                return back()->with('error', 'خطأ يجب تغيير كلمة المرور الافتراضية')->withInput();
            }
        }
        try {
            DB::beginTransaction();
            $user = Auth::user();

            $user->national_id = $requestData['national_id'];
            if (isset($requestData['password'])) {
                $user->password = Hash::make($requestData['password']);
            }
            if (isset($requestData['phone'])) {
                $user->phone = $requestData['phone'];
            }
            $user->save();

            $user->trainer->department_id = $requestData['department'];
            $user->trainer->qualification = $requestData['qualification'];
            $user->trainer->employer      = $requestData['employer'];
            $user->trainer->data_updated  = true;

            $user->trainer->save();

            $doc_name = 'degree.' . $requestData['degree']->getClientOriginalExtension();
            Storage::disk('trainerDocuments')->put('/' . $user->national_id . '/' . $doc_name, File::get($requestData['degree']));

            DB::commit();
            return redirect(route("addCoursesToTrainerView"))->with('success', 'تم تحديث البيانات بنجاح');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with('error', 'حدث خطأ غير معروف');
        }
    }

    public function addCoursesToTrainerView()
    {
        try {
            $programs = Program::with("departments.majors.courses")->get();
            $user = Auth::user();
            return view('trainer.addCourses')->with(compact('programs', 'user'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return view("error")->with("error", "حدث خطأ غير معروف" . $e);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function isDivisionAvailable(Request $request)
    {
        try {
            $isDivisionAvailable = TrainerCoursesOrders::whereIn('division_number', $request->division_numbers)->exists();
            return response(['message' => $isDivisionAvailable], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . $e);
            return response(['error' => ' حدث خطأ غير معروف ' . $e], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $this->validate($request, [
            "orders"                      => "required|array|min:1",
            "orders.*.course_id"          => "required|numeric|exists:courses,id",
            "orders.*.count_of_students"  => "required|numeric|min:1",
            "orders.*.division_number"    => "required|numeric|min:1",
            "orders.*.course_type"        => "required|string|in:عملي,نظري",
        ]);
        try {
            $user = Auth::user();
            $semester = Semester::latest()->first();
            DB::beginTransaction();
            foreach ($requestData['orders'] as $order) {
                $order = $user->trainer->coursesOrders()->create([
                    'course_id'           =>  $order['course_id'],
                    'course_type'         =>  $order['course_type'],
                    'count_of_students'   =>  $order['count_of_students'],
                    'division_number'     =>  $order['division_number'],
                    'semester_id'         =>  $semester->id,
                ]);
            }
            DB::commit();
            return response(['message' => 'تم ارسال الطلب بنجاح '], 200);
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage() . $e);
            return response(['error' => ' حدث خطأ غير معروف ' . $e], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Trainer  $trainer
     * @return \Illuminate\Http\Response
     */
    public function show(Trainer $trainer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Trainer  $trainer
     * @return \Illuminate\Http\Response
     */
    public function edit(Trainer $trainer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Trainer  $trainer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trainer $trainer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Trainer  $trainer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trainer $trainer)
    {
        //
    }
}
