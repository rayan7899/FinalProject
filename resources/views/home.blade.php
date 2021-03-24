@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{-- <div class="card">
                       <div class="card-header">{{ __('Dashboard') }}</div>

                       <div class="card-body">
                       @if (session('status'))
                       <div class="alert alert-success" role="alert">
                       {{ session('status') }}
                       </div>
                       @endif

                       {{ __('You are logged in!') }}
                       </div>
                       </div> --}}
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif
                @if(isset($error))
                    <div class="alert alert-danger">
                        {{ $error }}
                    </div>
                @endif
                <div class="form-group">
                    <div dir="ltr"  class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white" placeholder="" aria-label="Recipient's username" aria-describedby="basic-addon2" value="{{$user->national_id ?? "حدث خطأ"}}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 100px;">رقم الهوية</span>
                        </div>
                    </div>
                    <div dir="ltr"  class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white" placeholder="" aria-label="Recipient's username" aria-describedby="basic-addon2" value="{{$user->name ?? "حدث خطأ"}}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 100px;" >الاسم</span>
                        </div>
                    </div>
                    <div dir="ltr"  class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white" placeholder="" aria-label="Recipient's username" aria-describedby="basic-addon2" 
                               value="{{$user->documents_verified == true ? 'تم مراجعة الطلب' : 'قيد المراجعة'}}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 100px;" >حالة الطلب</span>
                        </div>
                    </div>

                </div>


                <!-- suggested courses -->
                <div class="from-group">
                    <label>المواد المسجلة</label>
                    <table class="table table-hover table-bordered bg-white">
                        <thead>
                            <tr>
                                <th class="text-center">رمز المقرر</th>
                                <th class="text-center">اسم المقرر</th>
                                <th class="text-center">المستوى</th>
                                <th class="text-center">الساعات</th>
                            </tr>
                        </thead>
                        <tbody id="courses">
                            @php
                            $default_cost = 0;
                            @endphp
                            @foreach ($user->student->courses as $course)
                                @php
                                $default_cost += $course->credit_hours * 550;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $course->code }}</td>
                                    <td class="text-center">{{ $course->name }}</td>
                                    <td class="text-center">{{ $course->level }}</td>
                                    <td class="text-center">{{ $course->credit_hours }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
