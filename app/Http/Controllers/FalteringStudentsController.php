<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Major;

class FalteringStudentsController extends Controller
{
    //
    public function index()
    {
        $majors = Major::with('courses')->get();
        return view('manager.departmentBoss.falteringStudents')->with('majors', $majors);
    }

    
}
