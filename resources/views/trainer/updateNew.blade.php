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
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
        @if ($user->trainer->data_verify_note != null && !$errors->any() && !session()->has('error'))
            <div class="alert alert-warning">
                  يرجى اعادة تعبئة النموذج بسبب وجود الملاحظات التالية 
                ( 
                {{ $user->trainer->data_verify_note }}
                )
            </div>
        @endif
        {{-- <div class="alert alert-info">
            <b>ملاحظة:</b>
            يمكن ارسال هذا النموذج مرة واحدة و
            اذا كان هناك أي تحديث او تعديل يرجى التواصل مع مركز خدمة المجتمع والتدريب المستمر بالكلية
        </div> --}}
        <div class="card m-auto">
            <div class="card-header h5">{{ __('استكمال متطلبات التسجيل') }}</div>
            <div class="card-body p-3 px-5">
                <form id="updateUserForm" action="{{ route('updateNewTrainerStore') }}" method="post"
                    accept-charset="utf-8" enctype="multipart/form-data">
                    @csrf
                    <!-- bct ID -->
                    <div class="form-group">
                        <label for="bct_id">الرقم الوظيفي</label>
                        <input disabled type="text" class="form-control p-1 m-1 " id="bct_id" name="bct_id"
                            value="{{ $user->trainer->bct_id }}">
                    </div>

                    <!-- full name -->
                    <div class="form-group">
                        <label for="name">الاسم</label>
                        <input disabled type="text" class="form-control p-1 m-1  " id="name" name="name"
                            value=" {{ $user->name }}">
                    </div>

                    @if ($user->phone != null)
                        <!-- phone number show-->
                        <div class="form-group">
                            <label for="phone">رقم الجوال</label>
                            <input disabled="true" type="phone" class="form-control p-1 m-1" id="phone" name="phone"
                                value="{{ $user->phone }} ">
                        </div>
                    @endif
                    <!-- email  -->
                    <div class="form-group">
                        <label for="email">البريد الالكتروني</label>
                        <input required disabled="true" type="email" class="form-control p-1 m-1" id="email" name="email"
                            value="{{ $user->email }} ">
                    </div>

                    <!-- national ID -->
                    <div class="form-group">
                        <label for="national_id">رقم الهوية</label>
                        <input required minlength="10" maxlength="10" type="text" class="form-control p-1 m-1 "
                            id="national_id" name="national_id"
                            value="{{ strlen($user->national_id) == 10 ? $user->national_id : old('national_id') }}">
                            <small class="alert-info p-1 my-1 d-block rounded">

                                <b>ملاحظة:</b>
                                سوف يتم استخدام رقم الهوية لتسجيل الدخول بعد تحديث البيانات    
                            </small>
                        @error('national_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- employer -->
                    <div class="form-group">
                        <label for="employer">جهة العمل</label>
                        <input required type="text" class="form-control p-1 m-1  " id="employer" name="employer"
                            value=" {{ old('employer') ?? $user->trainer->employer }}">
                    </div>

                    @if ($user->phone == null)
                        <!-- phone number edit -->
                        <div class="form-group">
                            <label for="phone">رقم الجوال</label>
                            <input required minlength="10" maxlength="10" type="phone" class="form-control p-1 m-1"
                                id="phone" name="phone" value="{{ old('phone') }}">
                        </div>
                    @endif

                    {{-- password update --}}
                    @if (Hash::check('bct12345', $user->password))
                        <div class="form-group">
                            <label for="password">{{ __('كلمة المرور الجديدة') }}</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password-confirm">{{ __('تأكيد كلمة المرور') }}</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                                required autocomplete="new-password">
                        </div>
                    @endif

                    {{-- department --}}
                    <div class="form-group">
                        <label for="department"> القسم </label>
                        <select required name="department" id="department" class="form-control">
                            <option value="" disabled {{ old('department') == null ? 'selected' : '' }}>أختر</option>
                            @forelse (json_decode($departments) as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department') == $department->id || $user->trainer->department_id == $department->id ? 'selected' : '' }}>
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




                    {{-- <!-- national id image -->
                    <div class="form-group">
                        <label for="">صورة الهوية الوطنية </label>
                        <input type="file" accept=".pdf,.png,.jpg,.jpeg" name="identity" class="form-control" value="">
                    </div> --}}

                    {{-- qualification --}}
                    <div class="form-group">
                        <label for="qualification"> المؤهل </label>
                        <select required name="qualification" id="qualification" class="form-control">
                            <option value="" disabled {{ old('qualification') == null ? 'selected' : '' }}>أختر</option>
                            <option value="bachelor"
                                {{ old('qualification') == 'bachelor' || $user->trainer->qualification == 'bachelor' ? 'selected' : '' }}>
                                {{ __('bachelor') }}</option>
                            <option value="master"
                                {{ old('qualification') == 'master' || $user->trainer->qualification == 'master' ? 'selected' : '' }}>
                                {{ __('master') }}</option>
                            <option value="doctoral"
                                {{ old('qualification') == 'doctoral' || $user->trainer->qualification == 'doctoral' ? 'selected' : '' }}>
                                {{ __('doctoral') }}</option>
                            <option value="higher_diploma"
                                {{ old('qualification') == 'higher_diploma' || $user->trainer->qualification == 'higher_diploma' ? 'selected' : '' }}>
                                {{ __('higher_diploma') }}</option>

                        </select>
                        @error('qualification')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- certificate image -->
                    <div class="form-group">
                        <label for="">صورة من المؤهل </label>
                        <input required type="file" accept=".pdf,.png,.jpg,.jpeg" name="degree" class="form-control" value="">
                        <small class="alert-info p-1 my-1 d-block rounded">

                            <b>ملاحظة:</b>
                            إذا كان المؤهل من جامعة غير سعودية يرجى إرفاق ملف pdf واحد
                             يحتوي على المؤهل بالإضافة الى ما يثبت معادلة المؤهل من وزارة التعليم (التعليم العالي سابقاً)

                        </small>
                    </div>

                    <!-- submet button -->
                    <div class="form-group my-3">
                        <input type="submit" name="form_submit" id="form_submit" value="أرسال" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>

    </div>
@stop
