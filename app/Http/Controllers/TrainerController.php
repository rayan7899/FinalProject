<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Program;
use App\Models\Trainer;
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
                "name" => "اضافة المقررات",
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $this->validate($request, [
            "orders"               => "required|array|min:1",
            "orders.*.course_id"             => "required|numeric|distinct|exists:courses,id",
            "orders.*.count_of_students"     => "required|numeric|min:1",
            "orders.*.count_of_divisions"    => "required|numeric|min:1",
        ]);
        try {
            $user = Auth::user();

            DB::beginTransaction();
            foreach ($requestData['orders'] as $order) {
                $order = $user->trainer->coursesOrders()->create([
                    'course_id'     =>  $order['course_id'],
                    'is_theoretical'     =>  true,
                    'is_practical'     =>  true,
                    'count_of_students'     =>  $order['count_of_students'],
                    'count_of_divisions'     =>  $order['count_of_divisions'],
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
