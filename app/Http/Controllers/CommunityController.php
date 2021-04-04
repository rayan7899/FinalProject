<?php

namespace App\Http\Controllers;


use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommunityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $links = (object)[
            (object) [
                "name" => "تجربة",
                "url" => "http://google.ocm"
            ],
            (object) [
                "name" => "تجربة ٢",
                "url" => "http://google.ocm"
            ],
        ];
        return view("manager.community.dashboard")->with(compact("links"));
    }

    public function studentDocumentsReviewForm()
    {

        $users = User::with('student')->whereHas('student', function ($result) {
            $result->where('traineeState', '!=', 'privateState')->where("data_updated", true);
        })->get();

        for ($i = 0; $i < count($users); $i++) {
            // $documents = Storage::disk('studentDocuments')->files($user->national_id);
            $users[$i]['receipts'] = Storage::disk('studentDocuments')->files($users[$i]->national_id . '/receipts');
        }
        return view('manager.community.studentDocumentsReview')->with(compact('users'));
    }


    public function studentDocumentsReviewJson()
    {

        $users = User::with('student')->whereHas('student', function ($result) {
            $result->where('traineeState', '!=', 'privateState');
        })->get();

        for ($i = 0; $i < count($users); $i++) {
            // $documents = Storage::disk('studentDocuments')->files($user->national_id);
            $users[$i]['receipts'] = Storage::disk('studentDocuments')->files($users[$i]->national_id . '/receipts');
            $users[$i]->progname = $users[$i]->student->program->name;
            $users[$i]->deptname = $users[$i]->student->department->name;
            $users[$i]->mjrname = $users[$i]->student->major->name;
        }
        //return view('manager.community.studentDocumentsReview')->with(compact('users'));
        return response(\json_encode(['data' => $users]), 200);
    }


    public function studentDocumentsReviewUpdate(Request $request)
    {
        $studentData = $this->validate($request, [
            "national_id"        => "required|numeric",
            "wallet"             => "required|numeric",
            "documents_verified" => "required|boolean",
            "note"               => "string|nullable"
        ]);



        try {
            $user = User::with('student')->where('national_id', $studentData['national_id'])->first();
            if ($user->student->traineeState == 'privateState') {
                $user->student()->update([
                    "documents_verified" => $studentData['documents_verified'],
                    "note"               => $studentData['note'],
                ]);
            } else {
                $user->student()->update([
                    "wallet"             => $studentData['wallet'],
                    "documents_verified" => $studentData['documents_verified'],
                    "note"               => $studentData['note'],
                ]);
            }


            return response(json_encode(['message' => 'تم تحديث البيانات بنجاح']), 200);
        } catch (Exception $e) {
            return response(json_encode(['message' => 'حدث خطأ غير معروف' . $e->getCode()]), 422);
        }
    }

    public function studentDocumentsReviewVerifiyDocs(Request $request)
    {

        $studentData = $this->validate($request, [
            "national_id"        => "required|numeric",
            "documents_verified" => "required|boolean"
        ]);

        try {
            $user = User::with('student')->where('national_id', $studentData['national_id'])->first();
            $user->student()->update([
                "documents_verified" => $studentData['documents_verified'],
            ]);

            return response(json_encode(['message' => 'تم تغيير الحالة بنجاح']), 200);
        } catch (Exception $e) {
            return response(json_encode(['message' => 'حدث خطأ غير معروف' . $e->getCode()]), 422);
        }
    }



    public function private_all_student_form()
    {
        $users = User::with('student')->whereHas('student', function ($result) {
            $result->where('traineeState', 'privateState');
        })->get();

        for ($i = 0; $i < count($users); $i++) {
            $users[$i]['docs'] = Storage::disk('studentDocuments')->files($users[$i]->national_id . '/privateStateDoc');
        }

        return view('manager.private.private_student')->with(compact('users'));
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
     * @param  \App\Models\  $
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\  $
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\  $
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\  $
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }
}
