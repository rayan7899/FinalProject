@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="col-10 m-auto">
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
                    <h5>تعديل الفصل الحالي</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('editSemester') }}" class="row">
                        @csrf

                        {{-- start date --}}
                        <div class="form-group col-md-6">
                            <label for="start_date">تاريخ بداية الفصل</label>
                            <input required type="date" name="start_date" id="start_date" class="form-control"
                                aria-describedby="start_date" value="{{ $semester->start_date ?? 'لا يوجد'}}">
                        </div>

                        {{-- end date --}}
                        <div class="form-group col-md-6">
                            <label for="end_date">تاريخ نهاية الفصل</label>
                            <input required type="date" name="end_date" id="end_date" class="form-control"
                                aria-describedby="end_date"  value="{{ $semester->end_date ?? 'لا يوجد'}}">
                        </div>

                        {{-- contract date --}}
                        <div class="form-group col-md-4">
                            <label for="end_date">تاريخ تحرير العقود</label>
                            <input required type="date" name="contract_date" id="contract_date" class="form-control"
                                aria-describedby="contract_date"  value="{{ $semester->contract_date ?? null}}">
                        </div>

                        <!-- semester name -->
                        <div class="form-group col-md-4">
                            <label for="end_date">الفصل التدريبي</label>
                            <input required type="text" name="semester_name" id="semester_name" class="form-control"
                                aria-describedby="semester_name"  value="{{ $semester->name ?? 'لا يوجد'}}">
                        </div>

                        <!-- count of weeks -->
                        <div class="form-group col-md-4">
                            <label for="end_date">عدد الاسابيع</label>
                            <input required type="text" name="count_of_weeks" id="count_of_weeks" class="form-control"
                                aria-describedby="count_of_weeks"  value="{{ $semester->count_of_weeks ?? 'لا يوجد'}}">
                        </div>

                        {{-- <div class="input-group mt-4 mb-3 col-md-6" dir="ltr">
                            <label class="form-control">
                                فصل صيفي |
                                <small> لن يتم نقل المتدربين الى المستوى التالي </small>
                            </label>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <input type="checkbox" name="isSummerSemester" id="isSummerSemester" value="1">
                                </div>
                            </div>
                        </div>

                        <<!-- which semester -->>
                        <div class="btn-group btn-group-toggle col-lg-6 mb-3" data-toggle="buttons" dir="ltr">
                            <label class="btn btn-outline-primary">
                            <input type="radio" value="bank" name="refund_to" id="radioBank" onclick="changeAmount()"> الفصل الاول
                            </label>
                            <label class="btn btn-outline-primary">
                            <input required type="radio" value="wallet" name="refund_to" id="radioWallet" onclick="changeAmount()"> الفصل الثاني
                            </label>
                        </div> --}}
                        <div class="col-lg-12 row m-0 p-0">
                            {{-- username (national_id) --}}
                            <div class="form-group col-lg-6">
                                <label for="national_id"> رقم الهوية</label>
                                <input id="national_id" minlength="10" maxlength="10" type="text"
                                    class="form-control @error('national_id') is-invalid @enderror" name="national_id"
                                    value="{{ old('national_id') ?? '' }}" required>
                                {{-- <small id="helpId" class="text-muted">مطلوب رقم الهوية وكلمة المرور لتأكيد الاجراء</small> --}}
                                @error('national_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- password --}}

                            <div class="form-group col-lg-6">
                                <label for="password">كلمة المرور </label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mx-auto">
                                <button type="submit" class="btn btn-primary px-5">
                                    {{ __('ارسال') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
        });

    </script>
@stop
