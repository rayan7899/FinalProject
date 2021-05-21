<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'agreement']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->manager !== null) {
            if ($user->manager->hasRole("شؤون المتدربين")) {
                return redirect(route('affairsDashboard'));
            }
            if ($user->manager->hasRole("خدمة المجتمع")) {
                return redirect(route('communityDashboard'));
            }
            if ($user->manager->hasRole("الإرشاد")) {
                return redirect(route('privateDashboard'));
            }
            if ($user->isDepartmentManager()) {
                return redirect(route('deptBossDashboard'));
            }
            return view("error")->with("error", "لا يوجد لديك اي صلاحيات");
        } else {
            // $user = User::with("student")->find(Auth::user()->id);
            $user = Auth::user();
            if (Hash::check("bct12345", $user->password)) {
                return view('student.form')->with(compact('user'));
            }
            if (count($user->student->orders) < 1) {
                return redirect(route('orderForm'));
            }
            return view('home')->with(compact('user'));
        }
    }
}
