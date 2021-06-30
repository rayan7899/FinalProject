<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeanController extends Controller
{
    public function dashboard()
    {
        $title = "المشرف العام";
        $links = [
            (object) [
                "name" => "عقود التدريب",
                "url" => route("deanCoursesOrdersReportView")
            ],
        ];
        return view("manager.dean.dashboard")->with(compact("links", "title"));
    }


    public function coursesOrdersReportView()
    {
        try {
            $semester = Semester::latest()->first();
            $users = User::with('trainer.coursesOrders')->has('trainer.coursesOrders')
                ->wheredoesntHave('trainer.coursesOrders', function ($res) {
                    $res->where(function ($res) {
                        $res->where('accepted_by_dept_boss', true)
                            ->Where('accepted_by_community', false);
                    })
                        ->orWhere(function ($res) {
                            $res->where('accepted_by_dept_boss', true)
                                ->where('accepted_by_community', null);
                        })
                        ->orWhere(function ($res) {
                            $res->where('accepted_by_dept_boss', null)
                                ->where('accepted_by_community', null);
                        });
                })
                // ->whereHas('trainer.coursesOrders', function($res){
                //     $res->where('accepted_by_dept_boss', true)
                //         ->where('accepted_by_community', true);
                // })
                ->get();
            return view('manager.dean.trainersContracts')->with(compact('users'));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with('error', $e);
        }
    }
}
