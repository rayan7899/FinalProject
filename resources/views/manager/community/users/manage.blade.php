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
        <div class="card">
            <div class="card-header h5">{{ __('ادارة المستخدمين') }}</div>
            <div class="card-body p-0 px-5">
                <table class="table">
                    <thead>
                        <tr>
                            <th>اسم المستخدم</th>
                            <th>الاسم</th>
                            <th>الصلاحيات</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($users))
                        @forelse ($users as $user)
                            <tr>
                                <td scope="row">{{ $user->national_id ?? '' }}</td>
                                <td>{{ $user->name }}</td>
                                <td>
                                    @foreach ($user->manager->permissions as $permission)
                                        <span class="badge badge-secondary">
                                            {{ $permission->role->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td style="max-width: 100px;">
                                    <a class="px-2" href="{{ route('editUserForm', ['user' => $user->id]) }}">
                                        <i class="fa fa-edit fa-lg text-primary" aria-hidden="true"></i>
                                    </a>
                                    <a href="{{ route('deleteUser', ['user' => $user->id]) }}" onclick="return confirm('سيتم حذف المستخدم ( {{$user->name}} )  هل انت متأكد ؟')">
                                        <i class="fa fa-trash fa-lg text-danger" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                        @endif
                        
                    </tbody>
                </table>
                <div class="p-2">
                    <a class="btn btn-primary" href="{{ route('createUserForm')}}">{{ __('اضافة مستخدم') }}
                       
                    </a>
                  
                </div>

            </div>
           
        </div>
    </div>
@stop
