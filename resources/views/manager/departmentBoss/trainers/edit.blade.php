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

            <div class="card-header h5">{{ __('تعديل بيانات مدرب') }}</div>
            <div class="card-body p-3 px-5">
                <div id="searchSection" class="row">
                    <div class="col-10 px-1">
                        <input type="text" class="form-control" name="searchId" id="searchId"
                            placeholder="ادخل رقم الهوية او الرقم التدريبي" value="">
                    </div>
                    <div class="col-2 px-0">
                        <input type="button" onclick="findTrainer()" value="بحث" class="btn btn-primary px-3">
                    </div>
                </div>
                <div id="trainerSection" style="display: none;">
                    {{-- <div class="alert alert-info">
                        <form id="resetPasswordForm" method="get">
                            <button onclick="sendResetPass(event)" type="button" class="btn btn-primary ml-4">اعادة تعيين
                                كلمة المرور</button>
                            <label for="">سيتم اعادة تعيين كلمة مرور المتدرب الى كلمة المرور الافتراضية (bct12345)</label>
                        </form>
                    </div> --}}
                    <form id="editTrainerForm" method="POST" action="">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
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
                            </div>

                            <div class="col-md-6">
                                {{-- (national_id) --}}
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
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                {{-- (bct_id) --}}
                                <div class="form-group">
                                    <label for="bct_id">الرقم الوظيفي </label>
                                    <input id="bct_id" type="text"
                                        class="form-control @error('bct_id') is-invalid @enderror" name="bct_id"
                                        value="{{ old('bct_id') ?? '' }}">
                                    @error('bct_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- employer -->
                                <div class="form-group">
                                    <label for="employer">جهة العمل</label>
                                    <input required type="text" class="form-control p-1 m-1  " id="employer" name="employer"
                                        value=" {{ old('employer') ?? '' }}">
                                    @error('employer')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">

                                {{-- qualification --}}
                                <div class="form-group">
                                    <label for="qualification"> المؤهل </label>
                                    <select required name="qualification" id="qualification" class="form-control">
                                        <option value="" disabled {{ old('qualification') == null ? 'selected' : '' }}>
                                            أختر
                                        </option>
                                        <option value="bachelor"
                                            {{ old('qualification') == 'bachelor' ? 'selected' : '' }}>
                                            {{ __('bachelor') }}</option>
                                        <option value="master" {{ old('qualification') == 'master' ? 'selected' : '' }}>
                                            {{ __('master') }}</option>
                                        <option value="doctoral"
                                            {{ old('qualification') == 'doctoral' ? 'selected' : '' }}>
                                            {{ __('doctoral') }}</option>
                                        <option value="higher_diploma"
                                            {{ old('qualification') == 'higher_diploma' ? 'selected' : '' }}>
                                            {{ __('higher_diploma') }}</option>

                                    </select>
                                    @error('qualification')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                {{-- department --}}
                                <div class="form-group">
                                    <label for="department"> القسم </label>
                                    <select required name="department" id="department" class="form-control">
                                        </option>
                                        @forelse ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ old('department') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}</option>
                                        @empty
                                            <option value="" disabled selected>لا يوجد اقسام</option>
                                        @endforelse
                                    </select>
                                    @error('department')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- phone number -->
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
                            </div>
                            <div class="col-md-6">
                                <!-- email  -->
                                <div class="form-group">
                                    <label for="email">البريد الالكتروني</label>
                                    <input required type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email') ?? '' }} ">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
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
        async function getTrainerInfo() {
            let id = document.getElementById("searchId").value;
            if (id == undefined || id == null || id == "") {
                console.error("searchId is undefined or null");
                return;
            }
            var data = null;
            Swal.fire({
                html: "<h4>جاري البحث </h4>",
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            await axios.get('/api/department-boss/trainers/get-trainer/' + id)
                .then((response) => {
                    data = response.data;
                })
                .catch(async (error) => {
                    await Swal.fire({
                        position: "center",
                        html: "<h4>" + error.response.data.message + "</h4>",
                        icon: "error",
                        showConfirmButton: true,
                    });
                });

            Swal.close();
            return data;
        }


        async function findTrainer() {

            var user = await getTrainerInfo();
            if (user == null) {
                console.error("user is null");
                return;
            }
            document.getElementById("editTrainerForm").action = "/department-boss/trainers/update/" + user.id;
            // document.getElementById("resetPasswordForm").action = "/department-boss/trainers/reset-password/" + user.id;
            document.getElementById("national_id").value = user.national_id;
            document.getElementById("email").value = user.email;
            document.getElementById("name").value = user.name;
            document.getElementById("phone").value = user.phone;
            document.getElementById("bct_id").value = user.trainer.bct_id;
            document.getElementById("employer").value = user.trainer.employer;
            document.getElementById("qualification").value = user.trainer.qualification;
            document.getElementById("department").value = parseInt(user.trainer.department_id);

            $("#trainerSection").show();
            $("#searchSection").hide();

            Swal.close();
        }
    </script>

@stop
