<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use App\Models\User;
use Illuminate\Http\Request;
use Storage;

class ManagerController extends Controller
{


    public function community_all_student_form()
    {

       $users = User::with('student')->whereHas('student',function($result){
        $result->where('traineeState','!=','privateState');
       })->get();

       for($i=0; $i<count($users); $i++){
       // $documents = Storage::disk('studentDocuments')->files($user->national_id);
        $users[$i]['receipts'] = Storage::disk('studentDocuments')->files($users[$i]->national_id.'/receipts');
       }
    //    dd($users[0]);



       return view('manager.community.all_student')->with(compact('users'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function show(Manager $manager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function edit(Manager $manager)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Manager $manager)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manager $manager)
    {
        //
    }
}
