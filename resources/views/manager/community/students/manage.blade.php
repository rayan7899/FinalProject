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
        <div class="card">
            <div class="card-header h5">{{ __('ادارة المتدربين') }}</div>
            <div class="card-body p-0 px-5">
                <table class="table">
                    <thead>
                        <tr>
                        {{-- 
                            #####################################################
                            
                                    This page was created for future use
                            
                            #####################################################
                            
                            --}}
                        </tr>
                    </thead>
                    <tbody>
                       
                    </tbody>
                </table>
                <div class="p-2">
                    <a class="btn btn-primary" href="{{ route('createStudentForm')}}">{{ __('اضافة متدرب') }}
                       
                    </a>
                  
                </div>

            </div>
           
        </div>
    </div>
@stop
