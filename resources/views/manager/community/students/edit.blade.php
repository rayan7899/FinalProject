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

        <div class="card m-auto">

            <div class="card-header h5">{{ __('تعديل بيانات متدرب') }}</div>
            <div class="card-body p-3 px-5">
                <div id="searchSection" class="row">
                    <div class="col-10 px-1">
                        <input type="text" class="form-control" name="searchId" id="searchId"
                            placeholder="ادخل رقم الهوية او الرقم التدريبي" value="">
                    </div>
                    <div class="col-2 px-0">
                        <input type="button" onclick="findStudent()" value="بحث" class="btn btn-primary px-3">
                    </div>
                </div>
                <div id="studentSection" style="display: none;">
                    <div class="alert alert-info">
                        <form id="resetPasswordForm" method="get">
                            <button onclick="sendResetPass(event)" type="button" class="btn btn-primary ml-4">اعادة تعيين كلمة المرور</button>
                            <label for="">سيتم اعادة تعيين كلمة مرور المتدرب الى كلمة المرور الافتراضية (bct12345)</label>
                        </form>
                    </div>
                    <form id="editStudentForm" method="POST">
                        @csrf

                        {{-- username (national_id) --}}
                        <div class="form-group">
                            <label for="national_id">رقم الهوية </label>
                            <input id="national_id" minlength="10" maxlength="10" type="text"
                                class="form-control @error('national_id') is-invalid @enderror" name="national_id"
                                value="{{ old('national_id') ?? '' }}" required>
                            @error('national_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- username (rayat_id) --}}
                        <div class="form-group">
                            <label for="rayat_id">الرقم التدريبي </label>
                            <input id="rayat_id" type="text" class="form-control @error('rayat_id') is-invalid @enderror"
                                name="rayat_id" value="{{ old('rayat_id') ?? '' }}" required>
                            @error('rayat_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- name --}}

                        <div class="form-group">
                            <label for="name">{{ __('الاسم') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') ?? '' }}" name="name" required>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{--  phone number --}}
                        <div class="form-group">
                            <label for="phone">رقم الجوال</label>
                            <input required type="phone" class="form-control @error('phone') is-invalid @enderror"
                                id="phone" name="phone" value="{{ old('phone') ?? '' }}">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                       
                        {{-- Trainee State --}}
                        <div class="form-group">
                            <label for="traineeState">{{ __('الحالة') }}</label>
                            <select id="traineeState" class="form-control" name="traineeState" class="ml-0 d-inline mx-3">
                                <option value="0" disabled selected>أختر</option>
                                <option value="trainee">{{__('trainee')}} </option>
                                <option value="employee"> {{__('employee')}}</option>
                                <option value="employeeSon">{{__('employeeSon')}}</option>
                                <option value="privateState"> {{__('privateState')}}</option>
                            </select>
                            <small class="alert-warning p-1 my-1 d-block">
                               
                                    <b>تنبية</b>
                                     عند تغيير حالة المتدرب الى (ظروف خاصة) ستكون جميع الطلبات للفصل الحالي المرفوعة الى رايات والتي قيد المراجعة مقبولة من الارشاد تلقائياً
                              
                            </small>
                            @error('traineeState')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- program , department , major --}}

                        <div class="form-row my-3">
                            <div class="col-sm-4">
                                <label for="program" class="pl-1"> البرنامج </label>
                                <select required name="program" id="program" class="form-control w-100"
                                    onchange="fillDepartments()">
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
                                <select required name="department" id="department" class="form-control w-100 "
                                    onchange="fillMajors()">
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
                                <select required name="major" id="major" class="form-control w-100">
                                    <option value="0" disabled selected>أختر</option>
                                </select>
                                @error('major')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="level">{{ __('المستوى') }}</label>
                            <select id="level" class="form-control" name="level" class="ml-0 d-inline mx-3">
                                <option value="0" disabled selected>أختر</option>
                                <option value="1"> المستوى الاول</option>
                                <option value="2"> المستوى الثاني</option>
                                <option value="3"> المستوى الثالث</option>
                                <option value="4"> المستوى الرابع</option>
                                <option value="5"> المستوى الخامس</option>
                            </select>
                            @error('level')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary px-5">
                                {{ __('ارسال') }}
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script>
        var programs = @php echo $programs; @endphp;

        function sendResetPass(event) {
            event.preventDefault();
            Swal.fire({
                title: ' هل انت متأكد ؟',
                text: "  لا يمكن التراجع عن هذا الاجراء",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم',
                cancelButtonText: 'الغاء',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("resetPasswordForm").submit();
                }
            })
        }

    </script>
@stop
