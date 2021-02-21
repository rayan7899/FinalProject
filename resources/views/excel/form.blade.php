@extends('layouts.app')
@section('content')
{{-- @dd(session()->get('duplicate')) --}}
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
            <div class="alert alert-warning">
                {{ session()->get('error') }}
            </div>
        @endif
        @if (session()->has('duplicate'))
        <table class="table table-sm table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">الاسم</th>
                    <th scope="col">رقم الهوية</th>
                </tr>
            </thead>
            <tbody>
              
                @forelse (session()->get('duplicate') as $user)
                <tr>
                    <td scope="row">{{$loop->index+1}}</td>
                  <td>{{$user['name'] ?? 'null'}}</td>
                  <td>{{$user['national_id'] ?? 'null'}}</td>
                </tr>
                 @empty
                 @endforelse 
               
              </tbody>
          </table>
          @endif
        <div class="card">
            <div class="position-absolute w-100 h-100 p-0 m-0"  id="loading" style="background-color: #0002;z-index: 10; display: none;" >
                    <div class="spinner-border text-success position-absolute h3" style="width: 3rem; height: 3rem; top: 50%; left: 50%; z-index: 10;" role="status">
                    <span class="sr-only">Loading...</span>
                    </div>
            </div>
            <div class="card-header">
                <h6>اضافة المتدربين من ملف Excel</h6>
            </div>
            <div class="card-body">
        <form id="excel_form" class="form" method="POST" action="{{ route('importExcel') }}"
            enctype="multipart/form-data">
            @csrf
            <div class="form-row mb-3">
                <div class="col-sm-4">
                    <label for="program" class="pl-1"> البرنامج </label>
                    <select required name="program" id="program" class="form-controller w-100"
                        onchange="fillDepartments()">
                        <option value="" disabled selected>أختر</option>
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
                <div class="col-sm-4">

                    <label for="department" class="pl-1"> القسم </label>
                    <select required name="department" id="department" class="form-controller w-100 "
                        onchange="fillMajors()">
                        <option value="" disabled selected>أختر</option>
                    </select>
                    @error('department')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-sm-4">
                    <label for="major" class="pl-1"> التخصص </label>
                    <select required name="major" id="major" class="form-controller w-100">
                        <option value="" disabled selected>أختر</option>
                    </select>
                    @error('major')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="excel_file">أختر الملف</label>
                <input required type="file" class="form-control-file" id="excel_file" name="excel_file">
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
            </div>
        <script>

            var programs = @php echo $programs; @endphp;    

            function findMajor(programs, program_id, department_id) {
                for (var i = 0; i < programs.length; i++) {
                    for (var j = 0; j < programs[i].departments.length; j++) {
                        if (programs[i].id == program_id && programs[i].departments[j].id == department_id) {
                            return programs[i].departments[j];
                        }
                    }
                }
            }


            function findDepartment(programs, program_id) {

                for (var i = 0; i < programs.length; i++) {
                    if (programs[i].id == program_id) {
                        return programs[i].departments;
                    }
                }
            }

            function fillDepartments() {
                var prog = document.getElementById('program').value;
                console.log(prog);
                var dept = document.getElementById('department');
                dept.innerHTML = null;
                var departments = findDepartment(programs, prog);
                console.log(departments);
                for (var i = 0; i < departments.length; i++) {
                    var option = document.createElement('option');
                    option.innerHTML = departments[i].name;
                    option.value = departments[i].id;
                    dept.appendChild(option);
                }
                fillMajors();
            }


            function fillMajors() {
                var prog = document.getElementById('program').value;
                var dept = document.getElementById('department').value;
                var mjr = document.getElementById('major');
                mjr.innerHTML = null;
                var majors = findMajor(programs, prog, dept).majors;
                for (var i = 0; i < majors.length; i++) {
                    var option = document.createElement('option');
                    option.innerHTML = majors[i].name;
                    option.value = majors[i].id;
                    mjr.appendChild(option);
                }

            }




            window.onload = function() {
                $("#excel_form").submit(function(e) 
                {
                    $('#loading').css('display','block');
                });
            }

        </script>
    </div>
@stop
