<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://www.tvtc.gov.sa/css/rayat.css"> -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"
        defer></script>
</head>

<body class="container-fluid text-right" @isset($print) onload="window.print()" @endisset>
<div dir="rtl">
    <p class="text-center h4 my-3">عقد تدريب</p>
    <h5>برنامج التدريب التطبيقي المسائي في الكليات التقنية- للعام التدريبي {{$contractData[0]->semester->name}}هـ الفصل 
        (
        @if($contractData[0]->semester->which_semester == 1)√ @else   □@endif
            الأول
         - 
        @if($contractData[0]->semester->which_semester == 2)√ @else   □@endif
          الثاني
        -
        @if($contractData[0]->semester->isSummer)√ @else   □@endif
        الصيفي)</h5>
    <p>استناداً إلى لائحة مركز خدمة المجتمع والتدريب المستمر المعتمدة من مجلس إدارة المؤسسة العامة للتدريب التقني والمهني بجلسته (103) بتاريخ 1/3/1436هـ، ونظراً لحاجة (اسم المنشأة التدريبية) لكادر مؤهل للقيام بالتدريب ببرنامج التدريب المسائي فقد تم الاتفاق بين كل من :
    </p>
    <p>الطرف الأول: المشرف على برنامج التدريب التطبيقي المسائي بـالكلية التقنية ببريده</p>
    <p>الطرف الثاني: ممثلاً عن نفسه وفق البيان أدناه.</p>
    <p>□ من منسوبي المؤسسة             □ منفذ خارجي</p>
    <table class="table">
        <thead>
            <tr>
                <th>اسم المدرب</th>
                <th>رقم الهوية</th>
                <th>رقم الحاسب </th>
                <th>التخصص</th>
                <th>المؤهل</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$contractData[0]->trainer->user->name ?? 'لا يوجد'}}</td>
                <td>{{$contractData[0]->trainer->user->national_id ?? 'لا يوجد'}}</td>
                <td>{{$contractData[0]->trainer->computer_number ?? 'لا يوجد'}}</td>
                <td>{{$contractData[0]->trainer->department->name ?? 'لا يوجد'}}</td>
                <td>{{$contractData[0]->trainer->qualification ?? 'لا يوجد'}}</td>
            </tr>
        </tbody>
    </table>
    <h5>أولاً: هدف العقد ومدته</h5>
    <ol>
        <li>اتفق الطرفان على قيام الطرف الثاني بتدريب المقرر / المقررات التالي في برنامج التدريب التطبيقي المسائي للفصل التدريبي (     @if($contractData[0]->semester->isSummer) الصيفي @elseif($contractData[0]->semester->which_semester == 1) الاول @elseif($contractData[0]->semester->which_semester == 2) الثاني @endif    ) من العام التدريبي (                {{$contractData[0]->semester->name}}                ) والذي تحدده المؤسسة :</li>
        <table class="table">
            <thead>
                <tr>
                    <th>وصف المقرر</th>
                    <th>الرمز</th>
                    <th>رقم الشعبة </th>
                    <th>عدد المتدربين</th>
                    <th>عدد الساعات المعتمدة</th>
                    <th>عدد ساعات الاتصال الاسبوعية</th>
                    <th>عدد ساعات الاختبار</th>
                    <th>عدد الاسابيع الفصلية المتوقع</th>
                    <th>اجمالي عدد ساعات الاتصال الفصلية المتوقعة</th>
                    <th>المخصص اجر الساعة بالريال</th>
                    <th>اجمالي الاستحقاق المتوقع بالريال</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @if (isset($contractData))
                    @forelse ($contractData as $courseOrder)
                        @php
                            $count_of_weeks = $courseOrder->semester->count_of_weeks;
                            $hour_cost = $courseOrder->trainer->qualification == 'دكتوراه' ? 200 : 150;
                            $contact_hours = $courseOrder->course_type == 'نظري' ? $courseOrder->course->theoretical_hours : $courseOrder->course->practical_hours;
                            $exam_hours = $courseOrder->course_type == 'نظري' ? $courseOrder->course->exam_theoretical_hours : $courseOrder->course->exam_practical_hours;
                            $deserved_amount = ($count_of_weeks*$contact_hours+$exam_hours)*$hour_cost;
                            $total += $deserved_amount;
                        @endphp
                        <tr>
                            <td>{{$courseOrder->course->name ?? 'لا يوجد'}}</td>
                            <td>{{$courseOrder->course->code ?? 'لا يوجد'}}</td>
                            <td>{{$courseOrder->division_number}}</td>
                            <td>{{$courseOrder->count_of_students ?? 'لا يوجد'}}</td>
                            <td>{{$courseOrder->course->credit_hours ?? 'لا يوجد'}}</td>
                            <td>{{$contact_hours ?? 'لا يوجد'}}</td>
                            <td>{{$exam_hours ?? 'لا يوجد'}}</td>
                            <td>{{$count_of_weeks ?? 'لا يوجد'}}</td>
                            <td>{{$count_of_weeks*$contact_hours ?? 'لا يوجد'}}</td>
                            <td>{{$hour_cost ?? 'لا يوجد'}}</td>
                            
                            <td>{{$deserved_amount ?? 'لا يوجد'}}</td>
                        </tr>
                    @empty
                        لايوجد
                    @endforelse
                @endif
                <tr class="">
                    <td colspan="10" class="text-center">إجمالي الاستحقاق المتوقع</td>
                    <td>{{$total}}</td>
                </tr>
            </tbody>
        </table>
        <li>يُحسب الاستحقاق الفعلي للطرف الثاني على أساس إجمالي الساعات التدريبية الفعلية مضافاً لها ساعات الاختبارات والمراقبات النهائية (غير شامل لأسابيع التسجيل والتهيئة) بعد إنهاء المهمة من رئيس القسم المشارك واعتماده من الهيئة الإشرافية للبرنامج.
        </li>
        <li>يتم دفع الاستحقاق بعد نهاية أعمال الفصل التدريبي وخلال (30) يوم من اكتمال مسوغات الصرف.
        </li>
    </ol>
    <h5>ثانياً: التزامات الطرف الثاني</h5>
    <ol>
        <li>التقيد بالمواعيد المحددة للمحاضرات بدءاً وانتهاءً، واماكن التدريب من قاعات ومعامل ومختبرات وورش .. الخ,  والمحددة  له في الجدول التدريبي على نظام رايات ، ولا يجوز له التغيير أو التعديل إلا بموافقه رسمية من قبل مسؤول القسم التدريبي المشارك.</li>
        <li>تقديم الخطة التدريبية للمقررات في بداية الفصل التدريبي لاعتمادها من مسؤول القسم التدريبي المشارك وكذلك الالتزام بالتدريب بلغة البرنامج المعتمدة.</li>
        <li>التحضير الإلكتروني للمتدربين أسبوعياً على نظام رايات.
        </li>
        <li>إدخال درجات المتدربين على نظام رايات أولاً بأول وإشعارهم بذلك، والالتزام بإدخال نتائج المتدربين للاختبارات النهائية خلال (٤٨) ساعة من عقد الاختبار النهائي.
        </li>
        <li>الرد على الإفادات الواردة له عن الغياب أو عدم الالتزام بالمواعيد المحددة للمحاضرات بدءاً وانتهاءً، والذي سيطبق عليه الإجراءات التالية: 
             <ul style="list-style: none">
                <li>أ- إذا كان غياب الطرف الثاني أو عدم التزامه بالمواعيد المحددة للمحاضرات بدءاً وانتهاءً بدون عذر و في حال عدم الرد على الافادات الواردة له فيتم حسم ما يعادل (1.5) ساعة عن كل ساعة من المستحقات المتفق عليها في (أولاً).</li>
                <li>ب – إذا كان غياب الطرف الثاني أو عدم التزامه بالمواعيـــــد المحددة للمحاضـــــرات بدءاً وانتهاءً بعذر مقبـــول،فيتم حســــم ما يعادل         ( 1 ) ساعة عن كل ساعة من المستحقات المتفق عليها في (أولاً).</li>
            </ul>
        </li>
        <li>التدريب للفترة المحددة له في البند (أولاً) كاملة، وليس له حق المطالبة باستحقاقاته عن الفترة التي نفذها في حال عدم إتمامه التدريب لكامل الفترة المتفق عليها بين الطرفين في البرنامج إلا بعذر تقبله الهيئة الإشرافية للبرنامج.
        </li>
        <li>التقيد بالزي الرسمي والالتزام بالمحافظة على الأجهزة والتجهيزات وتطبيق اشتراطات السلامة والصحة المهنية.
        </li>
    </ol>
    <h5>ثالثاً: إنهاء العقد</h5>
    <ol>
        <li>ينتهي العقد بانتهاء مدته أو الهدف منه .</li>
        <li>يجوز للطرف الأول إنهاء العقد دون إنذار ودون أي التزامات مالية عليه في حال عدم التزام الطرف الثاني بالبنود الواردة أعلاه او أحد منها.</li>
        <li>يجوز للطرف الأول إنهاء العقد دون إنذار ودون أي التزامات مالية عليه في حال تجاوز غياب الطرف الثاني بعذر أو بدون عذر ما نسبته ١٥٪ من مجموع ساعات التدريب الفعلية ، واتخاذ ما يراه حيال ذلك دون قيد أو شرط .</li>
    </ol>
    <h5>رابعاً: أحكام عامة</h5>
    <ol>
        <li>لايستحق الطرف الثاني أي مميزات غير واردة في هذا العقد.</li>
        <li>لمجلس إدارة مركز خدمة المجتمع والتدريب المستمر تفسير بنود العقد.</li>
        <li>في حال وجود خلاف بين الطرفين يكون الفصل فيه لمجلس إدارة مركز خدمة المجتمع والتدريب المستمر.</li>
        <li>يطبق على الطرف الثاني فيما يصدر عنه من مخالفات العقوبات الواردة في نظام تأديب الموظفين.</li>
        <li>مالم يُنص عليه في هذا العقد، فإنه يخضع لأنظمة ولوائح مركز خدمة المجتمع والتدريب المستمر.</li>
    </ol>
    <h5>خامساً: نسخ العقد</h5>
    <p>حرر هذا العقد من نسختين بتاريخ {{$contractData[0]->semester->contract_date}} هـ واستلم الطرف الثاني نسخة منها.</p>
    <div class="row">
        <div class="col-6">
            <p class="text-center">الطرف الأول</p>
            <p>المشرف على البرنامج المسائي  بالكلية التقنية ببريده</p>
            <p>التوقيع :</p>
        </div>

        <div class="col-6">
            <p class="text-center">الطرف الثاني</p>
            <p>المدرب: {{$contractData[0]->trainer->user->name ?? 'لا يوجد'}}</p>
            <p>التوقيع :</p>
        </div>
    </div>
</div>
</body>