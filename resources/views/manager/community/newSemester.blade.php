@extends('layouts.app')
@section('content')
    <div class="container">
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
                <h5>بداية فصل دراسي جديد</h5>
            </div>
            <div class="card-body">
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
                                    <input id="national_id" type="text"
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
            </div>
        </div>
    </div>
    <script>
        var form = document.getElementById("form").innerHTML;

        function show() {
            Swal.fire({
                html: form,
                width: 600,
                showCloseButton: true,
                showConfirmButton: false,
                focusConfirm: false,
                // confirmButtonText: '<i class="fa fa-thumbs-up"></i> Great!',
                // confirmButtonAriaLabel: 'Thumbs up, great!',
                // cancelButtonText: '<i class="fa fa-thumbs-down"></i>',
                // cancelButtonAriaLabel: 'Thumbs down'
            })
        }

    </script>
@stop
