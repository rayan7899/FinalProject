@extends('layouts.app')
@section('content')
<div class="container">
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
                    <img id="modalImage" src="" alt="image" class="img-fluid" />
                </div>
            </div>
        </div>
    </div>
</div>
المتدربين المدققة ايصالاتهم

      <table class="table nowrap display cell-border" id="mainTable">
        <thead class="text-center">
          <tr>
            <th scope="col">#</th>
            <th scope="col">رقم الهوية</th>
            <th scope="col">اسم المتقدم</th>
            <th scope="col">رقم الجوال</th>
            <th scope="col">البرنامج</th>
            <th scope="col">القسم</th>
            <th scope="col">التخصص</th>
            <th scope="col">الحالة</th>
            <th scope="col">ملاحظات المدقق</th>
            <th scope="col">الايصالات</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($users))
            @forelse($users as $user)
              <tr>
                <th scope="row">{{ $loop->index + 1 ?? '' }}</th>
                <td>{{ $user->national_id ?? 'لا يوجد' }} </td>
                <td>{{ $user->name ?? 'لا يوجد' }} </td>
                <td>{{ $user->phone ?? 'لا يوجد' }} </td>
                <td>{{ $user->student->program->name ?? 'لا يوجد' }} </td>
                <td>{{ $user->student->department->name ?? 'لا يوجد' }} </td>
                <td>{{ $user->student->major->name ?? 'لا يوجد' }} </td>
                <td>{{ __($user->student->traineeState) ?? 'لا يوجد' }} </td>
                <td>{{ $user->student->note ?? 'لا يوجد' }} </td>
                <td class="text-center">

                    @php
                        $splitByDot = explode('.', $user->student->receipt);
                        $fileExtantion = end($splitByDot);
                    @endphp
                    @if ($fileExtantion == 'pdf' || $fileExtantion == 'PDF')
                        <a data-toggle="modal" data-target="#pdfModal" href="#"
                            onclick="showPdf('{{ route('GetStudentDocument', ['path' => $user->student->receipt]) }}','pdf')">
                            <img style="width: 20px" src="{{ asset('/images/pdf.png') }}" />
                        </a>
                    @else
                        <a data-toggle="modal" data-target="#pdfModal" href="#"
                            onclick="showPdf('{{ route('GetStudentDocument', ['path' => $user->student->receipt]) }}','img')">
                            <img src=" {{ asset('/images/camera_img_icon.png') }}" style="width:25px;"
                                alt="Image File">
                        </a>
                    @endif

                </td>
              </tr>
            @empty
              <td colspan="9"> لا يوجد بيانات</td>
            @endforelse
          @else
            <p>لا يوجد بيانات</p>
          @endif

        </tbody>
      </table>
</div>
@stop