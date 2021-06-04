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

        <form method="POST" action="{{ route('reportFilterd') }}" class="border rounded p-4 mb-4 bg-white">
            @csrf

            <div class="form-row mb-3">
                <div class="col-sm-4">
                    <label for="program" class="pl-1"> البرنامج </label>
                    <select required name="prog_id" id="program" class="form-control w-100" onchange="fillDepartments()">
                        <option value="0" disabled selected>أختر</option>
                        @forelse (json_decode($programs) as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @empty
                        @endforelse

                    </select>
                    @error('program')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-sm-4 ">
                    <label for="department" class="pl-1"> القسم </label>
                    <select required name="dept_id" id="department" class="form-control w-100 " onchange="fillMajors()">
                        <option value="0" disabled selected>أختر</option>
                    </select>
                    @error('department')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-sm-4">
                    <label for="major" class="pl-1"> التخصص </label>
                    <select required name="major_id" id="major" class="form-control">
                        <option value="0" disabled selected>أختر</option>
                    </select>
                    @error('major')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>
                <button type="submit" class="btn btn-primary mt-4 mr-2 px-4">ارسال</button>
            </div>
        </form>
        @if(isset($programObj))
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header h5">{{ $programObj->name ?? 'Error' }} - {{ $department->name ?? 'Error' }} -
                        {{ $major->name ?? 'Error' }} </div>
                    <div class="card-body">
                        
                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $sumHours ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;"><label
                                        class="text-center m-0 p-0 w-100">عدد الساعات الكلي</label></span>
                            </div>
                        </div>

                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $count ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;"><label
                                        class="text-center m-0 p-0 w-100">عدد المتدربين</label></span>
                            </div>
                        </div>

                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $sumDeductions ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;"><label
                                        class="text-center m-0 p-0 w-100">مجموع تكلفة الساعات</label></span>
                            </div>
                        </div>
                        
                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $sumDiscount ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 180px;"><label
                                        class="text-center m-0 p-0 w-100">مجموع التخفيض</label></span>
                            </div>
                        </div>

                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white h5 num"
                                value="{{ $communityAmount ?? 'لا يوجد' }}">
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
                                value="{{ $generalManageAmount ?? 'لا يوجد' }}">
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
                                value="{{ $unitAmount ?? 'لا يوجد' }}">
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


                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <script>
        var programs = @php echo $programs; @endphp;

        function numFormat(num) {
            // return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            num = parseFloat(num);
            return num.toLocaleString();
        }

        function formatAll() {
            let allInputs = document.getElementsByClassName('num');
           for (let i=0; i<allInputs.length; i++){
               allInputs[i].value = numFormat(allInputs[i].value);
           }
        }

        window.onload = formatAll();

    </script>
@stop
