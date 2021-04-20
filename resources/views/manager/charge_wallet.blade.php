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
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                أضافة رصيد
            </div>
            <div class="card-body">
                <form id="rechrgeForm" action="{{ route('charge') }}" method="post" accept-charset="utf-8"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="id">رقم الطالب</label>
                        <input required type="text" class="form-control" name="id" id="id"
                            placeholder="ادخل رقم الطالب المدني او الاكاديمي">
                    </div>
                    <div class="form-group">
                        <label for="amount">المبلغ</label>
                        <input required type="number" class="form-control" id="amount" name="amount">
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-primary w-25" type="submit">أرسال</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop