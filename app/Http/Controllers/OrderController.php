<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{

   public function __construct()
   {
      $this->middleware('auth');
   }


   public function form()
   {
      $user = Auth::user();
      $waitingTransCount = $user->student->payments()->where("transaction_id", "=", null)->count();
      if ($waitingTransCount > 0) {
         return redirect(route("home"))->with("error", "تعذر ارسال الطلب يوجد طلب سابق تحت المراجعة");
      }
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

         return view('student.orders.form')->with(compact('user', 'courses', 'major_courses'));
      } else {
         if ($user->student->level > 1) {
            $major_courses = Course::where('major_id', $user->student->major_id)
               ->get();
         }
         $courses = [];

         return view('student.orders.form')->with(compact('user', 'courses', 'major_courses'));
      }
   }




   public function store(Request $request)
   {
      $user = Auth::user();

      $requestData = $this->validate($request, [
         "courses"      => "required|array|min:1",
         "courses.*"    => "required|numeric|distinct|exists:courses,id",
         "traineeState"      => "required|string",
         "payment_receipt"   => "required_if:traineeState,trainee,employee,employeeSon|mimes:pdf,png,jpg,jpeg|max:4000",
         "privateStateDoc"   => "required_if:traineeState,privateState",
      ],[
         'payment_receipt.required_if' => 'إيصال السداد مطلوب',
         'courses.required' => 'لم تقم باختيار المقررات',
     ]);


      try {
         $total_hours = array_sum(array_map(
            function ($c) {
               return $c['credit_hours'];
            },
            Course::whereIn('id', $requestData['courses'])->get()->toArray()
         ));
         $amount = $total_hours * 550;

         if ($total_hours < 9 || $total_hours > 21) {
            return back()->with('error', 'يجب أن يكون مجموع ساعات الجدول بين 9 و 21');
         }

         DB::beginTransaction();
         $courses = $requestData['courses'];
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

         // traineeState ------------------------------------------------------------

         if ($requestData["traineeState"] == "privateState") {

            $randomId =  uniqid();
            $doc_name =  date('Y-m-d-H-i') . '_privateStateDoc.' . $requestData['privateStateDoc']->getClientOriginalExtension();
            Storage::disk('studentDocuments')->put('/' . $user->national_id . '/privateStateDoc/' . $randomId . '/' . $doc_name, File::get($requestData['privateStateDoc']));

            $order = $user->student->orders()->create(
               [
                  "amount" => 0,
                  "requested_hours" => $total_hours,
                  "private_doc_file_id" => $randomId,
               ]
            );
         } else {


            
             $walletAfterCalc = $user->student->wallet - $amount;
            if ($walletAfterCalc < 0) {
               $cost = abs($walletAfterCalc);
               $randomId =  uniqid();
               $user->student->payments()->create(
                  [
                     "amount"            => $cost,
                     "receipt_file_id"   => $randomId
                  ]
               );
               $doc_name =  date('Y-m-d-H-i') . '_payment_receipt.' . $requestData['payment_receipt']->getClientOriginalExtension();
               Storage::disk('studentDocuments')->put('/' . $user->national_id . '/receipts/' . $randomId . '/' . $doc_name, File::get($requestData['payment_receipt']));
               
            }

            $user->student->wallet -= $amount;
            $user->student->save();
            $order = $user->student->orders()->create(
               [
                  "amount" => $amount,
                  "requested_hours" => $total_hours,
               ]
            );

            $transaction = $user->student->transactions()->create([
               "order_id"    => $order->id,
               "amount"        => $amount,
               "type"          => "deduction",
               "by_user"       => Auth::user()->id,
            ]);
            $order->update([
               "transaction_id" => $transaction->id,
            ]);
         }


         DB::commit();
         return redirect(route('home'))->with('success', ' تم تقديم الطلب بنجاح');
      } catch (\Throwable $e) {
         DB::rollback();
         Log::error($e);
         return back()->with('error', ' حدث خطأ غير معروف ' . $e->getCode());
      }
   }
}
