<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Course;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;


class StudentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('agreement')->except(['agreement_form', 'agreement_submit']);
    }


    public function edit()
    {
        $user = Auth::user();
        $courses = Course::where('suggested_level', $user->student->level)
            ->where('major_id', $user->student->major_id)
            ->get();
        $major_courses = null;

        if ($user->student->studentState != false) {
            $courses_id = array_map(
                function ($c) {
                    return $c['id'];
                },
                $courses->toArray()
            );
            if ($user->student->level > 1) {
                $major_courses = Course::where('major_id', $user->student->major_id)
                    ->whereNotIn('id', $courses_id)
                    ->get();
            }

            return view('student.form')->with(compact('user', 'courses', 'major_courses'));
        } else {
            if ($user->student->level > 1) {
                $major_courses = Course::where('major_id', $user->student->major_id)
                    ->get();
            }
            $courses = [];

            return view('student.form')->with(compact('user', 'courses', 'major_courses'));
        }
        // if (!$user->student->data_updated) {
        //     return view('student.form')->with(compact('user', 'courses'));
        // } else {
        //     return view('home')->with('error', 'تم تقديم الطلب مسبقاً')->with(compact('user'));
        // }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $studentData = $this->validate($request, [
            "email"             => "required|email|unique:users,email," . $user->id,
            "identity"          => "required|mimes:pdf,png,jpg,jpeg|max:4000",
            "degree"            => "required|mimes:pdf,png,jpg,jpeg|max:4000",
            "payment_receipt"   => "required_if:traineeState,trainee,employee,employeeSon|mimes:pdf,png,jpg,jpeg|max:4000",
            "traineeState"      => "required|string",
            "cost"              => "required|numeric",
            "privateStateDoc"   => "required_if:traineeState,privateState",
            "courses"           => "required|array|min:1",
            "courses.*"         => "required|numeric|distinct|exists:courses,id",
        ], [
            'payment_receipt.required_if' => 'إيصال السداد مطلوب',
            'courses.required' => 'لم تقم باختيار المواد',
        ]);

        $total_hours = array_sum(array_map(
            function ($c) {
                return $c['credit_hours'];
            },
            Course::whereIn('id', $studentData['courses'])->get()->toArray()
        ));
        if ($total_hours < 11 || $total_hours > 21) {
            return back()->with('error', 'يجب أن يكون مجموع ساعات الجدول بين 11 و 21');
        }

        try {
            DB::beginTransaction();
            $user->update(
                array(
                    'email' => $studentData['email']
                )
            );

            $user->student()->update(
                array(
                    'traineeState' => $studentData['traineeState'],
                    'wallet'       => $studentData['cost'],
                    'data_updated' => true,
                )
            );

            $courses = $studentData['courses'];
            if ($user->student->level < 2) {
                $courses = [];
                foreach (Course::where('suggested_level', $user->student->level)
                    ->where('major_id', $user->student->major_id)
                    ->get()
                    as $course) {
                    $courses[] = $course->id;
                }
            }
            foreach ($courses as $course) {
                $user->student->studentCourses()->create([
                    'course_id' => $course,
                ]);
            }

            $national_id = Auth::user()->national_id;

            $doc_name = 'identity.' . $studentData['identity']->getClientOriginalExtension();
            Storage::disk('studentDocuments')->put('/' . $national_id . '/' . $doc_name, File::get($studentData['identity']));

            $doc_name = 'degree.' . $studentData['degree']->getClientOriginalExtension();
            Storage::disk('studentDocuments')->put('/' . $national_id . '/' . $doc_name, File::get($studentData['degree']));

            if ($studentData['traineeState'] != 'privateState') {
                $doc_name =  date('Y-m-d-H-i') . '_payment_receipt.' . $studentData['payment_receipt']->getClientOriginalExtension();
                Storage::disk('studentDocuments')->put('/' . $national_id . '/receipts/' . $doc_name, File::get($studentData['payment_receipt']));
            } else {
                $doc_name =  date('Y-m-d-H-i') . '_privateStateDoc.' . $studentData['privateStateDoc']->getClientOriginalExtension();
                Storage::disk('studentDocuments')->put('/' . $national_id . '/privateStateDoc/' . $doc_name, File::get($studentData['privateStateDoc']));
            }

            DB::commit();

            return redirect(route('home'))->with('success', ' تم تقديم الطلب بنجاح');
        } catch (\Throwable $e) {
            DB::rollback();
            Log::error($e);
            return back()->with('error', ' تعذر تحديث بيانات تقديم الطلب حدث خطأ غير معروف ' . $e->getCode());
        }
    }

   
    // Route: type GET | URL: /student/delete | route name DeleteOneStudent
    public function destroy()
    {
        $user = Auth::user();
        $user->student()->update([
            'wallet'                => 0,
            'documents_verified'    => false,
            'traineeState'          => 'trainee',
            'note'                  => null,
            'data_updated'          => false
        ]);
        $dir = Storage::disk('studentDocuments')->exists($user->national_id);
        if ($dir) {
            $result = Storage::disk('studentDocuments')->deleteDirectory($user->national_id);
            if ($result) {
                return back()->with('success', 'تم حذف الطلب بنجاح');
            } else {
                return back()->with('error', 'تعذر حذف الطلب حدث خطأ غير معروف');
            }
        } else {
            return back()->with('error', 'لا يجود طلب لحذفة');
        }
    }

    public function agreement_form()
    {
        $user = Auth::user();
        $student = $user->student;

        // if (Hash::check("bct12345", $user['password'])) {
        //     return redirect(route('UpdatePasswordForm'))->with('info', 'يرجى تغيير كلمة المرور الافتراضية');
        // }
        if ($student->agreement == 1) {
            return redirect(route('EditOneStudent'));
        } else {
            $error =  'يجب الموافقة لإكمال التسجيل';
            return view("student.agreement_from")->with(compact('error'));
        }
    }

    public function agreement_submit(Request $request)
    {
        // dd($request);
        if ($request->input('agree') == 1) {

            $user = Auth::user();

            try {
                $user->student()->update(['agreement' => true]);
            } catch (\Throwable $ex) {
                return back()->with('error', 'خطأ أثناء اعتماد الموافقة');
            }
            return redirect(route('EditOneStudent'));
        } else {
            return redirect(route('AgreementForm'))->with('error', 'يجب الموافقة على الشروط اولا');
        }
    }



    public function UpdatePasswordForm()
    {
        return view('student.update_password');
    }


    public function UpdatePassword(Request $request)
    {
        $newpass = $this->validate($request, [
            'password' => 'required|string|min:8|confirmed',
        ]);
        if ($newpass['password'] != "bct12345") {
            Auth::user()->update([
                'password' => Hash::make($newpass['password']),
            ]);

            return redirect(route('AgreementForm'))->with('succuss', 'تم تغيير كلمة المرور بنجاح');
        } else {
            return back()->with('error', 'خطأ يجب تغيير كلمة المرور الفتراضية');
        }

        return back()->with('error', 'تعذر تغيير كلمة المرور حدث خطأ غير معروف');
    }

    public function getStudentOnLevel(Request $request)
    {
        $formData = $this->validate($request, [
            'level' => 'required|numeric|max:5|min:1',
            'program' => 'required|numeric|max:10|min:1',
            'department' => 'required|numeric|max:100|min:1',
            'major' => 'required|numeric|max:200|min:1',
        ]);

        try {
            $students = User::with('student')->whereHas('student', function ($result) use ($formData) {
                $result->where('level', $formData['level'])
                    ->where('program_id', $formData['program'])
                    ->where('department_id', $formData['department'])
                    ->where('major_id', $formData['major']);
            })->get();
            if (count($students) > 0) {
                return response()->json(['message' => 'تم جلب البيانات بنجاح', 'students' => $students], 200);
            } else {
                return response()->json(['message' => 'لا يوجد متدربين', 'students' => $students], 480);
            }
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['message' => ' حدث خطأ غير معروف, تعذر جلب بيانات المتدربين ' . "<p>" . $e->getCode() . "</p>"], 422);
        }
    }

    public function updateStudentState(Request $request)
    {
        $studentData = $this->validate($request, [
            'national_id' => 'required|string|max:10|min:10',
            'studentState' => 'required|boolean',
        ]);
        try {
            $user = User::with('student')->where('national_id', $studentData['national_id'])->first();
            if (isset($user)) {
                $result = $user->student()->update([
                    'studentState' => $studentData['studentState']
                ]);
                return response('ok', 200);
            } else {
                return response()->json(["message" => "لا يوجد متدرب برقم الهوية المرسل"], 422);
            }
        } catch (QueryException $e) {
            return response()->json(["message" => "لا يوجد متدرب برقم الهوية المرسل"], 422);
        }
    }


    public function getStudentInfo($id)
    {
        try {
            $userInfo = User::with('student.courses')->whereHas('student', function ($result) use ($id) {
                $result->where('national_id', $id)->orWhere('rayat_id', $id);
            })->first();
            if (isset($userInfo)) {
                return response()->json($userInfo, 200);
            } else {
                return response()->json(["message" => "لا يوجد متدرب بهذا الرقم"], 422);
            }
        } catch (QueryException $e) {
            return response()->json(["message" => "لا يوجد متدرب بهذا الرقم"], 422);
        }
    }
}
