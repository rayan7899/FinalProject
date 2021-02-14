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

</head>


<body>
    <div id="app">

        

        <!-- Navigation -->
        <div class="navigation-wrap bg-light start-header start-style">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <nav class="navbar navbar-expand-md navbar-light">
                            <a class="navbar-brand" href="https://www.tvtc.gov.sa/" target="_blank"><img src="/images/tvtclogo1.jpg" alt="" /></a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                        <ul class="navbar-nav mr-auto">
                                            <!-- Authentication Links -->
                                            @guest
                                            @if (Route::has('login'))
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                            </li>
                                            @endif

                                            @if (Route::has('register'))
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                            </li>
                                            @endif
                                            @else
                                            <li class="nav-item dropdown">
                                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                                    {{ Auth::user()->name }}
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                        {{ __('Logout') }}
                                                    </a>

                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                        @csrf
                                                    </form>
                                                </div>
                                            </li>
                                            @endguest
                                        </ul>
                                <ul class="navbar-nav ml-auto py-4 py-md-0">
                                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4"><a class="nav-link" href="https://ugate.tvtc.gov.sa/AFrontGate/">البوابة الإلكترونية للقبول
                                            (قبولي)</a></li>
                                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 active">
                                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="index.html" role="button" aria-haspopup="true" aria-expanded="false">خدمات إلكترونية </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="https://serv.tvtc.gov.sa/app/trkletters/TrkLetter.aspx">الإستعلام عن
                                                المعاملات</a>
                                            <a class="dropdown-item" href="https://suppliers.tvtc.gov.sa/Guest/Default.aspx">المنافسات</a>
                                            <a class="dropdown-item" href="https://serv.tvtc.gov.sa/app/tktagents/default.aspx">شركات السياحة
                                                والسفر</a>
                                            <a class="dropdown-item" href="https://serv.tvtc.gov.sa/app/traineesgate/searchtrainees.aspx">استعلام
                                                عن حالة المتدرب</a>
                                            <a class="dropdown-item" href="https://serv.tvtc.gov.sa/app/traineesgate/TraineeBankAccount.aspx">الإستعلام
                                                عن حالة البطاقة البنكية للمتدرب</a>
                                            <a class="dropdown-item" href="https://serv.tvtc.gov.sa/app/traineesgate/TraineeBankAccount.aspx">بوابة
                                                الموردين</a>
                                            <a class="dropdown-item" href="https://teqani.tvtc.gov.sa/GuestForms/Default.aspx?lang=ar ">موقع
                                                تقني</a>
                                            <a class="dropdown-item" href="https://www.tvtc.gov.sa/rayat.html">رايات نظام
                                                خدمات المتدربين</a>
                                            <a class="dropdown-item" href="https://serv.tvtc.gov.sa/app/trkletters/TrkSalary.aspx">التحقق من
                                                خطابات التعريف وتثبيت الراتب للموظفين</a>
                                        </div>
                                    </li>
                                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 active">
                                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="index.html" role="button" aria-haspopup="true" aria-expanded="false"> وظائف </a>

                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="https://serv.tvtc.gov.sa/app/njobs/selectcomp.aspx">التقدم على
                                                الوظائف</a>
                                        </div>
                                    </li>
                                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4"><a class="nav-link" href="https://serv.tvtc.gov.sa/">بوابة الموظفين</a></li>
                                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4"><a class="nav-link" href="tech-support-page.html">بوابة الدعم الفني</a></li>
                                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4"><a class="nav-link" href="./pdf/TVTC-at-a-Glance-AR.pdf">تعرف علينا </a></li>
                                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">اتصل بنا</a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="https://nartqi.tvtc.gov.sa/ar/ticket">مكتب خدمة
                                                العملاء</a>
                                            <a class="dropdown-item">
                                                الرقم الموحد
                                                <br />
                                                011-2896664
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide Show -->
        <section>
            <img class="mySlides" src="/images/G20.jpg" style="width:100%;  height:758px;">
            <img class="mySlides" src="/images/25.png" style="width:100%; height:758px;">
            <img class="mySlides" src="/images/31.png" style="width:100%; height:758px;">
        </section>

        <main class="py-4" style="text-align: right !important" dir="rtl">
            @yield('content')
        </main>


        <footer class="justify-content-end" dir="rtl">

            <div class="card bg-dark text-white">
                <img src="/images/background.jpg" style="height: 250px;" class="card-img" alt="...">
                <div class="card-img-overlay">

                    <div>
                        <div class="container-fluid text-white text-right">
                            <div class="row text-white">
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 text-white">
                                    <ul>
                                        <li>
                                            <a href="https://www.tvtc.gov.sa/" class="text-white ">الرئيسية</a>
                                        </li>
                                        <li>
                                            <a href="http://office365.tvtc.gov.sa" class="text-white">منصة أوفيس 365</a>
                                        </li>

                                        <li>
                                            <a href="https://www.my.gov.sa/" class="text-white">منصة الخدمات الحكومية</a>
                                        </li>

                                        <li>
                                            <a href="https://mail.tvtc.gov.sa/owa/auth/logon.aspx?replaceCurrent=1&url=https%3a%2f%2fmail.tvtc.gov.sa%2fowa%2f" class="text-white">بريد الموظف</a>
                                        </li>

                                        <li>
                                            <a href="https://www.yammer.com/mttvtcedu.onmicrosoft.com/" class="text-white">قناة التواصل الداخلي (Yammer)</a>
                                        </li>

                                        <li>
                                            <a href="https://nartqi.tvtc.gov.sa/ar/ticket" class="text-white">اتصل بنا</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-xl-9 col-md-6 col-sm-12">
                                    <ul>
                                        <li>
                                            <a href="https://www.tvtc.gov.sa/journal/journal-main.html" class="text-white">المجلة السعودية للتدريب التقني والمهني</a>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            <a href="https://sportal.tvtc.gov.sa/f5-w-687474703a2f2f70726f6d6f2e747674632e676f762e7361$$/promo/leader/" class="text-white">
                                                ترشيح "منصة استكشاف القيادات والمتميزين من منسوبي المؤسسة
                                            </a>

                                        </li>
                                    </ul>
                                    <ul class="justify-content-end">
                                        <li>
                                            <a href="facility-protection.html" class="text-white">
                                                حماية المرافق - البرامج الوقائية والرقابية
                                            </a>

                                        </li>

                                        <li>
                                            <a href="pdf/TVTC-at-a-Glance.pdf" class="text-white">About us</a>
                                        </li>
                                    </ul>
                                </div>


                            </div>
                        </div>

                        <p class="footer-copy-rights text-center">
                            © جميع الحقوق محفوظة - المؤسسة العامة للتدريب التقني والمهني 2020 ( TVTC )
                        </p>
                    </div>
                </div>
        </footer>
    </div>



    <script>
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
    </script>
</body>


</html>