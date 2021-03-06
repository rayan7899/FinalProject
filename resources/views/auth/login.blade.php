@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{-- @if ($errors->any())
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
                @endif --}}
                <div class="alert alert-info">
                    لمعرفة طريقة الاستخدام اضغط
                    <a target="_blank" href="{{ asset('help.pdf') }}"> هنا</a>
                     كما يمكنك الوصول الى التعليمات بعد تسجيل الدخول عبر الضغط على زر تعليمات الاستخدام في أعلى الصفحة 
                </div>
                <div class="card">
                    <div class="card-header">{{ __('تسجيل الدخول') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="national_id"
                                    class="col-md-4 col-form-label text-md-left">{{ __('أسم المستخدم') }}</label>

                                <div class="col-md-6">
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
                                    class="col-md-4 col-form-label text-md-left">{{ __('كلمة المرور') }}</label>

                                <div class="col-md-6">
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

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-6 ">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('تسجيل الدخول') }}
                                    </button>
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
