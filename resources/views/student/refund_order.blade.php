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
    <div class="card m-auto">
        <div class="card-header h5">{{ __('طلب استرداد مبلغ') }}</div>
        <div class="card-body p-3 px-5">
            <form id="refundOrderForm" action="{{ route('refundOrder') }}" method="post" accept-charset="utf-8"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- amount -->
                    <div class="form-group col-lg-6">
                        <label for="amount">المبلغ</label>
                        <input required disabled="true" type="text" class="form-control" id="amount" name="amount"
                            value="{{ $user->student->wallet }} ">
                        <span class="text-danger">*سيتم خصم ٣٠٠ ريال من المبغ مقابل الأتعاب الادارية</span>
                    </div>

                    <!-- reason -->
                    <div class="form-group col-lg-6">
                        <div class="form-group">
                            <label for="reason">السبب</label>
                            <select class="form-control" name="reason" id="reason">
                                <option disabled selected>حدد السبب</option>
                                <option value="drop-out">انسحاب</option>
                                <option value="exception">استثناء</option>
                                <option value="graduate">خريج</option>
                            </select>
                        </div>
                    </div>

                    <!-- IBAN -->
                    <div class="form-group col-lg-6" dir="ltr">
                        <label for="IBAN">الآيبان</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">SA</span>
                            </div>
                            <input required type="text" class="form-control" id="IBAN" name="IBAN">
                        </div>
                    </div>

                    <!-- bank -->
                    <div class="form-group col-lg-6">
                        <div class="form-group">
                            <label for="bank">البنك</label>
                            <input required type="text" class="form-control" id="bank" name="bank">
                        </div>
                    </div>

                    <!-- note -->
                    <div class="form-group col-lg">
                        <div class="form-group">
                            <label for="note">ملاحظة</label>
                            <textarea type="text" class="form-control" id="note" name="note"></textarea>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">ارسال</button>
            </form>
        </div>
    </div>
</div>
@endsection