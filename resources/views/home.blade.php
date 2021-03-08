@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div> --}}
            @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
             @endif
             @if(isset($error))
             <div class="alert alert-danger">
                 {{ $error }}
             </div>
              @endif
            <div class="form-group">
            <div dir="ltr"  class="input-group mb-1">
                <input readonly type="text" class="form-control text-right bg-white" placeholder="" aria-label="Recipient's username" aria-describedby="basic-addon2" value="{{$user->national_id ?? "حدث خطأ"}}">
                <div class="input-group-append">
                  <span class="input-group-text text-center" style="width: 100px;">رقم الهوية</span>
                </div>
            </div>
             <div dir="ltr"  class="input-group mb-1">
                <input readonly type="text" class="form-control text-right bg-white" placeholder="" aria-label="Recipient's username" aria-describedby="basic-addon2" value="{{$user->name ?? "حدث خطأ"}}">
                <div class="input-group-append">
                  <span class="input-group-text text-center" style="width: 100px;" >الاسم</span>
                </div>
            </div>
            <div dir="ltr"  class="input-group mb-1">
                <input readonly type="text" class="form-control text-right bg-white" placeholder="" aria-label="Recipient's username" aria-describedby="basic-addon2" 
                value="{{$user->documents_verified == true ? 'تم مراجعة الطلب' : 'قيد المراجعة'}}">
                <div class="input-group-append">
                  <span class="input-group-text text-center" style="width: 100px;" >حالة الطلب</span>
                </div>
            </div>

            </div>

        </div>
    </div>
</div>
@endsection
