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
        <div class="card w-75 m-auto">
            <div class="card-header h5">{{ __('انشاء مستخدم') }}</div>
            <div class="card-body p-3 px-5">
                <form method="POST" action="{{ route('createUserStore') }}">
                    @csrf
                    {{-- username (national_id) --}}
                    <div class="form-group">
                        <label for="national_id">اسم المستخدم ( رقم الهوية )</label>
                        <input id="national_id" minlength="10" maxlength="10" type="text"
                            class="form-control @error('national_id') is-invalid @enderror" name="national_id"
                            value="{{ old('national_id') ?? '' }}" required>
                        @error('national_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    {{-- name --}}

                    <div class="form-group">
                        <label for="name">{{ __('الاسم') }}</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') ?? '' }}" name="name" required>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    {{-- password --}}

                    <div class="form-group">
                        <label for="password">{{ __('كلمة المرور الجديدة') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="new-password">

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
