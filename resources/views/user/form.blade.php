@extends('layouts.app')
@section('content')
<div class="container">
	<form action="/user/update" method="post" accept-charset="utf-8">
			@csrf
            <div class="form-group">
                <label for="national_id">رقم الهوية</label>
                <input type="text" class="form-control p-1 m-1" disabled id="national_id" name="national_id" value="{{ $user->national_id }} ">
            </div>

            <div class="form-group">
                <label for="name">الاسم</label>
                <input type="text" class="form-control p-1 m-1" disabled id="name" name="name" value="{{ $user->name }} ">
            </div>

            <div class="form-group">
                <label for="phone">رقم الجوال</label>
                <input required type="phone" class="form-control p-1 m-1" id="phone" name="phone" value="{{ $user->phone }} ">
            </div>

            <div class="form-group">
                <label for="email">البريد الالكتروني</label>
                <input required type="email" class="form-control p-1 m-1" id="email" name="email" value="{{ $user->email }} ">
            </div>

            <div class="form-row">

            	<div class="col-sm-4">
            		<label for="department" class=""> القسم </label>
                	<input required disabled type="text" class="form-control" id="department" name="department" value="{{ $user->department->name }} ">
            	</div>

            	<div class="col-sm-4">
            		<label for="major" class=""> التخصص </label>
                	<input required disabled type="text" class="form-control" id="major" name="major" value="{{ $user->major->name }} ">
            	</div>

							<div class="col-sm-4">
									<label for="cost"> المبلغ المراد سداده</label>
									<input required disabled type="text" class="form-control" id="cost" name="cost" value="{{ $user->major->cost }} ">
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


@stop
