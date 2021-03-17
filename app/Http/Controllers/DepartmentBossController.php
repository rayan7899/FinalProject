<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;

class DepartmentBossController extends Controller
{
    public function index()
    {
        $programs =  json_encode(Program::with('departments.majors.courses')->orderBy('name', 'asc')->get());
        // dd($programs);
        return view('departmentBoss.manageCourses')->with(compact('programs'));
    }
    
}
