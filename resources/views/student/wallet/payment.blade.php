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
        <div class="card">
            <div class="card-header">
                أضافة رصيد
            </div>
            <div class="card-body">
                <form id="rechrgeForm" action="{{ route('paymentStore') }}" method="post" accept-charset="utf-8"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="amount">المبلغ</label>
                        <input required type="number" class="form-control" id="amount" name="amount">
                    </div>
                    <div class="form-group" id="receipt">
                        <label for="receiptImg"> صورة إيصال السداد</label>
                        <input required type="file" accept=".pdf,.png,.jpg,.jpeg" name="payment_receipt" class="form-control" id="receiptImg">
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-primary w-25" type="submit">أرسال</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
