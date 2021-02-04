@extends('layouts.app')
@section('content')
<div style="text-align: right !important" dir="rtl" lang="ar"class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $message)
                <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif
            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

<form class="border rounded p-3 bg-white" method="POST" action="/excel/import" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
      <label for="excel_file">أختر الملف</label>
      <input type="file" class="form-control-file" id="excel_file"  name="excel_file">
    </div>
    <div class="form-group">
      <input type="submit" name="excel_submit" id="excel_submit" value="أرسال">
      @error('excel_file')
      <span class="invalid-feedback" role="alert">
          <strong>{{ $message }}</strong>
      </span>
  @enderror
    </div>
  </form>
</div>
@stop