@extends('layouts.app')
@section('content')
    <div class="container">
        <form id="updateUserForm" action="/user/update" method="post" accept-charset="utf-8">
            @csrf
            <div class="form-group">
                <label for="national_id">رقم الهوية</label>
                <label type="text" class="form-control p-1 m-1 bg-light"  id="national_id" name="national_id"
                    >{{ $user->national_id }}</label>
            </div>

            <div class="form-group">
                <label for="name">الاسم</label>
                <label type="text" class="form-control p-1 m-1  bg-light"  id="name" name="name">{{ $user->name }}</label>
            </div>

            <div class="form-group">
                <label for="phone">رقم الجوال</label>
				<div class="input-group mb-3">
                <input required disabled="true" type="phone" class="form-control p-1 m-1" id="phone" name="phone"
                    value="{{ $user->phone }} ">
					<button type="button" onclick="EditPhoneClicked()" id="editPhoneBtn" class="btn btn-sm px-2 m-1 btn-primary font-weight-bold">تعديل</button>
				</div>
            </div>

            <div class="form-group">
                <label for="email">البريد الالكتروني</label>
                <input required type="email" class="form-control p-1 m-1" id="email" name="email"
                    value="{{ $user->email }} ">
            </div>

            <div class="form-row">

                <div class="col-sm-4">
                    <label for="department" class=""> القسم </label>
                    <label required  type="text" class="form-control  bg-light" id="department" name="department">{{ $user->department->name }}</label>
                </div>

                <div class="col-sm-4">
                    <label for="major" class=""> التخصص </label>
                    <label required  type="text" class="form-control  bg-light" id="major" name="major">{{ $user->major->name }}</label>
                </div>

                <div class="col-sm-4">
					<label for="cost"> المبلغ المراد سداده</label>
					<div class="input-group mb-3">
						<label required  type="text" class="form-control  bg-light" id="cost" name="cost">{{ $user->major->cost }}</label>
						<span class="input-group-text">SR</span>
					  </div>
                </div>
            </div>



            <div class="form-group">
                <label for="">صورة الهوية الوطنية </label>
                <input type="file" name="" class="form-control" value="">
            </div>

            <div class="form-group">
                <label for="">صورة من المؤهل </label>
                <input type="file" name="" class="form-control" value="">
            </div>

            <div class="form-group">
                <label for=""> صورة الايصال </label>
                <input type="file" name="" class="form-control" value="">
            </div>

            <div class="form-group my-3">
                <input type="submit" name="form_submit" id="excel_submit" value="أرسال" class="btn btn-primary">
            </div>
        </form>
    </div>
    <script>
      function  EditPhoneClicked()
      {
        var updateUserForm = document.getElementById('updateUserForm');
        var isEditDisabled = document.getElementById('phone');
        var editPhoneBtn = document.getElementById('editPhoneBtn');
      

        if(document.getElementById('phone').disabled == true)
        {
            document.getElementById('phone').disabled = false;
            editPhoneBtn.classList.remove('btn-primary');
            editPhoneBtn.classList.add('btn-success');
            editPhoneBtn.innerHTML = " تـم " ;
        }else
        {
            document.getElementById('phone').disabled = true;
            editPhoneBtn.classList.remove('btn-success');
            editPhoneBtn.classList.add('btn-primary');
            editPhoneBtn.innerHTML = "تعديل";
        }
       
      }
    </script>
    </div>

@stop
