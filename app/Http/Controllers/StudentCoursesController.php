<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentCoursesController extends Controller
{
    //
    public function getStudentCourses($id)
    {
        $info = User::with('student.studentCourses')->whereHas('student', function ($result) use ($id) {
            $result->where('national_id', $id)->orWhere('rayat_id', $id);
        })->get();
        dd($info);
        return response(['info' => $info], 200);
    }

    public function addCourseToStudent(Request $request)
    {
        $user = User::where('national_id', $request->studentNationalId)->first();
        $coursesData = $this->validate($request, [
            'studentNationalId'   => 'required|string|max:10|min:10',
            "courses.*"     => "required|numeric|distinct|exists:courses,id",
        ]);
        
        try {
            foreach ($coursesData['courses'] as $course) {
                if (!$user->student->courses->contains('id', $course)) {
                    $user->student->studentCourses()->create([
                        'course_id' => $course,
                    ]);
                }
            }
            return response(['message' => 'تمت اضافة المقررات بنجاح'], 200);
        } catch (QueryException $e) {
            return response(['message' => 'حدث خطأ غير معروف اثناء اضافة المقررات'], 422);
        }
    }


    public function deleteCourseFromStudent(Request $request)
    {
        try{
            StudentCourse::where('course_id', $request->studentCourseId)->delete();
            return response(['message' => 'تم حذف المقرر بنجاح'], 200);
        } catch (QueryException $e) {
            return response(['message' => $e->getMessage()], 422);
        }
    }
}
