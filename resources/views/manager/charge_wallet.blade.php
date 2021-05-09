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
                أضافة رصيد
            </div>
            <div class="card-body">
                <div class="form-inline m-3">
                    <div class="form-group" id="searchContainer">
                        <input type="text" class="form-control" name="search" id="search"
                            placeholder="ادخل رقم الطالب المدني او الاكاديمي" value="">
                        <input type="button" onclick="getStudentInfo()" value="بحث" class="btn btn-primary">
                    </div>
                </div>
                <div class="container-fluid" id="userInfo" style="display: none">
                    <div class="form-group row">
                        <div dir="ltr" class="input-group col-md-4 mb-1">
                            <input readonly type="text" class="form-control text-right bg-white" placeholder=""
                                aria-label="Recipient's username" aria-describedby="basic-addon2" id="name">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 100px;">الاسم</span>
                            </div>
                        </div>
                        <div dir="ltr" class="input-group col-md-4 mb-1">
                            <input readonly type="text" class="form-control text-right bg-white" placeholder=""
                                aria-label="Recipient's username" aria-describedby="basic-addon2" id="national_id">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 100px;"><label
                                        class="text-center m-0 p-0 w-100">رقم الهوية</label></span>
                            </div>
                        </div>
                        <div dir="ltr" class="input-group col-md-4 mb-1">
                            <input readonly type="text" class="form-control text-right bg-white" placeholder=""
                                aria-label="Recipient's username" aria-describedby="basic-addon2" id="wallet">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 100px;">الرصيد</span>
                            </div>
                        </div>
                    </div>
                </div>
                <form style="display: none;" class="px-3" id="chargeForm" action="{{ route('charge') }}" method="post"
                    accept-charset="utf-8" enctype="multipart/form-data">
                    @csrf
                    <input hidden required type="number" class="form-control" id="id" name="id">

                    <div class="btn-group btn-group-toggle mb-3" data-toggle="buttons" dir="ltr">
                        <label class="btn btn-outline-primary">
                          <input type="radio" value="deduction" name="action" id="deduction" onclick="receiptToggle('hide')"> خصم من المحفظة
                        </label>
                        <label class="btn btn-outline-primary">
                          <input required type="radio" value="charge" name="action" id="charge" checked onclick="receiptToggle('show')"> اضافة الى المحفضة
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="amount">المبلغ</label>
                        <input required type="number" class="form-control" id="amount" name="amount">
                    </div>
                    <div class="form-group">
                        <label for="" class="col-form-label">ملاحظات </label>
                        <textarea class="form-control" id="note" name="note"></textarea>
                    </div>
                    <div class="form-group" id="receipt">
                        <label for="receiptImg"> صورة إيصال السداد</label>
                        <input required type="file" accept=".pdf,.png,.jpg,.jpeg" name="payment_receipt"
                            class="form-control" id="receiptImg">
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-primary w-25" type="submit">أرسال</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop
