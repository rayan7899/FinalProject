@extends('layouts.app')
@section('content')
    <div style="text-align: right !important" dir="rtl" lang="ar" class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session()->has('success') && !session()->has('error'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif

        <form class="border rounded p-3 bg-white" method="POST" action="/excel/import" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="excel_file">أختر الملف</label>
                <input required type="file" class="form-control-file" id="excel_file" name="excel_file">
            </div>
            <div class="form-group">
                <label for="department" class="pl-1"> القسم </label>
                <select required name="department" id="department" class="form-controller m-1  w-25"
                    onchange="departmentChanged()">
                    <option value="" disabled selected>أختر</option>
                    @forelse ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @empty
                    @endforelse

                </select>
                @error('department')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <label for="major" class="pl-1"> التخصص </label>
                <select required name="major" id="major" class="form-controller m-1 w-25">
                    <option value="" disabled selected>أختر</option>
                </select>
                @error('major')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input type="submit" name="excel_submit" id="excel_submit" value="أرسال" class="btn btn-sm btn-primary">
                @error('excel_file')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </form>
        <script>
            var departments = [@php echo $departments; @endphp]
            function departmentChanged() {
                var dept = document.getElementById('department').value;
                var mjr = document.getElementById('major');
                mjr.innerHTML = null;
                var mejors = departments[0][dept - 1].majors;
                for (var i = 0; i < mejors.length; i++) {
                    var option = document.createElement('option');
                    option.innerHTML = mejors[i].name;
                    option.value = mejors[i].id;
                    mjr.appendChild(option);
                }


            }

        </script>
    </div>
@stop
