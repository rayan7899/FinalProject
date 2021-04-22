<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://www.tvtc.gov.sa/css/rayat.css"> -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"
        defer></script>
</head>

<body>
    <div id="app">
        <div class="position-absolute w-100 h-100 p-0 m-0" id="loading"
            style="background-color: #0002;z-index: 10; display: none;">
            <div class="spinner-border text-success position-absolute h3"
                style="width: 3rem; height: 3rem; top: 50%; left: 50%; z-index: 10;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Navigation -->
        <div class="navigation-wrap bg-light start-header border shadow sticky-top start-style">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <nav class="navbar navbar-expand-md navbar-light px-0">

                            <div class="collapse navbar-collapse " id="navbarSupportedContent">
                                <ul class="navbar-nav mr-auto">
                                    <!-- Authentication Links -->
                                    @guest
                                        @if (Route::has('login'))
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                    href="{{ route('login') }}">{{ __('تسجيل الدخول') }}</a>
                                            </li>
                                        @endif

                                        @if (Route::has('register'))
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                    href="{{ route('register') }}">{{ __('Register') }}</a>
                                            </li>
                                        @endif
                                    @else
                                        <li class="nav-item dropdown">
                                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                                {{ Auth::user()->name }}
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item  text-right"
                                                    href="{{ route('home') }}">{{ __('Go Home') }}</a>
                                                <a class="dropdown-item text-right" href="{{ route('logout') }}"
                                                    onclick="event.preventDefault();
                                                                                                document.getElementById('logout-form').submit();">
                                                    {{ __('Logout') }}
                                                </a>

                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    class="d-none">
                                                    @csrf
                                                </form>
                                            </div>
                                        </li>
                                    @endguest
                                </ul>
                                @auth


                                    {{-- --------- department boss ----------- --}}

                                    {{-- @if (Auth::user()->hasRole('??????????????????'))
                                        <ul class="navbar-nav">
                                            <li class="nav-item"><a class="nav-link" href="{{route('deptBossDashboard')}}">{{ __('Go Home') }}</a></li>
                                            <li class="nav-item"><a class="nav-link" href="{{route('studentCourses')}}">الطلاب المتعثرين</a></li>
                                            <li class="nav-item"><a class="nav-link" href="{{route('coursesPerLevel')}}">الجداول المقترحة</a></li>
                                        </ul>
                                        @endif --}}

                                    @if (Auth::user()->isDepartmentManager())
                                        <ul class="navbar-nav">
                                            <li id="navbarDropdown" class="nav-link dropdown-toggle" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>رئيس
                                                القسم</li>
                                            <li class="nav-item dropdown">
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item"
                                                        href="{{ route('deptBossDashboard') }}">{{ __('Go Home') }}</a>
                                                    <a class="dropdown-item" href="{{ route('studentCourses') }}">الطلاب
                                                        المتعثرين</a>
                                                    <a class="dropdown-item" href="{{ route('coursesPerLevel') }}">الجداول
                                                        المقترحة</a>
                                                </div>
                                            </li>
                                        </ul>
                                    @endif

                                    {{-- --------- community ----------- --}}
                                    @if (Auth::user()->hasRole('خدمة المجتمع'))
                                        <ul class="navbar-nav">
                                            <li id="navbarDropdown" class="nav-link dropdown-toggle" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>خدمة
                                                المجتمع</li>
                                            <li class="nav-item dropdown">
                                                <div class="dropdown-menu dropdown-menu-right">

                                                    <a class="dropdown-item"
                                                        href="{{ route('rayatReportFormCommunity') }}">تقرير رايات</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('publishToRayatForm',["type" => "community"]) }}">الرفع
                                                        لرايات</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('paymentsReviewForm') }}">تدقيق الايصالات</a>
                                                    <a class="dropdown-item" href="{{ route('manageUsersForm') }}">ادارة
                                                        المستخدمين</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('communityDashboard') }}">{{ __('Go Home') }}</a>

                                                </div>
                                            </li>
                                        </ul>
                                    @endif


                                    {{-- --------- student affairs ----------- --}}
                                    @if (Auth::user()->hasRole('شؤون المتدربين'))
                                        <ul class="navbar-nav">
                                            <li id="navbarDropdown" class="nav-link dropdown-toggle" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                                شؤون المتدربين</li>
                                            <li class="nav-item dropdown">
                                                <div class="dropdown-menu dropdown-menu-right">

                                                    <a class="dropdown-item"
                                                        href="{{ route('finalAcceptedList') }}">قائمة
                                                        القبول النهائي</a>
                                                    <a class="dropdown-item" href="{{ route('NewStudents') }}">المتدربين
                                                        المستجدين </a>
                                                    <a class="dropdown-item" href="{{ route('finalAcceptedForm') }}">
                                                        القبول
                                                        النهائي </a>
                                                    <a class="dropdown-item" href="{{ route('AddExcelForm') }}">اضافة
                                                        اكسل
                                                        مستجدين</a>
                                                    <a class="dropdown-item" href="{{ route('OldForm') }}">
                                                        اضافة اكسل مستمرين </a>
                                                    <a class="dropdown-item" href="{{ route('rayatReportForm') }}">تقرير
                                                        رايات</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('publishToRayatForm',["type" => "affairs"]) }}">الرفع
                                                        لرايات</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('affairsDashboard') }}">{{ __('Go Home') }}</a>

                                                </div>
                                            </li>

                                        </ul>
                                    @endif


                                    {{-- --------- private state ----------- --}}
                                    @if (Auth::user()->hasRole('الإرشاد'))
                                        <ul class="navbar-nav">
                                            <li id="navbarDropdown" class="nav-link dropdown-toggle" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                                الارشاد</li>
                                            <li class="nav-item dropdown">
                                                <div class="dropdown-menu dropdown-menu-right">

                                                    <a class="dropdown-item"
                                                        href="{{ route('privateDashboard') }}">{{ __('Go Home') }}</a>

                                                    <a class="dropdown-item" href="{{ route('PrivateAllStudentsForm') }}">ظروف
                                                        خاصة</a>
                                                </div>
                                            </li>
                                        </ul>
                                    @endif

                                    {{-- ---------  student ----------- --}}
                                    @if (Auth::user()->hasRole('متدرب'))
                                        <ul class="navbar-nav">
                                            <li class="nav-item"><a class="nav-link"
                                                    href="{{ route('home') }}">{{ __('Go Home') }}</a>
                                            </li>
                                        </ul>
                                    @endif
                                @endauth

                                <a class="navbar-brand py-0" href="https://www.tvtc.gov.sa/" target="_blank">
                                    <img style="width: 250px;" class="" src="{{ asset('images/tvtclogo1.svg') }}"
                                        alt="" />
                                </a>
                                <button class="navbar-toggler" type="button" data-toggle="collapse"
                                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                    aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide Show -->
        <!-- <section>
                <img class="mySlides" src="/images/G20.jpg" style="width:100%;  height:758px;">
                <img class="mySlides" src="/images/25.png" style="width:100%; height:758px;">
                <img class="mySlides" src="/images/31.png" style="width:100%; height:758px;">
            </section> -->

        <main class="py-4 my-4 p-2" style="text-align: right !important; min-height: 500px;" dir="rtl">
            @yield('content')
        </main>


        <footer class="justify-content-end" dir="rtl">

            <div class="card bg-dark text-white">
                <img src="/images/background.jpg" style="height: 200px;" class="card-img" alt="...">
                <div class="card-img-overlay">
                    <p class="text-center mt-5 pt-5">
                        © جميع الحقوق محفوظة - المؤسسة العامة للتدريب التقني والمهني 2020 ( TVTC )
                    </p>
                </div>
            </div>
        </footer>
    </div>



    <!-- <script>
            // Automatic Slideshow - change image every 3 seconds
            var myIndex = 0;
            carousel();

            function carousel() {
                var i;
                var x = document.getElementsByClassName("mySlides");
                for (i = 0; i < x.length; i++) {
                    x[i].style.display = "none";
                }
                myIndex++;
                if (myIndex > x.length) {
                    myIndex = 1
                }
                x[myIndex - 1].style.display = "block";
                setTimeout(carousel, 4000);
            }
        </script> -->
</body>


</html>
