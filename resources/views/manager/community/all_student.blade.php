@extends('layouts.app')
@section('content')
    <div class="p-1 m-5">
        <div class="table-responsive">

            <form>
                <table class="table table-sm table-bordered table-striped  table-hover">
                    <thead class="text-center">
                      
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">رقم الهوية</th>
                            <th scope="col">الاسم</th>
                            <th scope="col">رقم الجوال</th>
                            <th scope="col">البرنامج</th>
                            <th scope="col">القسم</th>
                            <th scope="col">التخصص</th>
                            <th scope="col">الحالة</th>
                            <th scope="col">ايصالات السداد</th>
                            <th scope="col">المبلغ المدفوع</th>
                            <th scope="col">حالة التدقيق</th>
                            <th scope="col">ملاحظات المدقق</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            @php
                                //$receipts = Storage::disk('studentDocuments')->files($user->national_id . '/receipts');
                                // dd( $receipts);
                            @endphp
                            <tr>
                                <th scope="row">{{ $loop->index + 1 ?? '' }}</th>
                                <td>{{ $user->national_id ?? 'لا يوجد' }} </td>
                                <td>{{ $user->name ?? 'لا يوجد' }} </td>
                                <td>{{ $user->phone ?? 'لا يوجد' }} </td>
                                <td>{{ $user->student->program->name ?? 'لا يوجد' }} </td>
                                <td>{{ $user->student->department->name ?? 'لا يوجد' }} </td>
                                <td>{{ $user->student->major->name ?? 'لا يوجد' }} </td>
                                <td>{{ __($user->student->traineeState) ?? 'لا يوجد' }} </td>

                                {{-- <td>
                                    <a data-toggle="popover"  onclick="popup()" title="الايصالات" class="link p-0 m-0"
                                        data-content='
                                                @foreach ($user['receipts'] as $receipt)
                                                 <a class="d-block" href="{{ route('GetStudentDocument',['path' => $receipt ]) }}">{{ substr($receipt, 20, 10) }}</a>
                                                @endforeach
                                            '>عرض الايصالات</a>
                                </td> --}}
                                <td class="text-center">
                                    @forelse ($user['receipts'] as $receipt)
                                        <a class="d-block"  target="_blank"
                                            href="{{ route('GetStudentDocument', ['path' => $receipt]) }}">{{ substr($receipt, 20, 10) }}</a>
                                    @empty
                                        لايوجد
                                    @endforelse
                                </td>
                                <td contenteditable class="text-center">{{ $user->student->wallet ?? 'لا يوجد' }} </td>
                                <td class="text-center">
                                    <input type="checkbox" class="custom-checkbox"
                                        {{ $user->student->documents_verified == true ? 'checked' : '' }}
                                        value="{{ $user->student->documents_verified }}">
                                </td>
                                <td contenteditable>{{ $user->student->note ?? '' }} </td>
                            </tr>
                        @empty

                        @endforelse
                    </tbody>
                </table>

                <div class="text-right">
                    <input type="submit" value="ارسال" class="btn btn-primary px-5">
                </div>
            </form>
        </div>
        <script>
            function popup() {
                $('[data-toggle="popover"]').popover({
                    html: true
                });
            }

        </script>
    </div>
@stop
