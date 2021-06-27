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
            <div class="alert alert-danger w-75">
                {{ session()->get('error') ?? $error }}
            </div>
        @endif
        @if (session()->has('success') || isset($success))
            <div class="alert alert-success">
                {{ session()->get('success') ?? $success }}
            </div>
        @endif
        <div class="card m-auto">
            <div class="card-header h5">{{ __('اضافة مدرب جديد') }}</div>
            <div class="card-body p-3 px-5">
                <form method="POST" action="{{ route('createTrainerStore') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
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
                        </div>
                        <div class="col-md-6">
                            {{-- (national_id) --}}
                            <div class="form-group">
                                <label for="national_id">رقم الهوية </label>
                                <input id="national_id" minlength="10" maxlength="10" type="text"
                                    class="form-control @error('national_id') is-invalid @enderror" name="national_id"
                                    value="{{ old('national_id') ?? '' }}" required>
                                @error('national_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {{-- (bct_id) --}}
                            <div class="form-group">
                                <label for="bct_id">الرقم الوظيفي </label>
                                <input id="bct_id" type="text" class="form-control @error('bct_id') is-invalid @enderror"
                                    name="bct_id" value="{{ old('bct_id') ?? '' }}">
                                @error('bct_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- employer -->
                            <div class="form-group">
                                <label for="employer">جهة العمل</label>
                                <input required type="text" class="form-control p-1 m-1  " id="employer" name="employer"
                                    value=" {{ old('employer') ?? '' }}">
                                @error('employer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {{-- qualification --}}
                            <div class="form-group">
                                <label for="qualification"> المؤهل </label>
                                <select required name="qualification" id="qualification" class="form-control">
                                    <option value="" disabled {{ old('qualification') == null ? 'selected' : '' }}>
                                        أختر
                                    </option>
                                    <option value="bachelor" {{ old('qualification') == 'bachelor' ? 'selected' : '' }}>
                                        {{ __('bachelor') }}</option>
                                    <option value="master" {{ old('qualification') == 'master' ? 'selected' : '' }}>
                                        {{ __('master') }}</option>
                                    <option value="doctoral" {{ old('qualification') == 'doctoral' ? 'selected' : '' }}>
                                        {{ __('doctoral') }}</option>
                                </select>
                                @error('qualification')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{-- department --}}
                            <div class="form-group">
                                <label for="department"> القسم </label>
                                <select required name="department" id="department" class="form-control">
                                    <option value="" disabled {{ old('department') == null ? 'selected' : '' }}>أختر
                                    </option>
                                    @forelse ($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ old('department') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}</option>
                                    @empty
                                        <option value="" disabled selected>لا يوجد اقسام</option>
                                    @endforelse
                                </select>
                                @error('department')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- phone number -->
                            <div class="form-group">
                                <label for="phone">رقم الجوال</label>
                                <input required type="phone" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone') ?? '' }}">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- email  -->
                            <div class="form-group">
                                <label for="email">البريد الالكتروني</label>
                                <input required type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email') ?? '' }} ">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>



                    <div class="alert alert-info p-1" role="alert">
                        <p class="p-0 m-0">يقوم المدرب باكمال البيانات المطلوبة بعد تسجيل الدخول باستخدام رقم الهوية وكلمة
                            المرور الافتراضية (bct12345)</p>
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
