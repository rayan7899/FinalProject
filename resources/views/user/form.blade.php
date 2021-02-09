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
                    <label for="department"> القسم </label>
                    <label required  type="text" class="form-control  bg-light" id="department" name="department">{{ $user->department->name }}</label>
                </div>

                <div class="col-sm-4">
                    <label for="major" > التخصص </label>
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
                <input type="file" name="identity" class="form-control" value="">
            </div>

            <div class="form-group">
                <label for="">صورة من المؤهل </label>
                <input type="file" name="degree" class="form-control" value="">
            </div>

            <div class="form-group">
                <label for=""> صورة إيصال السداد</label>
                <input type="file" name="payment_receipt" class="form-control" value="">
            </div>

            <div class="form-group my-3">
                <input type="button" onclick="formSubmit()" name="form_submit" id="form_submit" value="أرسال" class="btn btn-primary">
            </div>
        </form>
    </div>
    <script>
      function  EditPhoneClicked()
      {
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

      function formSubmit(){
        document.getElementById('phone').disabled = false;
        document.getElementById('updateUserForm').submit();
      }
    </script>
    </div>

@stop
