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
                    @php
                        switch ($user->student->traineeState) {
                            case 'privateState':
                                $discount = 0; // = %100 discount
                                break;
                            case 'employee':
                                $discount = 0.25; // = %75 discount
                                break;
                            case 'employeeSon':
                                $discount = 0.5; // = %50 discount
                                break;
                            default:
                                $discount = 1; // = %0 discount
                        }
                        
                        $creditHoursCost = $user->student->credit_hours*$user->student->program->hourPrice*$discount;
                    @endphp
                    <div class="form-group col-lg-6">
                        <label for="amount">المبلغ المتوقع استرداده</label>
                        <input required disabled="true" type="text" class="form-control" id="amount" name="amount"
                            value="حدد السبب اولا">
                        <span class="text-danger" id="discountMsg" style="display: none">*قد يتم خصم ٣٠٠ ريال مقابل المصاريف الادارية او ٤٠٪ من المبلغ</span>
                    </div>

                    <!-- reason -->
                    <div class="form-group col-lg-6">
                        <label for="reason">السبب</label>
                        <select required class="form-control" name="reason" id="reason" onchange="changeAmount()">
                            <option value="" disabled>حدد سبب الاسترداد</option>
                            <option value="drop-out" @if ($user->student->credit_hours == 0) disabled @endif>انسحاب</option>
                            @if($user->student->level == 1) 
                                <option value="not-opened-class" @if ($user->student->credit_hours == 0) disabled @endif>لم تتاح الشعبة</option> 
                            @endif
                            <option value="exception" @if ($user->student->credit_hours == 0) disabled @endif>استثناء</option>
                            <option value="graduate" @if ($user->student->level < 5) disabled @endif>خريج</option>
                            <option value="get-wallet-amount" @if ($user->student->wallet == 0) disabled @endif>استرداد مبلغ المحفظة</option>
                        </select>
                    </div>

                    {{-- refund to ? --}}
                    <div class="btn-group btn-group-toggle col-lg-6 mb-3" data-toggle="buttons" dir="ltr">
                        <label class="btn btn-outline-primary">
                          <input type="radio" value="bank" name="refund_to" id="radioBank" onclick="changeAmount()"> استرداد المبلغ الى البنك
                        </label>
                        <label class="btn btn-outline-primary">
                          <input required type="radio" value="wallet" name="refund_to" id="radioWallet" onclick="changeAmount()" @if ($user->student->credit_hours == 0) disabled @endif> استرداد المبلغ الى المحفظة
                        </label>
                    </div>

                    {{-- checkbox --}}
                    <div class="input-group mb-3 col-lg-6" dir="ltr">
                        <label class="form-control" aria-label="Text input with checkbox">
                            اتعهد بتقديم الطلب عبر موقع رايات
                        </label>
                        <div class="input-group-append">
                          <div class="input-group-text">
                            <input required type="checkbox" aria-label="Checkbox for following text input">
                          </div>
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
    <script>
        var wallet = {{ $user->student->wallet }};
        var creditHoursCost = {{ $creditHoursCost }};
    </script>
</div>
@endsection