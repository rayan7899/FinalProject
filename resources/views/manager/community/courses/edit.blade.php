@extends('layouts.app')
@section('content')
    {{-- رمز المقرر	اسم المقرر	المستوى	الساعات المعتمدة	ساعات الإتصال --}}
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
            <div class="card-header h5">{{ __('اضافة مقرر') }}</div>
            <div class="card-body p-3 px-5">
                <form method="POST" action="{{ route('editCourse') }}">
                    @csrf

                    <input name="id" value="{{$course->id}}" required hidden>

                    <div class="form-group">
                        <label for="name">{{ __('اسم المقرر') }}</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name') ?? $course->name }}" required autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="code">{{ __('رمز المقرر') }}</label>
                        <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code"
                            value="{{ old('code') ?? $course->code }}" required autofocus>
                        @error('code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="level">{{ __('المستوى') }}</label>
                        <select class="form-control" name="level" class="ml-0 d-inline mx-3">
                            <option value="0" disabled selected>أختر</option>
                            <option value="1"> المستوى الاول</option>
                            <option value="2"> المستوى الثاني</option>
                            <option value="3"> المستوى الثالث</option>
                            <option value="4"> المستوى الرابع</option>
                            <option value="5"> المستوى الخامس</option>
                        </select>
                        @error('level')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="credit_hours">{{ __('الساعات المعتمدة') }}</label>
                        <input id="credit_hours" type="number" class="form-control @error('credit_hours') is-invalid @enderror" name="credit_hours"
                            value="{{ old('credit_hours') ?? $course->credit_hours}}" required autofocus>
                        @error('credit_hours')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="contact_hours">{{ __('ساعات الاتصال') }}</label>
                        <input id="contact_hours" type="number" class="form-control @error('contact_hours') is-invalid @enderror" name="contact_hours"
                            value="{{ old('contact_hours') ?? $course->contact_hours}}" required autofocus>
                        @error('contact_hours')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
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
