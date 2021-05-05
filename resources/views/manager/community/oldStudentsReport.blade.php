@extends('layouts.app')
@section('content')
    <div class="container-fluid">
    المتدربين المستمرين
        @if (isset($error) || !empty($fetch_errors))
            <div class="alert alert-danger">
                @if (isset($error))
                    {{ $error }}
                @endif
            </div>
        @endif
        <div class="table-responsive p-2 bg-white rounded border">
            <table class="table nowrap display cell-border" id="studentsReportTbl">
                <thead class="text-center">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">رقم الهوية</th>
                        <th scope="col">الاسم</th>
                        <th scope="col">رقم الجوال</th>
                        <th scope="col">البرنامج</th>
                        <th scope="col">القسم</th>
                        <th scope="col">التخصص</th>
                        <th scope="col">المستوى</th>
                        <th scope="col">القبول النهائي</th>
                    </tr>
                    <tr>

                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>

                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        var studentsReportJson = "{{route('studentsReportCommunityJson',['type' => 'old'])}}"
    </script>
@stop
