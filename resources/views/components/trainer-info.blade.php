<div>
    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
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
    <div class="card my-4">
        <div class="card-header">
            <h5 class="card-title">
                بيانات المدرب
            </h5>
        </div>
        <div class="row px-5 py-2">
            <div class="col-6 p-1">
                {{-- Name --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white h5"
                            value="{{ $user->name ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">الاسم</label></span>
                        </div>
                    </div>
                </div>

                {{-- national_id --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->national_id ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">رقم الهوية</label></span>
                        </div>
                    </div>
                </div>
                {{-- phone --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->phone ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">رقم الجوال</label></span>
                        </div>
                    </div>
                </div>

                {{-- email --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->email ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">البريد الالكتروني</label></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 p-1">
                {{-- bct_id --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->trainer->bct_id ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">الرقم الوظيفي</label></span>
                        </div>
                    </div>
                </div>

                {{-- employer --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->trainer->employer ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">جهة العمل</label></span>
                        </div>
                    </div>
                </div>

                {{-- qualification --}}
                <div class="row p-0 m-0">
                    <div class="col-11 p-0 m-0">
                        <div dir="ltr" class="input-group mb-1">
                            <input readonly type="text" class="form-control text-right bg-white"
                                style="border-left-width: 0.5px; border-top-left-radius: 0; border-bottom-left-radius: 0;"
                                value="{{ __($user->trainer->qualification) ?? 'لا يوجد' }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-center" style="width: 120px;"><label
                                        class="text-center m-0 p-0 w-100">المؤهل</label></span>

                            </div>
                        </div>
                    </div>
                    <div class="col-1 p-0 m-0">
                        @php
                            $url = route('trainerGetDocument', ['national_id' => $user->national_id, 'filename' => 'degree']);
                            $files = Storage::disk('trainerDocuments')->files($user->national_id);
                            $filePath = $files[array_key_first(preg_grep('/degree/', $files))];
                            $ext = explode('.', $filePath);
                            $fileExtantion = end($ext);
                        @endphp
                        @if ($fileExtantion == 'pdf' || $fileExtantion == 'PDF')
                            <a class="form-control"
                                style="border-right-width: 0.5px; border-top-right-radius: 0; border-bottom-right-radius: 0;"
                                data-toggle="modal" data-target="#pdfModal" href="#"
                                onclick="showPdf('{{ $url }}','pdf')">
                                <img style="width: 20px" src="{{ asset('/images/pdf.png') }}" />
                            </a>
                        @else
                            <a class="form-control"
                                style="border-right-width: 0.5px; border-top-right-radius: 0; border-bottom-right-radius: 0;"
                                data-toggle="modal" data-target="#pdfModal" href="#"
                                onclick="showPdf('{{ $url }}','img')">
                                <img src=" {{ asset('/images/camera_img_icon.png') }}" style="width:25px;"
                                    alt="Image File">
                            </a>
                        @endif
                    </div>

                </div>


                {{-- department --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->trainer->department->name ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">القسم</label></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
