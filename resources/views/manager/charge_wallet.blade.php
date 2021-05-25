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


        <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 75%" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pdfName"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <button onclick="window.rotateImg()" type="button" class="btn btn-primary">
                            تدوير الصورة

                            <i class="fa fa-rotate-right"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id="pdfIfreme" src="" width="100%" height="600px"></iframe>
                        <div class="text-center" id="modalImageDev">
                            <img id="modalImage" src="" alt="image" class="img-fluid" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                أضافة رصيد
            </div>
            <div class="card-body">
                <div class="form-inline m-3">
                    <div class="form-group" id="searchContainer">
                        <input type="text" class="form-control" name="search" id="search"
                            placeholder="ادخل رقم الهوية او الرقم التدريبي" value="">
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
                            <input type="radio" value="deduction" name="action" id="deduction"
                                onclick="receiptToggle('hide')"> خصم من المحفظة
                        </label>
                        <label class="btn btn-outline-primary">
                            <input required type="radio" value="charge" name="action" id="charge" checked
                                onclick="receiptToggle('show')"> اضافة الى المحفضة
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
        {{-- payments --}}
        <div class="card mt-5"  id="paymentsReportCard" style="display: none">
            <div class="card-header bg-white">
                <div class="d-flex flex-row justify-content-between">
                    <div class="h5">
                        الطلبات السابقة
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table nowrap display cell-border" id="singlePaymentsReportTbl">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">رقم الطلب</th>
                            <th class="text-center">المبلغ</th>
                            <th class="text-center">حالة السداد</th>
                            <th class="text-center">التاريخ</th>
                            <th class="text-center">الملاحظات</th>
                            <th class="text-center">ايصال السداد</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@stop
