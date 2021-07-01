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
            <div class="card-header h5">{{ __('ادارة الفصل التدريبي') }}</div>
            <div class="card-body p-0 px-5">
                <div class="p-2">
                    <a href="{{ route('newSemester') }}" class="btn btn-outline-primary p-3 m-2"
                        style="font-size: 16px; width: 220px;">فصل تدريبي جديد</a>
                    <a href="{{ route('editSemesterView') }}" class="btn btn-outline-primary p-3 m-2"
                        style="font-size: 16px; width: 220px;">تعديل الفصل الحالي</a>
                    <a href="#" class="btn btn-outline-primary p-3 m-2" onclick="openModal()"
                        style="font-size: 16px; width: 220px;">اتاحة\ايقاف اضافة مقررات</a>
                </div>

            </div>

        </div>
    </div>
    <script>
        function openModal() {
            hasSemester = {{isset($semester) ? 'true' : 'false'}};
            if(!hasSemester){
                Swal.fire({
                position: "center",
                html: "<h5>لم يبدأ الفصل التدريبي </h5>",
                icon: "info",
                confirmButtonText:'اغلاق',
                showConfirmButton: true,
            });
            return;
            }
            Swal.fire({
                title: 'اتاحة\/ايقاف اضافة مقررات',
                html: `
                <form id="toggleCanRequestHours" action="{{route('toggleAllowAddHours')}}" method="POST">
                                @csrf
                                <div class="input-group" dir="ltr">
                                    <label class="form-control" aria-label="Text input with checkbox">
                                        @if(isset($semester))
                                            @if ($semester->can_request_hours) متاح @else غير متاح @endif
                                        @endif
                                    </label>
                                    <div class="input-group-append">
                                    <div class="input-group-text">
                                        حالة اتاحة اضافة المقررات
                                    </div>
                                    </div>
                                </div>
                            </form>
                            `,

                confirmButtonText: ` @if(isset($semester))
                                            @if ($semester->can_request_hours) 
                                                ايقاف 
                                            @else 
                                                اتاحة 
                                            @endif 
                                        @else
                                        اغلاق
                                    @endif
                                    `,
                showCancelButton: true,
                cancelButtonText: 'إلغاء',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("toggleCanRequestHours").submit();
                }
            })
        }

    </script>
@stop
