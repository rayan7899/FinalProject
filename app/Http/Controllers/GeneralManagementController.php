<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $title = "الإدارة العامة";
        $links = [
            (object) [
                "name" => "تقرير طلبات الشحن",
                "url" => route("paymentsReport")
            ],
        ];
        return view("manager.community.dashboard")->with(compact("links", "title"));
    }
}
