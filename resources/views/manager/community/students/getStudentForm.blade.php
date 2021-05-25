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
            <div class="card-header">
               جميع طلبات و بيانات المتدرب
            </div>
            <div class="card-body">
                <div class="form-inline m-3">
                    <div class="form-group" id="searchContainer">
                        <input type="text" class="form-control" name="search" id="search"
                            placeholder="ادخل رقم الهوية او الرقم التدريبي" value="">
                        <input type="button" onclick="getStudentReport()" value="بحث" class="btn btn-primary">
                    </div>
                    <form id="getReportForm" action="" method="get">
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
