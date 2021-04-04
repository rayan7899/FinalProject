@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header h5">{{ __('انشاء مستخدم') }}</div>
            <div class="card-body p-5">
                <form method="POST" action="{{ route('UpdatePassword') }}">
                    @csrf
                    {{-- username --}}
                    <div class="form-group">
                        <label for="username"
                            >{{ __('رقم الهوية') }}</label>
                            <input id="username" type="text"
                                class="form-control @error('username') is-invalid @enderror" name="username" required>
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>

                    {{-- name --}}

                    <div class="form-group">
                        <label for="name"
                            >{{ __('الاسم') }}</label>
                            <input id="name" type="text"
                                class="form-control @error('name') is-invalid @enderror" name="name" required>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>

                    {{-- password --}}

                    <div class="form-group">
                        <label for="password"
                            >{{ __('كلمة المرور الجديدة') }}</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                    {{-- password-confirm --}}

                    <div class="form-group">
                        <label for="password-confirm">{{ __('تأكيد كلمة المرور') }}</label>
                       
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                                required autocomplete="new-password">
                       
                    </div>
                    <div class="form-group">
                            <button type="submit" class="btn btn-primary px-5">
                                {{ __('ارسال') }}
                            </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
