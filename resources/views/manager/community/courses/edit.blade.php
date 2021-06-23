@extends('layouts.app')
@section('content')
    {{-- رمز المقرر	اسم المقرر	المستوى	الساعات المعتمدة	ساعات الإتصال --}}
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
            <div class="card-header h5">{{ __('اضافة مقرر') }}</div>
            <div class="card-body p-3 px-5">
                @php
                    if (Auth::user()->hasRole('خدمة المجتمع')) {
                        $route = route('editCourse');
                    } elseif (Auth::user()->isDepartmentManager()) {
                        $route = route('deptEditCourse');
                    }
                @endphp
                <form method="POST" action="{{ $route }}">
                    @csrf

                    <input name="id" value="{{ $course->id }}" required hidden>

                    {{-- program department major info --}}
                    <div class="row" id="progDeptMajorInfo" style="display: flex;">
                        {{-- program --}}
                        <div class="col-md-4" dir="ltr">
                            <div class="form-row">
                                <div class="col-10">
                                    <label for="program"> البرنامج </label>
                                    <input type="text" class="form-control"
                                        value="{{ $course->major->department->program->name ?? 'error' }}" disabled>
                                </div>
                                <div class="col-2">
                                    <br>
                                    <button onclick="showEdit()" type="button"
                                        class="btn btn-primary d-block mt-2">تعديل</button>
                                </div>
                            </div>
                        </div>
                        {{-- department --}}
                        <div class="col-md-4 ">
                            <div class="form-group">
                                <label for="department"> القسم </label>
                                <input type="text" class="form-control"
                                    value="{{ $course->major->department->name ?? 'error' }}" disabled>
                            </div>
                        </div>

                        {{-- major --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="major"> التخصص </label>
                                <input type="text" class="form-control" value="{{ $course->major->name ?? 'error' }}"
                                    disabled>
                            </div>
                        </div>

                    </div>

                    {{-- program department major editable --}}
                    <div class="row" id="progDeptMajorGroup" style="display: none;">
                        <div class="col-md-4" dir="ltr">
                            <div class="form-row">
                                <div class="col-10">
                                    <label for="program"> البرنامج </label>
                                    <select required name="program" id="program" class="form-control"
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
                                <div class="col-2">
                                    <br>
                                    <button onclick="undoEdit()" type="button"
                                        class="btn btn-primary d-block mt-2">تراجع</button>
                                </div>
                            </div>
                        </div>
                        {{-- department --}}
                        <div class="col-md-4 ">
                            <div class="form-group">
                                <label for="department"> القسم </label>
                                <select required name="department" id="department" class="form-control"
                                    onchange="fillMajors()">
                                    <option value="0" disabled selected>أختر</option>
                                </select>
                                @error('department')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- major --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="major"> التخصص </label>
                                <select required name="major" id="major" class="form-control">
                                    <option value="0" disabled selected>أختر</option>
                                </select>
                                @error('major')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- course name --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">{{ __('اسم المقرر') }}</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') ?? $course->name }}" required autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{-- course code --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="code">{{ __('رمز المقرر') }}</label>
                                <input id="code" type="text" class="form-control @error('code') is-invalid @enderror"
                                    name="code" value="{{ old('code') ?? $course->code }}" required autofocus>
                                @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            {{-- course level --}}
                            <div class="form-group">
                                <label for="level">{{ __('المستوى') }}</label>
                                <select name="level" class="form-control">
                                    <option value="0" disabled selected>أختر</option>
                                    <option value="1" @if ($course->level == 1) selected @endif> المستوى الاول</option>
                                    <option value="2" @if ($course->level == 2) selected @endif> المستوى الثاني</option>
                                    <option value="3" @if ($course->level == 3) selected @endif> المستوى الثالث</option>
                                    <option value="4" @if ($course->level == 4) selected @endif> المستوى الرابع</option>
                                    <option value="5" @if ($course->level == 5) selected @endif> المستوى الخامس</option>
                                </select>
                                @error('level')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- credit_hours --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="credit_hours">{{ __('الساعات المعتمدة') }}</label>
                                <input id="credit_hours" type="number"
                                    class="form-control @error('credit_hours') is-invalid @enderror" name="credit_hours"
                                    value="{{ old('credit_hours') ?? $course->credit_hours }}" required autofocus>
                                @error('credit_hours')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{-- contact_hours --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_hours">{{ __('ساعات الاتصال') }}</label>
                                <input id="contact_hours" type="number"
                                    class="form-control @error('contact_hours') is-invalid @enderror" name="contact_hours"
                                    value="{{ old('contact_hours') ?? $course->contact_hours }}" required autofocus>
                                @error('contact_hours')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- theoretical_hours --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="theoretical_hours">{{ __('عدد الساعات - نظري') }}</label>
                                <input id="theoretical_hours" type="number"
                                    class="form-control @error('theoretical_hours') is-invalid @enderror"
                                    name="theoretical_hours"
                                    value="{{ old('theoretical_hours') ?? $course->theoretical_hours }}" required
                                    autofocus>
                                @error('theoretical_hours')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{-- practical_hours --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="practical_hours">{{ __('عدد الساعات - عملي') }}</label>
                                <input id="practical_hours" type="number"
                                    class="form-control @error('practical_hours') is-invalid @enderror"
                                    name="practical_hours"
                                    value="{{ old('practical_hours') ?? $course->practical_hours }}" required autofocus>
                                @error('practical_hours')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- exam theoretical_hours --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exam_theoretical_hours">{{ __(' ساعات الاختبار - نظري') }}</label>
                                <input id="exam_theoretical_hours" type="number"
                                    class="form-control @error('exam_theoretical_hours') is-invalid @enderror"
                                    name="exam_theoretical_hours"
                                    value="{{ old('exam_theoretical_hours') ?? $course->exam_theoretical_hours }}"
                                    required autofocus>
                                @error('exam_theoretical_hours')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{-- exam practical_hours --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exam_practical_hours">{{ __('ساعات الاختبار - عملي') }}</label>
                                <input id="exam_practical_hours" type="number"
                                    class="form-control @error('exam_practical_hours') is-invalid @enderror"
                                    name="exam_practical_hours"
                                    value="{{ old('exam_practical_hours') ?? $course->exam_practical_hours }}" required
                                    autofocus>
                                @error('exam_practical_hours')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row m-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary px-5">
                                {{ __('ارسال') }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <script>
            var programs = @php echo $programs; @endphp;

            function showEdit() {
                $("#progDeptMajorInfo").hide();
                $("#progDeptMajorGroup").show();
            }

            function undoEdit() {
                $("#progDeptMajorInfo").show();
                $("#progDeptMajorGroup").hide();
                $("#program").val("0");
                $("#department").val("0");
                $("#major").val("0");

            }

        </script>

    </div>
@stop
