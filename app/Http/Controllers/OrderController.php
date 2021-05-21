<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Semester;
use Carbon\Carbon;
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
      $waitingPaymentssCount = $user->student->payments()->where("accepted", null)->count();
      $waitingOrdersCount = $user->student->orders()->where("transaction_id", null)
         ->where("private_doc_verified", "!=", false)->count();
      $isHasActiveRefund = $user->student->refunds->where('accepted', null)->first() !== null;
      if ($user->student->level == 1 && $user->student->credit_hours != 0) {
         return redirect(route("home"))->with("error", "اضافة المقررات غير متاح للمتدربين في المستوى الاول");
      }elseif ($isHasActiveRefund) {
         return redirect(route("home"))->with("error", "تعذر ارسال الطلب يوجد طلب استرداد تحت المراجعة");
      }

      if ($waitingPaymentssCount > 0 || $waitingOrdersCount > 0) {
         return view('error')->with("error", "تعذر ارسال الطلب يوجد طلب اضافة مقررات او شحن رصيد تحت المراجعة");
      //    return redirect(route("home"))->with("error", "تعذر ارسال الطلب يوجد طلب اضافة مقررات او شحن رصيد تحت المراجعة");
      }
      $courses = Course::where('suggested_level', $user->student->level)
         ->where('major_id', $user->student->major_id)
         ->get();
      $major_courses = null;

      if ($user->student->studentState == true && $user->student->credit_hours == 0) {
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
      $semester = Semester::latest()->first();
      $waitingPaymentssCount = $user->student->payments()->where("accepted", null)->count();
      $waitingOrdersCount = $user->student->orders()->where("transaction_id", null)
         ->where("private_doc_verified", "!=", false)->count();

      if ($waitingPaymentssCount > 0 || $waitingOrdersCount > 0) {
         return view('error')->with("error", "تعذر ارسال الطلب يوجد طلب اضافة مقررات او شحن رصيد تحت المراجعة");
         // return redirect(route("home"))->with("error", "تعذر ارسال الطلب يوجد طلب اضافة مقررات او شحن رصيد تحت المراجعة");
      }

      $requestData = $this->validate($request, [
         "courses"      => "required|array|min:1",
         "courses.*"    => "required|numeric|distinct|exists:courses,id",
         "traineeState"      => "required|string",
         "payment_receipt"   => "mimes:pdf,png,jpg,jpeg|max:4000",
         "privateStateDoc"   => "required_if:traineeState,privateState",
      ], [
         'courses.required' => 'لم تقم باختيار المقررات',
      ]);


      try {
         switch ($requestData["traineeState"]) {

            case 'employee':
               $discount = 0.25;
               break;
            case 'employeeSon':
               $discount = 0.5;
               break;
            default:
               $discount = 1;
               break;
         }
         $total_hours = array_sum(array_map(
            function ($c) {
               return $c['credit_hours'];
            },
            Course::whereIn('id', $requestData['courses'])->get()->toArray()
         ));
         $origAmount = $total_hours * $user->student->program->hourPrice;
         $amount = $origAmount  * $discount;
         $discountAmount = $origAmount - $amount;
         $walletAfterCalc = $user->student->wallet - $amount;

         if ($walletAfterCalc < 0 && !isset($requestData["payment_receipt"]) && $requestData["traineeState"] != "privateState") {
            return back()->with('error', ' ايصال السداد حقل مطلوب');
         }

         // if ($total_hours < 12 || $total_hours > 21) {
         //    return back()->with('error', 'يجب أن يكون مجموع ساعات الجدول بين 12 و 21');
         // }

         DB::beginTransaction();

         // $courses = $requestData['courses'];
         // if ($user->student->level < 2) {
         //    $courses = [];
         //    foreach (Course::where('suggested_level', $user->student->level)
         //       ->where('major_id', $user->student->major_id)
         //       ->get()
         //       as $course) {
         //       $courses[] = $course->id;
         //    }
         // }
         // foreach ($courses as $course) {
         //    $user->student->studentCourses()->create([
         //       'course_id' => $course,
         //    ]);
         // }

         // privateState ------------------------------------------------------------
         
         if ($requestData["traineeState"] == "privateState") {

            $randomId =  uniqid();
            $doc_name =  date('Y-m-d-H-i') . '_privateStateDoc.' . $requestData['privateStateDoc']->getClientOriginalExtension();
            Storage::disk('studentDocuments')->put('/' . $user->national_id . '/privateStateDocs/' . $randomId . '/' . $doc_name, File::get($requestData['privateStateDoc']));
            $user->student->traineeState = "privateState";
            $user->student->save();

            $user->student->orders()->create(
               [
                  "amount" => $amount,
                  "discount" => $discountAmount,
                  "requested_hours" => $total_hours,
                  "private_doc_file_id" => $randomId,
                  "semester_id"        => $semester->id,
               ]
            );
         } else {


            // Other traineeState ------------------------------------------------------------

            $walletAfterCalc = $user->student->wallet - $amount;
            if ($walletAfterCalc < 0) {
               $cost = abs($walletAfterCalc);
               $randomId =  uniqid();
               $doc_name =  $randomId . '.' . $requestData['payment_receipt']->getClientOriginalExtension();
               $user->student->payments()->create(
                  [
                     "amount"            => $cost,
                     "receipt_file_id"   => $doc_name,
                     "semester_id"        => $semester->id,

                  ]
               );
               Storage::disk('studentDocuments')->put('/' . $user->national_id . '/receipts/' . $doc_name, File::get($requestData['payment_receipt']));
            }

            $user->student->orders()->create(
               [
                  "amount" => $amount,
                  "discount" => $discountAmount,
                  "requested_hours" => $total_hours,
                  "private_doc_verified" => true,
                  "semester_id"        => $semester->id,
               ]
            );

            $user->student->traineeState = $requestData["traineeState"];
            $user->student->save();
         }
         $user->student()->update(
            array(
               'data_updated' => true,
            )
         );
         DB::commit();
         return redirect(route('home'))->with('success', ' تم تقديم الطلب بنجاح');
      } catch (\Throwable $e) {
         DB::rollback();
         Log::error($e->getMessage() . $e);
         return back()->with('error', ' حدث خطأ غير معروف ' . $e->getCode());
      }
   }
}
