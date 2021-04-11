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

        {{-- <div class="row  justify-content-center">
            <div class="row w-75">
                <div dir="ltr" class="input-group mb-1">
                    <input readonly type="text" class="form-control text-right bg-white h5"
                        value="{{ $user->name ?? 'لا يوجد' }}">
                    <div class="input-group-append">
                        <span class="input-group-text text-center" style="width: 120px;"><label
                                class="text-center m-0 p-0 w-100">الاسم</label></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row  justify-content-center">
            <div class="row w-75">
                <div dir="ltr" class="input-group mb-1">
                    <input readonly type="text" class="form-control text-right bg-white"
                        value="{{ $user->national_id ?? 'لا يوجد' }}">
                    <div class="input-group-append">
                        <span class="input-group-text text-center" style="width: 120px;"><label
                                class="text-center m-0 p-0 w-100">رقم الهوية</label></span>
                    </div>
                </div>
            </div>
        </div> --}}

            <div class="card my-5">
                <div class="card-header h5">{{ __('تعديل البيانات الشخصية') }}</div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('editUserUpdate', ['user' => $user->id]) }}">
                        @csrf
                        {{-- username (national_id) --}}
                        <div class="form-group">
                            <label for="national_id">اسم المستخدم ( رقم الهوية )</label>
                            <input id="national_id" type="text"
                                class="form-control @error('national_id') is-invalid @enderror" name="national_id"
                                value="{{$user->national_id ?? ''}}" required>
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
                                value="{{$user->name ?? ''}}" name="name" required>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- password --}}

                        {{-- <div class="form-group">
                            <label for="password">{{ __('كلمة المرور الجديدة') }}</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> --}}
                        {{-- password-confirm --}}

                        {{-- <div class="form-group">
                            <label for="password-confirm">{{ __('تأكيد كلمة المرور') }}</label>

                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                                required autocomplete="new-password">
                        </div> --}}
                        
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary px-5">
                                {{ __('ارسال') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            <div class="card my-5">
                <div class="card-header h5"> صلاحيات المستخدم</div>
                <div class="card-body p-0 px-5">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>الصلاحية</th>
                                <th class="text-center">حذف</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($user->manager->permissions as $permission)
                                <tr>
                                    <td>
                                        {{ $permission->role->name ?? '' }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('deleteUserPermission', ['permission' => $permission->id]) }}"
                                            onclick="return confirm(' سيتم ازالة الصلاحية ( {{ $permission->role->name }} )  هل انت متأكد ؟')">
                                            <i class="fa fa-trash fa-lg text-danger" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">لا يوجد صلاحيات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
 
            <div class="card my-5">
                <div class="card-header h5">{{ __('اضافة صلاحيات') }}</div>
                <div class="card-body p-0 px-5">
                    <form method="POST" action="{{ route('editUserPermissionsUpdate', ['user' => $user->id]) }}">
                        @csrf
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>الصلاحية</th>
                                    <th class="text-center">اضافة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                    <tr>
                                        <td>
                                            {{ $role->name ?? '' }}
                                        </td>
                                        <td class="text-center">
                                            <input style="width: 25px; height: 25px;" type="checkbox" name="roles[]"
                                                id="roles_{{ $role->id ?? '' }}" value="{{ $role->id ?? '' }}">
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                        <div class="form-group p-2 text-center">
                            <button type="submit" class="btn btn-primary px-5">
                                {{ __('ارسال') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

    </div>
@stop
