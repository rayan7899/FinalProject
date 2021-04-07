@extends('layouts.app')
@section('content')
    {{-- @dd($user->student->final_accepted); --}}
    {{-- {{ $user->student->documents_verified == true && $user->student->final_accepted == true ? 'border-success text-success' : '' }} --}}
    @php
    $step_1 = $user->student->documents_verified == true ? 'border-success text-success' : '';
    $line_1 = $user->student->documents_verified == true ? 'bg-success' : '';

    $step_2 = $user->student->student_docs_verified == true ? 'border-success text-success' : '';
    $line_2 = $user->student->student_docs_verified == true ? 'bg-success' : '';

    $step_3 = $user->student->final_accepted == true ? 'border-success text-success' : '';

    $total_cost = 0;
    $total_hours = 0;
    foreach ($user->student->courses as $course) {
        $total_hours += $course->credit_hours;
        $total_cost += $course->credit_hours * 550;
    }
    @endphp
    <div class="container">
        <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 75%" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pdfName"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id="pdfIfreme" src="" width="100%" height="600px"></iframe>
                        <div class="text-center">
                            <img id="modalImage" src="" alt="image" class="img-fluid" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="stepState">
            <div class="flag {{ $step_1 }}">الايصال</div>
            <!-- <div class="line {{ $line_1 }}"></div> -->
            <div class="flag {{ $step_2 }}">الوثائق</div>
            <!-- <div class="line {{ $line_2 }}"></div> -->
            <div class="flag {{ $step_3 }}">مقبول</div>
        </div> --}}
        <div class="row justify-content-center">
            <div class="col-10">
                @if (session()->has('error') || isset($error))
                <div class="alert alert-danger">
                    {{ session()->get('error') ?? $error}}
                </div>
            @endif
            @if (session()->has('success') || isset($success))
                <div class="alert alert-success">
                    {{ session()->get('success') ?? $success}}
                </div>
            @endif
                <div class="card my-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            البيانات الشخصية
                        </h5>
                    </div>
                    <div class="row">
                        
                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white h5"
                                    value="{{ $user->name ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">الاسم</label></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $user->national_id ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">رقم الهوية</label></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $user->phone ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">رقم الجوال</label></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $user->email ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">البريد الألكتروني</label></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $user->student->wallet ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">رصيد المحفظة</label></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $user->student->program->name ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">البرنامج</label></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $user->student->department->name ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">القسم</label></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $user->student->major->name ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">التخصص</label></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $user->student->level ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">المستوى</label></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $total_hours ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">الساعات المعتمدة</label></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- courses -->
            <div class="col-10">
                <div class="card">
                    <div class="card-header">
                        <div class="h5">
                            المحفظة
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row m-2">
                            <a href="{{ route('walletMain') }}" class=" btn btn-primary rounded">
                               اضافة مقررات  / شحن المحفظة
                            </a>
                            {{-- <a href="{{ route('orderForm') }}" class=" btn btn-primary m-3 rounded"
                                style="padding: 20px; font-size: 14px; font-weight:bold;">
                                اضافة مقررات
                            </a> --}}
                        </div>
                        <div class="row m-2">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>المبلغ</th>
                                        <th>حالة الطلب</th>
                                        <th>صورة لايصال</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($user->student->payments as $payment)
                                        @php
                                            $user->student->receipt = Storage::disk('studentDocuments')->files($user->national_id . '/receipts/' . $payment->receipt_file_id)[0];
                                        @endphp
                                        <tr>
                                            <td>{{ $payment->amount }}</td>
                                            <td>{{ $payment->transaction_id !== null ? 'مقبول' : 'قيد المراجعة' }}</td>
                                            <td class="">

                                                @php
                                                    $splitByDot = explode('.', $user->student->receipt);
                                                    $fileExtantion = end($splitByDot);
                                                @endphp
                                                @if ($fileExtantion == 'pdf' || $fileExtantion == 'PDF')
                                                    <a data-toggle="modal" data-target="#pdfModal" href="#"
                                                        onclick="showPdf('{{ route('GetStudentDocument', ['path' => $user->student->receipt]) }}','pdf')">
                                                        <img style="width: 20px" src="{{ asset('/images/pdf.png') }}" />
                                                    </a>
                                                @else
                                                    <a data-toggle="modal" data-target="#pdfModal" href="#"
                                                        onclick="showPdf('{{ route('GetStudentDocument', ['path' => $user->student->receipt]) }}','img')">
                                                        <img src=" {{ asset('/images/camera_img_icon.png') }}"
                                                            style="width:25px;" alt="Image File">
                                                    </a>
                                                @endif

                                            </td>
                                        </tr>
                                    @empty

                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .stepState {
            width: 100%;
            display: flex;
            justify-content: center;
            align-content: center;
            align-items: center;
        }

        .line {
            height: 3px;
            width: 5%;
            background: #b4b4b4;
        }

        .flag {
            display: flex;
            width: 70px;
            height: 70px;
            justify-content: center;
            align-content: center;
            align-items: center;
            border-radius: 50%;
            border: 3px solid #b4b4b4;
            background-color: #f3f3f3;
            font-weight: bold;
            margin-left: 2%;
            margin-right: 2%;
        }

    </style>
@endsection
