@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="col-10 m-auto">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session()->has('error') || isset($error))
                <div class="alert alert-danger">
                    {{ session()->get('error') ?? $error }}
                </div>
            @endif
            @if (session()->has('success') || isset($success))
                <div class="alert alert-success">
                    {{ session()->get('success') ?? $success }}
                </div>
            @endif
            <div class="alert alert-warning">
                <strong>
                    تحذير
                </strong>
                لا يمكن التراجع عن التغييرات بعد اتمام هذه العملية.
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>بداية فصل تدريبي جديد</h5>
                </div>
                {{-- <div class="card-body">
                <p>
                    تقوم هذه العملية بنقل جميع المتدربين الى المستوى التالي
                    وتهيئة الساعات المعتمدة للفصل الجديد.
                </p>
                <button onclick="show()" type="button" name="" id="" class="btn btn-primary">موافق</button>
                <div class="d-none">
                    <div id="form">
                        <form class="pt-4" dir="rtl" method="POST" action="{{ route('newSemester') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="national_id"
                                    class="col-md-4 col-form-label text-md-right">{{ __('أسم المستخدم') }}</label>

                                <div class="col">
                                    <input id="national_id" minlength="10" maxlength="10" type="text"
                                        class="form-control @error('national_id') is-invalid @enderror" name="national_id"
                                        value="{{ old('national_id') }}" required autocomplete="number" autofocus>

                                    @error('national_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-right">{{ __('كلمة المرور') }}</label>

                                <div class="col">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">ارسال</button>
                        </form>
                    </div>

                </div>
            </div> --}}
                <div class="card-body">
                    <div class="alert alert-info p-1" role="alert">
                        <p class="p-0 m-0">تقوم هذه العملية بنقل جميع المتدربين الى المستوى التالي
                            وتهيئة الساعات المعتمدة للفصل الجديد.</p>
                    </div>
                    <form method="POST" action="{{ route('newSemester') }}" class="row">
                        @csrf

                        {{-- start date --}}
                        <div class="form-group col-md-6">
                            <label for="start_date">تاريخ بداية الفصل</label>
                            <input onchange="changeEndMinDate()" required type="date" name="start_date" id="start_date" class="form-control"
                                aria-describedby="start_date" placeholder="yyyy-mm-dd">
                        </div>

                        {{-- end date --}}
                        <div class="form-group col-md-6">
                            <label for="end_date">تاريخ نهاية الفصل</label>
                            <input required type="date" name="end_date" id="end_date" class="form-control"
                                aria-describedby="end_date"  placeholder="yyyy-mm-dd">
                        </div>

                        {{-- contract date --}}
                        <div class="form-group col-md-4">
                            <label for="end_date">تاريخ تحرير العقود</label>
                            <input required type="date" name="contract_date" id="contract_date" class="form-control"
                                aria-describedby="contract_date">
                        </div>

                        <!-- semester name -->
                        <div class="form-group col-md-4">
                            <label for="end_date">الفصل التدريبي</label>
                            <input required type="text" name="semester_name" id="semester_name" class="form-control"
                                aria-describedby="semester_name">
                        </div>

                        <!-- count of weeks -->
                        <div class="form-group col-md-4">
                            <label for="end_date">عدد الاسابيع</label>
                            <input required type="text" name="count_of_weeks" id="count_of_weeks" class="form-control"
                                aria-describedby="count_of_weeks">
                        </div>

                        {{-- <div class="input-group mt-4 mb-3 col-lg-6" dir="ltr">
                            <label class="form-control">
                                فصل صيفي |
                                <small> لن يتم نقل المتدربين الى المستوى التالي </small>
                            </label>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <input type="checkbox" name="isSummerSemester" id="isSummerSemester" value="1">
                                </div>
                            </div>
                        </div> --}}

                        <!-- which semester -->
                        <div class="form-group col-lg-12 m-0 p-0 row">
                            <label class="mr-3">حدد الفصل</label>
                            <div class="btn-group btn-group-toggle col-lg-12 my-auto" data-toggle="buttons" dir="ltr">
                                
                                <label class="btn btn-outline-primary">
                                <input required type="radio" value="summer" name="whichSemester" id="whichSemester" onclick="">
                                فصل صيفي |
                                <small> لن يتم نقل المتدربين الى المستوى التالي </small>
                                </label>
                                
                                <label class="btn btn-outline-primary">
                                <input required type="radio" value="2" name="whichSemester" id="whichSemester" onclick=""> الفصل الثاني
                                </label>
    
                                <label class="btn btn-outline-primary">
                                <input required type="radio" value="1" name="whichSemester" id="whichSemester" onclick=""> الفصل الاول
                                </label>
                            </div>
                        </div>

                        

                        <div class="col-lg-12 row m-0 p-0 mt-3">
                            {{-- username (national_id) --}}
                            <div class="form-group col-md-6">
                                <label for="national_id"> رقم الهوية</label>
                                <input id="national_id" minlength="10" maxlength="10" type="text"
                                    class="form-control @error('national_id') is-invalid @enderror" name="national_id"
                                    value="{{ old('national_id') ?? '' }}" required>
                                {{-- <small id="helpId" class="text-muted">مطلوب رقم الهوية وكلمة المرور لتأكيد الاجراء</small> --}}
                                @error('national_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
    
                            {{-- password --}}
    
                            <div class="form-group col-md-6">
                                <label for="password">كلمة المرور </label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mx-auto">
                                <button type="submit" class="btn btn-primary px-5">
                                    {{ __('ارسال') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            var today = new Date().toISOString().split('T')[0];
            document.getElementById("start_date").setAttribute('min', today);
            document.getElementById("end_date").setAttribute('min', today);


        });

        function changeEndMinDate() {
            let mindate = document.getElementById("start_date").value;
            document.getElementById("end_date").setAttribute('min', mindate);

        }
        // var form = document.getElementById("form").innerHTML;

        // function show() {
        //     Swal.fire({
        //         html: form,
        //         width: 600,
        //         showCloseButton: true,
        //         showConfirmButton: false,
        //         focusConfirm: false,
        //         // confirmButtonText: '<i class="fa fa-thumbs-up"></i> Great!',
        //         // confirmButtonAriaLabel: 'Thumbs up, great!',
        //         // cancelButtonText: '<i class="fa fa-thumbs-down"></i>',
        //         // cancelButtonAriaLabel: 'Thumbs down'
        //     })
        // }

    </script>
@stop
