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
    <form id="updateUserForm" action="/user/update" method="post" accept-charset="utf-8" enctype="multipart/form-data">
        @csrf
        <!-- national ID -->
        <div class="form-group">
            <label for="national_id">رقم الهوية</label>
            <input disabled type="text" class="form-control p-1 m-1 " id="national_id" name="national_id" value="{{$user->national_id }}">
        </div>

        <!-- full name -->
        <div class="form-group">
            <label for="name">الاسم</label>
            <input disabled type="text" class="form-control p-1 m-1  " id="name" name="name" value=" {{$user->name }}">
        </div>

        <!-- phone number -->
        <div class="form-group">
            <label for="phone">رقم الجوال</label>
                <input required disabled="true" type="phone" class="form-control p-1 m-1" id="phone" name="phone" value="{{ $user->phone }} ">
            <!-- <div class="input-group mb-3">
                <button type="button" onclick="EditPhoneClicked()" id="editPhoneBtn" class="btn btn-sm px-2 m-1 btn-primary font-weight-bold">تعديل</button>
            </div> -->
        </div>

        <!-- email -->
        <div class="form-group">
            <label for="email">البريد الالكتروني</label>
            <input required type="email" class="form-control p-1 m-1" id="email" name="email" value="{{ $user->email }} ">
        </div>

        <!-- department and major -->
        <div class="form-row">

            <!-- department -->
            <div class="col-sm-6">
                <label for="department"> القسم </label>
                <input disabled required type="text" class="form-control  " id="department" name="department" value="{{ $user->department->name }}">
            </div>

            <!-- major -->
            <div class="col-sm-6">
                <label for="major"> التخصص </label>
                <input disabled required type="text" class="form-control  " id="major" name="major" value="{{ $user->major->name }}">
            </div>
        </div>

        <!-- trainee state -->
        <div class="form-row my-4">
            <div class="custom-control custom-radio custom-control-inline col-sm-3 m-0">
                <input value="trainee" type="radio" onclick="changeTraineeState('trainee')" id="trainee" name="traineeState" class="custom-control-input" checked>
                <label class="custom-control-label" for="trainee">متدرب</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline col-sm-3 m-0">
                <input value="employee" type="radio" onclick="changeTraineeState('employee')"id="employee" name="traineeState" class="custom-control-input">
                <label class="custom-control-label" for="employee">أحد منسوبي المؤسسة</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline col-sm-3 m-0">
                <input value="employeeSon" type="radio" onclick="changeTraineeState('employeeSon')" id="employeeSon" name="traineeState" class="custom-control-input">
                <label class="custom-control-label" for="employeeSon">من ابناء منسوبي المؤسسة</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline col-sm-3 m-0">
                <input value="privateState" type="radio" onclick="changeTraineeState('privateState')" id="privateState" name="traineeState" class="custom-control-input">
                <label class="custom-control-label" for="privateState">الظروف الخاصة</label>
            </div>
        </div>

        <!-- cost -->
        <div class="form-inline" id="costGroup">
            <div class="col-sm-4">
                <label for="cost"> المبلغ المراد سداده</label>
                <div class="input-group mb-3">
                    <input disabled required type="text" class="form-control  " id="cost" name="cost" value="{{ $user->major->hours * $user->student->program->hourPrice }}">
                    <span class="input-group-text">SR</span>
                </div>
            </div>
            <div class="col-sm-8" style="display: none;" id="pledgeSection">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="pledge" id="pledge" required>
                    <label class="form-check-label mr-1"> اتعهد بدفع كامل المبلغ في حالة عدم موافقة المؤسسة
                    </label>
                </div>
            </div>
        </div>

        <!-- national id image -->
        <div class="form-group">
            <label for="">صورة الهوية الوطنية </label>
            <input type="file" name="identity" class="form-control" value="">
        </div>

        <!-- certificate image -->
        <div class="form-group">
            <label for="">صورة من المؤهل </label>
            <input type="file" name="degree" class="form-control" value="">
        </div>

        <!-- payment receipt image -->
        <div class="form-group" id="receipt">
            <label for=""> صورة إيصال السداد</label>
            <input type="file" name="payment_receipt" class="form-control" id="receiptImg">
        </div>

        <!-- submet button -->
        <div class="form-group my-3">
            <input type="button" onclick="formSubmit()" name="form_submit" id="form_submit" value="أرسال" class="btn btn-primary">
        </div>
    </form>
</div>
<script>
    // function EditPhoneClicked() {
    //     var editPhoneBtn = document.getElementById('editPhoneBtn');


    //     if (document.getElementById('phone').disabled == true) {
    //         document.getElementById('phone').disabled = false;
    //         editPhoneBtn.classList.remove('btn-primary');
    //         editPhoneBtn.classList.add('btn-success');
    //         editPhoneBtn.innerHTML = " تـم ";
    //     } else {
    //         document.getElementById('phone').disabled = true;
    //         editPhoneBtn.classList.remove('btn-success');
    //         editPhoneBtn.classList.add('btn-primary');
    //         editPhoneBtn.innerHTML = "تعديل";
    //     }

    // }

    function formSubmit() {
        document.getElementById('phone').disabled = false;
        document.getElementById('updateUserForm').submit();
    }

    function changeTraineeState(state) {
        var user = [@php echo $user;@endphp];
        var hours = user[0].major.hours;
        var hourCost = 
        @php
        if(isset($user->student->program->hourPrice)){
            echo $user->student->program->hourPrice; 
        }else{
            echo 550;
        }
         @endphp;
         ;
        var costGroup = document.getElementById('costGroup');
        switch (state) {
            case 'trainee':
                document.getElementById('cost').value = hours * hourCost;
                $('#pledgeSection').hide();
                $('#receiptImg').prop('disabled', false);
                $('#costGroup').show();
                $('#receipt').show();
                break;

            case 'employee':
                document.getElementById('cost').value = hours * hourCost - (hours * hourCost * 0.75);
                $('#pledgeSection').show();
                $('#receiptImg').prop('disabled', false);
                $('#costGroup').show();
                $('#receipt').show();
                break;

            case 'employeeSon':
                document.getElementById('cost').value = hours * hourCost - (hours * hourCost * 0.5);
                $('#pledgeSection').show();
                $('#receiptImg').prop('disabled', false);
                $('#costGroup').show();
                $('#receipt').show();
                break;

            case 'privateState':
                $('#costGroup').hide();
                $('#receipt').hide();
                $('#receiptImg').prop('disabled', true);
                break;

            default:
                break;
        }
    }
</script>
</div>

@stop