<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $users = User::with(['department','major'])->get();

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        /*
         @Rayan you can access $user varebal from view for Ex:
             to print user name:
         {{$user->name}}
            to print department:
        {{$user->department->name}}
        */

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
        $user = Auth::user();

        return view('user.form')->with(compact('user'));
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


        $userData = $this->validate($request, [
            "phone" => "required|digits:10",
            "email" => "required|email",
        ]);

        try {

            Auth::user()->update($userData);
            return redirect('/home');

        } catch (\Throwable $e) {

            echo $e;

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function agreement_form()
    {
        $user = Auth::user();
        if ($user->agreement == 1) {
            return redirect(route('EditOneUser'));
        } else {
            $error =  'يجب الموافقة لإكمال التسجيل';
            return view("user.agreement_from")->with(compact('error'));
        }
    }

    public function agreement_submit(Request $request)
    {
       // dd($request);
       if ($request->input('agree') == 1) {

           $user = Auth::user();
           try {
               $user->update(['agreement' => true]);
           } catch(Throwable $ex) {
               return back()->with('error', 'خطأ أثناء اعتماد الموافقة');
           }
           return redirect(route('EditOneUser'));
       } else {
           return redirect(route('AgreementForm'));
       }
    }
}
