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
                {{ session()->get('error') ?? $error}}
            </div>
        @endif
        @if (session()->has('success') || isset($success))
            <div class="alert alert-success">
                {{ session()->get('success') ?? $success}}
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <div class="h5">
                    المحفظة
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <a href="{{ route('paymentForm') }}" class=" btn m-3 bg-primary text-white rounded"
                        style="padding: 50px; font-size: 16px; font-weight:bold;">
                        شحن المحفظة
                    </a>
                    <a href="{{ route('orderForm') }}" class=" btn m-3 bg-primary text-white rounded"
                        style="padding: 50px; font-size: 16px; font-weight:bold;">
                        اضافة مقررات
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop
