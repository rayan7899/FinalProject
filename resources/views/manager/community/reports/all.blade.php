@extends('layouts.app')
@section('content')
    {{-- رمز المقرر	اسم المقرر	المستوى	الساعات المعتمدة	ساعات الإتصال --}}
    <div class="container w-75">
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
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header h5">{{ __('بكالوريوس') }}</div>
                    <div class="card-body">

                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $baccSumHours ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;"><label
                                        class="text-center m-0 p-0 w-100">عدد الساعات الكلي</label></span>
                            </div>
                        </div>

                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $baccCount ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;"><label
                                        class="text-center m-0 p-0 w-100">عدد المتدربين</label></span>
                            </div>
                        </div>

                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $baccSumDeductions ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;"><label
                                        class="text-center m-0 p-0 w-100">مجموع تكلفة الساعات</label></span>
                            </div>
                        </div>

                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $baccSumDiscount ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;"><label
                                        class="text-center m-0 p-0 w-100">مجموع التخفيض</label></span>
                            </div>
                        </div>

                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $baccCommunityAmount ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;">
                                    <label class="text-center m-0 p-0 w-100">
                                        <a role="button" class="mx-1" data-toggle="popover" title="مخصص المركز الرئيسي"
                                            data-content="١٥٪ من مجموع تكلفة الساعات">
                                            <i class="fa fa-info-circle d-inline"></i>
                                        </a>
                                        مخصص المركز الرئيسي
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $baccGeneralManageAmount ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;">
                                    <label class="text-center m-0 p-0 w-100">
                                        <a role="button" class="mx-1" data-toggle="popover" title="مخصص الادارة العامة"
                                            data-content="٥٪ من مجموع تكلفة الساعات">
                                            <i class="fa fa-info-circle d-inline"></i>
                                        </a>
                                        مخصص الادارة العامة
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $baccUnitAmount ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;">
                                    <label class="text-center m-0 p-0 w-100">
                                        <a role="button" class="mx-1" data-toggle="popover" title="مخصص الوحدة المنفذة"
                                            data-content="٨٠٪ من مجموع تكلفة الساعات">
                                            <i class="fa fa-info-circle d-inline"></i>
                                        </a> مخصص الوحدة المنفذة
                                    </label>
                                </span>
                            </div>
                        </div>
                        {{-- <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{$baccSumWallets ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;"><label
                                        class="text-center m-0 p-0 w-100">مجموع الارصدة</label></span>
                            </div>
                        </div> --}}


                        {{-- <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{$baccSum ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;"><label
                                        class="text-center m-0 p-0 w-100">المجموع</label></span>
                            </div>
                        </div> --}}

                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header h5">{{ __('دبلوم') }}</div>
                    <div class="card-body">

                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $diplomSumHours ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;"><label
                                        class="text-center m-0 p-0 w-100">عدد الساعات الكلي</label></span>
                            </div>
                        </div>

                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $diplomCount ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;"><label
                                        class="text-center m-0 p-0 w-100">عدد المتدربين</label></span>
                            </div>
                        </div>

                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $diplomSumDeductions ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;"><label
                                        class="text-center m-0 p-0 w-100">مجموع تكلفة الساعات</label></span>
                            </div>
                        </div>

                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $diplomSumDiscount ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;"><label
                                        class="text-center m-0 p-0 w-100">مجموع التخفيض</label></span>
                            </div>
                        </div>

                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $diplomCommunityAmount ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;">
                                    <label class="text-center m-0 p-0 w-100">
                                        <a role="button" class="mx-1" data-toggle="popover" title="مخصص المركز الرئيسي"
                                            data-content="١٥٪ من مجموع تكلفة الساعات">
                                            <i class="fa fa-info-circle d-inline"></i>
                                        </a>
                                        مخصص المركز الرئيسي
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $diplomGeneralManageAmount ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;">
                                    <label class="text-center m-0 p-0 w-100">
                                        <a role="button" class="mx-1" data-toggle="popover" title="مخصص الادارة العامة"
                                            data-content="٥٪ من مجموع تكلفة الساعات">
                                            <i class="fa fa-info-circle d-inline"></i>
                                        </a>
                                        مخصص الادارة العامة
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $diplomUnitAmount ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;">
                                    <label class="text-center m-0 p-0 w-100">
                                        <a role="button" class="mx-1" data-toggle="popover" title="مخصص الوحدة المنفذة"
                                            data-content="٨٠٪ من مجموع تكلفة الساعات">
                                            <i class="fa fa-info-circle d-inline"></i>
                                        </a> مخصص الوحدة المنفذة
                                    </label>
                                </span>
                            </div>
                        </div>

                        {{-- <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white h5 num"
                                    value="{{$diplomSumWallets ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 180px;"><label
                                            class="text-center m-0 p-0 w-100">مجموع الارصدة</label></span>
                                </div>
                            </div> --}}

                        {{-- <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{$diplomSum ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;"><label
                                        class="text-center m-0 p-0 w-100">المجموع</label></span>
                            </div>
                        </div> --}}

                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        var programs = @php echo $programs; @endphp;

        function numFormat(num) {
            // return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            num = parseFloat(num);
            return num.toLocaleString("en-US");
        }

        function formatAll() {
            let allInputs = document.getElementsByClassName('num');
           for (let i=0; i<allInputs.length; i++){
               allInputs[i].value = numFormat(allInputs[i].value);
           }
        }
        // window.addEventListener('onload', (event) => {
        //     formatAll();
        // });

        window.onload = formatAll();

    </script>
@stop
