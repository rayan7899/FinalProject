@extends('layouts.app')
@section('content')
    {{-- @dd(session()->get('duplicate')) --}}
    <div style="text-align: right !important" dir="rtl" lang="ar" class="container">
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        @if (session()->has('addedCount'))
            <div class="alert alert-info">
                تم اضافة {{ session()->get('addedCount') }} من {{ session()->get('countOfUsers') }} مستخدم
            </div>
        @endif

        @if ($errors->any())
            @php
                $errArr = array_unique($errors->all());
                // dd(count($errArr));
            @endphp
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errArr as $message)
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
        @if (session()->has('errorsArr'))
            <div class="alert alert-danger" role="alert">
                لم يتم اضافة المستخدمين في الجدول ادناه بسبب وجود اخطاء في بياناتهم
            </div>
            <table class="table table-sm table-hover bg-white">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">الاسم</th>
                        <th scope="col">الرقم الوظيفي</th>
                        <th scope="col">الحالة </th>
                    </tr>
                </thead>
                <tbody>

                    @forelse (session()->get('errorsArr') as $error)
                        <tr>
                            <td scope="row">{{ $loop->index + 1 }}</td>
                            <td>{{ $error['userinfo']['name'] ?? 'null' }}</td>
                            <td>{{ $error['userinfo']['national_id'] ?? 'null' }}</td>
                            <td class="text-danger">{{ $error['userinfo']['messages'] ?? 'null' }} </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        @endif
        @if (session()->has('duplicate'))
            <div class="alert alert-warning" role="alert">
                المستخدمين التالية بياناتهم تم اضافتهم مسبقاَ
            </div>
            <table class="table table-sm table-hover bg-white">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">الاسم</th>
                        <th scope="col">الرقم الوظيفي</th>
                        <th scope="col">الحاله </th>
                    </tr>
                </thead>
                <tbody>

                    @forelse (session()->get('duplicate') as $user)
                        <tr>
                            <td scope="row">{{ $loop->index + 1 }}</td>
                            <td>{{ $user['name'] ?? 'null' }}</td>
                            <td>{{ $user['national_id'] ?? 'null' }}</td>
                            <td class="text-danger">مكرر </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        @endif

        <div class="card">
            <div class="card-header">
                <h6>اضافة المستخدمين من ملف Excel</h6>
            </div>
            <div class="card-body">
                <form id="excel_form" class="form" method="POST" action="{{ route('trainerImportStore') }}"
                    enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group">
                        <label for="excel_file">أختر الملف</label>
                        <input required type="file" accept=".xls,.xlsx,.ods" class="form-control-file" id="excel_file"
                            name="excel_file">
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="type" id="type" value="trainer">
                            <label class="form-check-label px-4">
                            مدربين
                          </label>
                        </div>
                        <div class="form-check">
                            <input  type="radio" class="form-check-input" name="type" id="type" value="employee">
                            <label class="form-check-label px-4">
                            اداريين
                          </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" name="excel_submit" id="excel_submit" 
                            class="btn btn-sm btn-primary">أرسال</button>
                        @error('excel_file')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </form>
            </div>
            
        </div>
    @stop
