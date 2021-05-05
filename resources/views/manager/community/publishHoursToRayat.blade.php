@extends('layouts.app')
@section('content')

    {{-- @dd($users[0]->student->courses) --}}
    <div class="container-fluid">
        @if ($errors->any() || isset($error))
            <div class="alert alert-danger">
                @if ($errors->any())
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @endif
                @if (isset($error))
                    {{ $error }}
                @endif
            </div>
        @endif

        <div class="table-responsive p-2 bg-white rounded border">
            <table onloadeddata="window.changeHoursInputs()" id="publishToRayatTbl" class="table nowrap display cell-border">

                <thead>
                    <tr>
                        <th colspan="8">
                        </th>
                        <th colspan="2">
                            <div id="allHoursContainer" class="d-inline">
                                <label for="allHoursValue">تعديل جميع الساعات:</label>
                                <input type="number" name="allHoursValue" id="allHoursValue" class="d-inline" placeholder=""
                                    aria-describedby="helpId">
                                <button onclick="window.changeHoursInputs()" class="btn btn-primary btn-sm">تعديل</button>
                            </div>
                        </th>

                    </tr>
                    <tr>
                        <th>#</th>
                        <th>رقم الهوية</th>
                        <th>اسم المتدرب </th>
                        <th>رقم الجوال</th>
                        <th>البرنامج</th>
                        <th>القسم</th>
                        <th>التخصص</th>
                        <th>رقم الطلب</th>
                        <th> عدد الساعات</th>
                        <th class="text-center">التسجيل في رايات</th>
                    </tr>
                    <tr>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <script>
            var publishToRayat = "{{$type == 'community' ? route('publishToRayatStoreCommunity') : route('publishToRayatStoreAffairs')}}"
            var getStudentForRayatApi = "{{$type == 'community' ? route('getStudentForRayatCommunityApi',['type' => $type]) : route('getStudentForRayatAffairsApi',['type' => $type])}}"

        </script>

    </div>
@stop
