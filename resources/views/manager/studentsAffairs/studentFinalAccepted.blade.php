@extends('layouts.app')
@section('content')
    <!-- modal for showing student files -->
    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 75%" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfName"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe id="pdfIfreme" src="" width="100%" height="600px"></iframe>
                    <div class="text-center">
                        <img id="modalImage" src="" alt="image" class="img-fluid"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- page content -->
    <div class="container-fluid">
        @if (isset($error) || !empty($fetch_errors))
            <div class="alert alert-danger">
                @if (isset($error))
                    {{ $error }}
                @endif
                @if (isset($fetch_errors))
                    <p>حصل خطا في جلب بعض ملفات المتدربين:</p>
                    @foreach ($fetch_errors as $err)
                        <ul>
                            <li>{{ $err }}</li>
                        </ul>
                    @endforeach
                @endif
            </div>
        @endif
        <div class="table-responsive p-2 bg-white rounded border">
            <h6 class="text-center" style="position: relative; top:10px"> القبول النهائي-شؤون المتدربين</h6>
            <table class="table nowrap display cell-border" id="mainTable">
                <thead class="text-center">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">رقم الهوية</th>
                        <th scope="col">الاسم</th>
                        <th scope="col">رقم الجوال</th>
                        <th scope="col">البرنامج</th>
                        <th scope="col">القسم</th>
                        <th scope="col">التخصص</th>
                        <th scope="col">الهوية</th>
                        <th scope="col">المؤهل</th>
                        <th scope="col">القبول الهائي</th>
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
                        <th class="filterhead" scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($users))
                        @forelse ($users as $user)
                            <tr>
                                <th class="text-center" scope="row">{{ $loop->index + 1 ?? '' }}</th>
                                <td class="text-center">{{ $user->national_id ?? 'لا يوجد' }} </td>
                                <td>{{ $user->name ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $user->phone ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $user->student->program->name ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $user->student->department->name ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $user->student->major->name ?? 'لا يوجد' }} </td>

                                <!-- get ideentity -->
                                <td class="text-center">
                                    @if (isset($user->student->identity))
                                        @php
                                        $splitByDot = explode('.', $user->student->identity);
                                        $fileExtantion = end($splitByDot);
                                        @endphp
                                        @if ($fileExtantion == 'pdf' || $fileExtantion == 'PDF')
                                            <a data-toggle="modal" data-target="#pdfModal" href="#"
                                               onclick="showPdf('{{ route('GetStudentDocument', ['path' => $user->student->identity]) }}','pdf')">
                                                <img style="width: 20px" src="{{ asset('/images/pdf.png') }}" />
                                            </a>
                                        @else
                                            <a data-toggle="modal" data-target="#pdfModal" href="#"
                                               onclick="showPdf('{{ route('GetStudentDocument', ['path' => $user->student->identity]) }}','img')">        
                                                <img src=" {{ asset('/images/camera_img_icon.png') }}" style="width:25px;" alt="Image File">
                                            </a>
                                        @endif
                                    @else
                                        غير متوفر
                                    @endif
                                </td>

                                <!-- get degree -->
                                <td class="text-center">
                                    @if (isset($user->student->degree))
                                        @php
                                        $splitByDot = explode('.', $user->student->degree);
                                        $fileExtantion = end($splitByDot);
                                        @endphp
                                        @if ($fileExtantion == 'pdf' || $fileExtantion == 'PDF')
                                            <a data-toggle="modal" data-target="#pdfModal" href="#"
                                               onclick="showPdf('{{ route('GetStudentDocument', ['path' => $user->student->degree]) }}','pdf')">
                                                <img style="width: 20px" src="{{ asset('/images/pdf.png') }}" />
                                            </a>
                                        @else
                                            <a data-toggle="modal" data-target="#pdfModal" href="#"
                                               onclick="showPdf('{{ route('GetStudentDocument', ['path' => $user->student->degree]) }}','img')">        
                                                <img src=" {{ asset('/images/camera_img_icon.png') }}" style="width:25px;" alt="Image File">
                                            </a>
                                        @endif
                                    @else
                                        غير متوفر
                                    @endif
                                </td>

                                <td class="text-center">
                                    <input id="check_{{ $user->national_id }}" type="checkbox"
                                        onchange="window.finalAcceptChanged('{{ $user->national_id }}',event)"
                                        class="custom-checkbox" style="width: 16px; height: 16px;"
                                        {{ $user->student->final_accepted == true ? 'checked' : '' ?? '' }}
                                        value="{{ $user->student->final_accepted }}" />
                                </td>
                            </tr>
                        @empty
                            <td colspan="12">لا يوجد بيانات</td>
                    @endforelse
                    @endif
                </tbody>
            </table>
        </div>
        <script>
            var finalAcceptedRoute = "{{ route('finalAcceptedUpdate') }}";

        </script>
    </div>
@stop
