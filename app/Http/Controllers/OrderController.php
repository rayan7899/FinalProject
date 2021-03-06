<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Order;
use App\Models\Semester;
use Carbon\Carbon;
use Exception;
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
      try {
         $user = Auth::user();
         $semester = Semester::latest()->first();
         if ($semester == null) {
            Log::info("there is no semester! ");
            return view('error')->with('error', 'حدث خطأ غير معروف');
         }elseif(!$semester->can_request_hours){
            return redirect(route("home"))->with('error', 'اضافة المقررات غير متاح في الوقت الحالي');
         }

         if ($user->student->available_hours >= 12) {
            return back()->with('error', 'الحد الاعلى للفصل الصيفي هو 12 ساعة');
         }

         $isHasActivePayment = $user->student->payments()->where("accepted", '=', null)->first() !== null;

         // $isHasActiveOrder = $user->student->orders()
         //    ->where("transaction_id", '=', null)
         //    ->where("private_doc_verified", true)
         //    ->orWhere("private_doc_verified",'=', null)->first() !== null;
         $isHasActiveRefund = $user->student->refunds()->where('accepted', null)->first() !== null;

         if ($user->student->level == 1 && $user->student->available_hours != 0) {
            return redirect(route("home"))->with("error", "اضافة المقررات غير متاح للمتدربين في المستوى الاول");
         } elseif ($isHasActiveRefund) {
            return redirect(route("home"))->with("error", "تعذر ارسال الطلب يوجد طلب استرداد تحت المراجعة");
         }

         // if ($isHasActivePayment || $isHasActiveOrder) {
         //    return view('error')->with("error", "تعذر ارسال الطلب يوجد طلب اضافة مقررات او شحن رصيد تحت المراجعة");
         //    //    return redirect(route("home"))->with("error", "تعذر ارسال الطلب يوجد طلب اضافة مقررات او شحن رصيد تحت المراجعة");
         // }


         for($i = 0; $i < count($user->student->orders); $i++){
            if($user->student->orders[$i]->transaction_id === null && $user->student->orders[$i]->private_doc_verified !== 0){
               return view('error')->with("error", "تعذر ارسال الطلب يوجد طلب اضافة مقررات او شحن رصيد تحت المراجعة");
            }
         }

         $courses = Course::where('suggested_level', $user->student->level)
            ->where('major_id', $user->student->major_id)
            ->get();
         $major_courses = null;

         if ($user->student->studentState == true && $user->student->available_hours == 0) {
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

            return view('student.orders.form')->with(compact('user', 'courses', 'major_courses', 'semester'));
         } else {
            if ($user->student->level > 1) {
               $major_courses = Course::where('major_id', $user->student->major_id)
                  ->get();
            }
            $courses = [];

            return view('student.orders.form')->with(compact('user', 'courses', 'major_courses', 'semester'));
         }
      } catch (Exception $e) {
         Log::error($e->getMessage() . ' ' . $e);
         return view('error')->with('error', 'حدث خطأ غير معروف');
      }
   }




   public function store(Request $request)
   {
      $requestData = $this->validate($request, [
         "courses"      => "required|array|min:1",
         "courses.*"    => "required|numeric|distinct|exists:courses,id",
         "traineeState"      => "required|string",
         "payment_receipt"   => "mimes:pdf,png,jpg,jpeg|max:10000",
         "privateStateDoc"   => "required_if:traineeState,privateState",
         "promise"           => "required_if:traineeState,privateState",
         "paymentCost"       => "required_with:payment_receipt|numeric|min:0|max:50000"
      ], [
         'courses.required' => 'لم تقم باختيار المقررات',
         'promise.required_if' => 'التعهد مطلوب ',
         'privateStateDoc.required_if' => 'يجب ارفاق المستندات المطلوبة',
         'paymentCost.required_if' => 'لا يمكن ترك حقل المبلغ المسجل في الايصال فارغ',
      ]);
      try {
         $user = Auth::user();
         $semester = Semester::latest()->first();
         // $waitingPaymentssCount = $user->student->payments()->where("accepted", null)->count();
         // $waitingOrdersCount = $user->student->orders()
         //    ->where("transaction_id", null)
         //    ->where("private_doc_verified", true)
         //    ->orWhere("private_doc_verified",'=', null)->count();

         // if ($waitingPaymentssCount > 0 || $waitingOrdersCount > 0) {
         //    return view('error')->with("error", "تعذر ارسال الطلب يوجد طلب اضافة مقررات او شحن رصيد تحت المراجعة");
         //    // return redirect(route("home"))->with("error", "تعذر ارسال الطلب يوجد طلب اضافة مقررات او شحن رصيد تحت المراجعة");
         // }

         for($i = 0; $i < count($user->student->orders); $i++){
            if($user->student->orders[$i]->transaction_id === null && $user->student->orders[$i]->private_doc_verified !== 0){
               return view('error')->with("error", "تعذر ارسال الطلب يوجد طلب اضافة مقررات تحت المراجعة");
            }
         }

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

            if (($total_hours > 12 && $semester->isSummer == true) || ($user->student->available_hours + $total_hours > 12 &&  $semester->isSummer == true)) {
               return back()->with('error', 'الحد الاعلى للفصل الصيفي هو 12 ساعة');
            }

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
                        "amount"            => $requestData['paymentCost'],
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
      } catch (Exception $e) {
         Log::error($e->getMessage() . ' ' . $e);
         return view('error')->with('error', 'حدث خطأ غير معروف');
      }
   }

   public function deleteOrder(Request $request)
   {
      $requestData = $this->validate($request, [
         "order_id"    => "required|numeric|distinct|exists:orders,id",
      ]);
      try {
         $order = Order::where('id', $requestData['order_id'])->first();
         $user = Auth::user();
         if($user->id !== $order->student->user->id && !$user->hasRole('خدمة المجتمع')){
            return response()->json(["message" => "ليس لديك صلاحيات لتنفيذ هذا الامر"], 422);
         }
         if($order->transaction_id != null || $order->transaction_id !== null){
             return response()->json(["message" => "لا يمكن حذف طلب تم تدقيقه"], 422);
           }else{
             $order->delete();
             if($order->private_doc_file_id != null){
               Storage::disk('studentDocuments')->deleteDirectory('/'.$order->student->user->national_id.'/privateStateDocs/'.$order->private_doc_file_id);
             }
         }
         return response()->json(["message" => "تم حذف الطلب بنجاح"], 200);
      } catch (Exception $e) {
         Log::error($e->getMessage() . ' ' . $e);
         return response()->json(["message" => "حدث خطأ غير معروف تعذر حذف الطلب"], 422);
      }
   }
}
