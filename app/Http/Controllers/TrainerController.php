<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Program;
use App\Models\Semester;
use App\Models\Trainer;
use App\Models\TrainerCoursesOrders;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                "name" => "إنشاء عقد تدريبي",
                "url" => route("addCoursesToTrainerView")
            ],
        ];
        return view("trainer.dashboard")->with(compact("links", "title"));
    }

    public function addCoursesToTrainerView()
    {
        try {
            $programs = Program::with("departments.majors.courses")->get();
            $user = Auth::user();
            return view('trainer.addCourses')->with(compact('programs', 'user'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return view("error")->with("error", "حدث خطأ غير معروف".$e);
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
