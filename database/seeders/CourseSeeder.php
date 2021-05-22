<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{


    static $baccDepts = [

        /*
        "الحاسب وتقنية المعلومات"  =>  [
            [

                ///  level 1
                ///////////////////////
                'name' => "برمجات",
                'courses' => [
                    [
                        'name' => "Mathematics -1",
                        'code' => "MATH 301",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4

                    ],
                    [
                        'name' => "Physics",
                        'code' => "PHYS 301",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "English Language -1",
                        'code' => "ENGL 301",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "Principles Of Accounting",
                        'code' => "UACC 301",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 5
                    ],
                    [
                        'name' => "Advanced computer Programming",
                        'code' => "IPRG 313",
                        'level' => 1,
                        'credit_hours' => 4,
                        'contact_hours' => 5
                    ], 



                      ///  level 2
                      ///////////////////////
                    [
                        'name' => "Statistics and Probability",
                        'code' => "STAT 303",
                        'level' => 2,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "Discrete Math",
                        'code' => "MATH 303",
                        'level' => 2,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "Advanced Web Programming",
                        'code' => "IPRG 335",
                        'level' => 2,
                        'credit_hours' => 4,
                        'contact_hours' => 5
                    ],
                    [
                        'name' => "IT Terminologies and Technical Writing",
                        'code' => "IPRG 382",
                        'level' => 2,
                        'credit_hours' => 2,
                        'contact_hours' => 3
                    ],
                    [
                        'name' => "Algorithms Design & Data Structure",
                        'code' => "IPRG 325",
                        'level' => 2,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                      



                      ///  level 3
                      ///////////////////////
  
                    [
                        'name' => "English Language -2",
                        'code' => "ENGL 302",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "Network Technologies -1",
                        'code' => "INSA 351",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "Multimedia Systems Development",
                        'code' => "IPRG 473",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "Advanced Software Engineering",
                        'code' => "IPRG 443",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 3
                    ],
                    [
                        'name' => "Embedded system programming",
                        'code' => "IPRG 472",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],








                      ///  level 4
                      ///////////////////////
                    [
                        'name' => "Database Management Systems",
                        'code' => "IPRG 324",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "Advanced Smart Devices Programming -1",
                        'code' => "IPRG 453",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "User Interface Design",
                        'code' => "IPRG 461",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "Enterprise Resources Planning Systems - ERP",
                        'code' => "IPRG 478",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "Elective Courses -1",
                        'code' => "لا يوجد",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],










                      ///  level 5
                      ///////////////////////
                    [
                        'name' => "Advanced Smart Devices Programming -2",
                        'code' => "IPRG 454",
                        'level' => 5,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "Software security",
                        'code' => "IPRG 474",
                        'level' => 5,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "Graduation Project",
                        'code' => "IPRG 492",
                        'level' => 5,
                        'credit_hours' => 4,
                        'contact_hours' => 6
                    ],
                    [
                        'name' => "Elective Courses -2",
                        'code' => "لا يوجد",
                        'level' => 5,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "انترنت الاشياء",
                        'code' => "نشبك 485",
                        'level' => 5,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                ]
            ],


            [
                'name' => "دعم أنظمة شبكات",
               'courses' => [
                    [
                        'name' => "Mathematics -1",
                        'code' => "MATH 301",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "Physics",
                        'code' => "PHYS 301",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "English Language -1",
                        'code' => "ENGL 301",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "Basic Networks Systems Administration",
                        'code' => "INSA 312",
                        'level' => 1,
                        'credit_hours' => 4,
                        'contact_hours' => 6
                    ],
                    [
                        'name' => "Network Technologies -1",
                        'code' => "INSA 351",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "Problems Solving Strategies",
                        'code' => "INSA 343",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],


                    [
                        'name' => "Discrete Math",
                        'code' => "MATH 303",
                        'level' => 2,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "Introduction to Management and Leadership",
                        'code' => "GNRL 401",
                        'level' => 2,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "Advanced Network Administration",
                        'code' => "INSA 371",
                        'level' => 2,
                        'credit_hours' => 4,
                        'contact_hours' => 6
                    ],


                    [
                        'name' => "Statistics and Probability",
                        'code' => "STAT 303",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "English Language -2",
                        'code' => "ENGL 302",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "Data Center Operation -1",
                        'code' => "INSA 453",
                        'level' => 3,
                        'credit_hours' => 4,
                        'contact_hours' => 6
                    ],
                    [
                        'name' => "اخلاقيات العمل في تقنية المعلومات",
                        'code' => "نشبك 482",
                        'level' => 3,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],


                    [
                        'name' => "تصميم و تحليل الشبكات",
                        'code' => "نشبك 443",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],


                    
                    [
                        'name' => "افضل ممارسات البنية التحتية",
                        'code' => "نشبك484",
                        'level' => 5,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "الامن السبراني",
                        'code' => "نشبك434",
                        'level' => 5,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "انترنت الاشياء",
                        'code' => "نشبك 485",
                        'level' => 5,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                ]
            ],
        ],
        */
        "الحاسب وتقنية المعلومات"  =>  [
            [
                'name' => "برمجات",
                'courses' => [
                    [
                        'name' => "برمجةالاجهزةالذكية 2",
                        'code' => "برمج454",
                        'level' => 5,
                        'credit_hours' => 3,
                        'contact_hours' => 4

                    ],
                    [
                        'name' => "مشروع تخرج(برمجيات) ",
                        'code' => "برمج492",
                        'level' => 5,
                        'credit_hours' => 4,
                        'contact_hours' => 6
                    ],
                    [
                        'name' => "انترنت الاشياء",
                        'code' => "نشبك485",
                        'level' => 5,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "هندسةالبرمجيات المتقدمة",
                        'code' => "برمج443",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 3
                    ],
                    [
                        'name' => "اللغة الأنجليزية العامة",
                        'code' => "انجل302",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],

                    [
                        'name' => "فيزياء",
                        'code' => "فيزي301",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "رياضيات متقطعة",
                        'code' => "رياض303",
                        'level' => 2,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "الاحصاء والاحتمالات",
                        'code' => "احصا303",
                        'level' => 2,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],

                ]
            ],
            [
                'name' => "دعم أنظمة شبكات",
                'courses' => [
                    [
                        'name' => "اخلاقيات العمل في تقنية المعلومات",
                        'code' => "نشبك482",
                        'level' => 3,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],


                    [
                        'name' => "تصميم و تحليل الشبكات",
                        'code' => "نشبك443",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],



                    [
                        'name' => "افضل ممارسات البنية التحتية",
                        'code' => "نشبك484",
                        'level' => 5,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "الامن السبراني",
                        'code' => "نشبك434",
                        'level' => 5,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "انترنت الاشياء",
                        'code' => "نشبك485",
                        'level' => 5,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "تقنيات شبكات 1",
                        'code' => "نشبك351",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "الاحصاء والاحتمالات",
                        'code' => "احصا303",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "رياضيات متقطعة",
                        'code' => "رياض303",
                        'level' => 2,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "مقدمة في الادارة والقيادة",
                        'code' => "عامة401",
                        'level' => 2,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "ادارة المشاريع الهندسية",
                        'code' => "عامة402",
                        'level' => 5,
                        'credit_hours' => 3,
                        'contact_hours' => 3
                    ],
                    [
                        'name' => "فيزياء",
                        'code' => "فيزي301",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "اللغة الأنجليزية العامة",
                        'code' => "انجل302",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                ]
            ],
        ],

        "التقنية الكهربائية"  =>  [
            [
                'name' => "قوى وآلات كهربائية",
                'courses' => [
                    [
                        'name' => "التحكم الآلي",
                        'code' => "كهرب442",
                        'level' => 4,
                        'credit_hours' => 2,
                        'contact_hours' => 5
                    ],
                    [
                        'name' => "حماية نظم",
                        'code' => "كهرب462",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 5
                    ],
                    [
                        'name' => "تسيير كهربائي",
                        'code' => "كهرب444",
                        'level' => 4,
                        'credit_hours' => 4,
                        'contact_hours' => 6
                    ],
                    [
                        'name' => "مشروع التخرج",
                        'code' => "كهرب491",
                        'level' => 5,
                        'credit_hours' => 4,
                        'contact_hours' => 6
                    ],
                    [
                        'name' => "التحويل الكهروميكانيكي للطاقة",
                        'code' => "كهرب334",
                        'level' => 2,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "الطاقة المتجددة",
                        'code' => "كهرب471",
                        'level' => 2,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "نظم التوزيع الكهربائي",
                        'code' => "كهرب361",
                        'level' => 2,
                        'credit_hours' => 2,
                        'contact_hours' => 3
                    ],
                    [
                        'name' => "الجر الكهربائي",
                        'code' => "كهرب445",
                        'level' => 5,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "التحكم في نظم القوى الكهربائية",
                        'code' => "كهرب443",
                        'level' => 5,
                        'credit_hours' => 2,
                        'contact_hours' => 3
                    ],
                    [
                        'name' => "التشغيل الاقتصادي لنظم القوى",
                        'code' => "كهرب463",
                        'level' => 4,
                        'credit_hours' => 2,
                        'contact_hours' => 3
                    ],
                    [
                        'name' => "ادارة المشاريع الهندسية",
                        'code' => "عامة402",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 3
                    ],
                    [
                        'name' => "فيزياء",
                        'code' => "فيزي301",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "اللغة الأنجليزية العامة",
                        'code' => "انجل302",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "الاحصاء والاحتمالات",
                        'code' => "احصا303",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "رياضيات 2",
                        'code' => "رياض302",
                        'level' => 2,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                ]
            ],
        ],

        "تقنية ميكانيكيا السيارات"  =>  [
            [
                'name' => "ميكانيكا سيارات",
                'courses' => [
                    [
                        'name' => "الطاقة المتجددة",
                        'code' => "متمر461",
                        'level' => 4,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "الشاحن التربيني",
                        'code' => "متمر473",
                        'level' => 5,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "تقنية الوقود البديلة",
                        'code' => "متمر431",
                        'level' => 5,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "ادارة ورش السيارات",
                        'code' => "متمر483",
                        'level' => 2,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "تطبيقات الموائع",
                        'code' => "متمر443",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "تصميم اجزاء السياراة",
                        'code' => "متمر434",
                        'level' => 4,
                        'credit_hours' => 2,
                        'contact_hours' => 5
                    ],
                    [
                        'name' => "الاحصاء والاحتمالات",
                        'code' => "احصا303",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "رياضيات 2",
                        'code' => "رياض302",
                        'level' => 2,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "ادارة المشاريع الهندسية",
                        'code' => "عامة402",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 3
                    ],
                    [
                        'name' => "فيزياء",
                        'code' => "فيزي301",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "اللغة الأنجليزية العامة",
                        'code' => "انجل302",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                ]
            ],
            [
                'name' =>   "محركات ومركبات",
                'courses' => [
                    // [
                    //     'name' => "",
                    //     'code' => "",
                    //     'level' => ,
                    //     'credit_hours' => 3,
                    //     'contact_hours' => 
                    // ],
                ]
            ],
        ],


        "التقنيةالإدارية"  =>  [
            [
                'name' => "ادارة عامة",
                'courses' => [
                    [
                        'name' => "القيادةالادارية",
                        'code' => "ادار435",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 5
                    ],
                    [
                        'name' => "تطوير المنظمات",
                        'code' => "ادار464",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "ريادة الاعمال",
                        'code' => "ادار473",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 5
                    ],
                    [
                        'name' => "تمويل الشركات",
                        'code' => "ادار462",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "الادارة الاستراتيجية",
                        'code' => "ادار445",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 5
                    ],
                    [
                        'name' => "العلاقات العامة",
                        'code' => "ادار439",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 5
                    ],
                    [
                        'name' => "مشروع التخرج",
                        'code' => "ادار492",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 5
                    ],
                    [
                        'name' => "اللغة الأنجليزية العامة",
                        'code' => "انجل302",
                        'level' => 2,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                ]
            ],
            [
                'name' => "محاسبة",
                'courses' => [
                    [
                        'name' => "رقابة ومراجعة داخلية",
                        'code' => "محسب463",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 5
                    ],
                    [
                        'name' => "مشروع تخرج",
                        'code' => "محسب491",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 5
                    ],
                    [
                        'name' => "نظم معلومات محاسبية",
                        'code' => "محسب 424",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 5
                    ],
                    [
                        'name' => "اللغة الأنجليزية العامة",
                        'code' => "انجل302",
                        'level' => 2,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                ]
            ],

        ],
    ];


    // [
    //     'name' => "مهارات التعلم",
    //     'code' => "ماهر101",
    //     'level' => 2,
    //     'credit_hours' => 2,
    //     'contact_hours' => 2
    // ],
    // [
    //     'name' => "الكتابة الفنية",
    //     'code' => "عربي101",
    //     'level' => 2,
    //     'credit_hours' => 2,
    //     'contact_hours' => 2
    // ],

    static $diplomDepts = [
        "الحاسب وتقنية المعلومات"  =>  [
            // [
            //     'name' => "برمجات",
            //     'courses' => [

            //         ///  level 1
            //         ///////////////////////
            //         [
            //             'name' => "الدراسات الإسلامية",
            //             'code' => "101 سلم",
            //             'level' => 1,
            //             'credit_hours' => 2,
            //             'contact_hours' => 2

            //         ],
            //         [
            //             'name' => "لغة إنجليزية (1)",
            //             'code' => "101 نجل",
            //             'level' => 1,
            //             'credit_hours' => 3,
            //             'contact_hours' => 4
            //         ],
            //         [
            //             'name' => "الرياضيات",
            //             'code' => "101 ريض",
            //             'level' => 1,
            //             'credit_hours' => 3,
            //             'contact_hours' => 4
            //         ],
            //         [
            //             'name' => "الفيزياء",
            //             'code' => "101 فيزي",
            //             'level' => 1,
            //             'credit_hours' => 3,
            //             'contact_hours' => 5
            //         ],
            //         [
            //             'name' => "التوجيه المهني والتميز",
            //             'code' => "101 مهني",
            //             'level' => 1,
            //             'credit_hours' => 2,
            //             'contact_hours' => 2
            //         ],
            //         [
            //             'name' => "تجميع الحاسب وتشغيله",
            //             'code' => "121 حاسب",
            //             'level' => 1,
            //             'credit_hours' => 4,
            //             'contact_hours' => 8
            //         ],
            //         [
            //             'name' => "الخوارزميات والمنطق",
            //             'code' => "101 برمج",
            //             'level' => 1,
            //             'credit_hours' => 3,
            //             'contact_hours' => 4
            //         ],







            //         ///  level 2
            //         ///////////////////////
            //         [
            //             'name' => "الكتابة الفنية",
            //             'code' => "101 عرب",
            //             'level' => 2,
            //             'credit_hours' => 2,
            //             'contact_hours' => 2
            //         ],
            //         [
            //             'name' => "لغة إنجليزية (2)",
            //             'code' => "102 نجل",
            //             'level' => 2,
            //             'credit_hours' => 3,
            //             'contact_hours' => 4
            //         ],

            //         [
            //             'name' => "مقدمة تطبيقات الحاسب",
            //             'code' => "101 حال",
            //             'level' => 2,
            //             'credit_hours' => 2,
            //             'contact_hours' => 4
            //         ],
            //         [
            //             'name' => "أساسيات برمجة الحاسب",
            //             'code' => "111 برمج",
            //             'level' => 2,
            //             'credit_hours' => 4,
            //             'contact_hours' => 6
            //         ],
            //         [
            //             'name' => "مبادئ برمجة صفحات الانترنت",
            //             'code' => "131 برمج",
            //             'level' => 2,
            //             'credit_hours' => 4,
            //             'contact_hours' => 6
            //         ],
            //         [
            //             'name' => "مبادئ قواعد البيانات",
            //             'code' => "121 برمج",
            //             'level' => 2,
            //             'credit_hours' => 4,
            //             'contact_hours' => 6
            //         ],






            //         ///  level 3
            //         ///////////////////////
            //         [
            //             'name' => "لغة إنجليزية (3)",
            //             'code' => "103 نجل",
            //             'level' => 3,
            //             'credit_hours' => 3,
            //             'contact_hours' => 4
            //         ],
            //         [
            //             'name' => "تطبيقات الحاسب المتقدمة",
            //             'code' => "102 حال",
            //             'level' => 3,
            //             'credit_hours' => 2,
            //             'contact_hours' => 4
            //         ],
            //         [
            //             'name' => "برمجة الحاسب",
            //             'code' => "212 برمج",
            //             'level' => 3,
            //             'credit_hours' => 4,
            //             'contact_hours' => 6
            //         ],
            //         [
            //             'name' => "برمجة الانترنت",
            //             'code' => "232 برمج",
            //             'level' => 3,
            //             'credit_hours' => 4,
            //             'contact_hours' => 6
            //         ],
            //         [
            //             'name' => "برمجة قواعد البيانات",
            //             'code' => "222 برمج",
            //             'level' => 3,
            //             'credit_hours' => 4,
            //             'contact_hours' => 6
            //         ],
            //         [
            //             'name' => "هندسة البرمجيات",
            //             'code' => "241 برمج",
            //             'level' => 3,
            //             'credit_hours' => 3,
            //             'contact_hours' => 4
            //         ],







            //         ///  level 4
            //         ///////////////////////
            //         [
            //             'name' => "مهارات التعلم",
            //             'code' => "101 ماهر",
            //             'level' => 4,
            //             'credit_hours' => 2,
            //             'contact_hours' => 2
            //         ],
            //         [
            //             'name' => "لغة إنجليزية (4)",
            //             'code' => "104 انجل",
            //             'level' => 4,
            //             'credit_hours' => 3,
            //             'contact_hours' => 4
            //         ],
            //         [
            //             'name' => "السلوي الوظيفي ومهارات الاتصال",
            //             'code' => "101 اسلك",
            //             'level' => 4,
            //             'credit_hours' => 2,
            //             'contact_hours' => 2
            //         ],
            //         [
            //             'name' => "تقنيات الانترنت المتقدمة",
            //             'code' => "234 برمج",
            //             'level' => 4,
            //             'credit_hours' => 4,
            //             'contact_hours' => 6
            //         ],
            //         [
            //             'name' => "برمجة الأجهزة الذكية",
            //             'code' => "251 برمج",
            //             'level' => 4,
            //             'credit_hours' => 4,
            //             'contact_hours' => 6
            //         ],
            //         [
            //             'name' => "التأهيل للشهادات الاحترافية",
            //             'code' => "280 حاسب",
            //             'level' => 4,
            //             'credit_hours' => 1,
            //             'contact_hours' => 2
            //         ],
            //         [
            //             'name' => "مشروع",
            //             'code' => "295 برمج",
            //             'level' => 4,
            //             'credit_hours' => 4,
            //             'contact_hours' => 6
            //         ],







            //         ///  level 5
            //         ///////////////////////
            //         [
            //             'name' => "التدريب التعاوني",
            //             'code' => "299 برمج",
            //             'level' => 5,
            //             'credit_hours' => 4,
            //             'contact_hours' => 0
            //         ],
            //     ]
            // ],
            // [
            //     'name' => "دعم أنظمة شبكات",
            //     'courses' => [
            //         [
            //             'name' => "التوجيه المهني والتميز",
            //             'code' => "101 مهن",
            //             'level' => 1,
            //             'credit_hours' => 2,
            //             'contact_hours' => 2

            //         ],
            //         [
            //             'name' => "لغة إنجليزية (1)",
            //             'code' => "101 نجل",
            //             'level' => 1,
            //             'credit_hours' => 3,
            //             'contact_hours' => 4
            //         ],
            //         [
            //             'name' => "الرياضيات",
            //             'code' => "101 ريض",
            //             'level' => 1,
            //             'credit_hours' => 3,
            //             'contact_hours' => 4
            //         ],
            //         [
            //             'name' => "مقدمة تطبيقات الحاسب",
            //             'code' => "101 حال",
            //             'level' => 1,
            //             'credit_hours' => 2,
            //             'contact_hours' => 4
            //         ],





            //         [
            //             'name' => "الدراسات الإسلامية",
            //             'code' => "101 سلم",
            //             'level' => 1,
            //             'credit_hours' => 2,
            //             'contact_hours' => 2

            //         ],
            //         [
            //             'name' => "لغة إنجليزية (2)",
            //             'code' => "102 نجل",
            //             'level' => 2,
            //             'credit_hours' => 3,
            //             'contact_hours' => 4
            //         ],
            //         [
            //             'name' => "تطبيقات الحاسب المتقدمة",
            //             'code' => "102 حال",
            //             'level' => 2,
            //             'credit_hours' => 2,
            //             'contact_hours' => 4
            //         ],





            //         [
            //             'name' => "الكتابة الفنية",
            //             'code' => "101 عرب",
            //             'level' => 2,
            //             'credit_hours' => 2,
            //             'contact_hours' => 2
            //         ],
            //         [
            //             'name' => "لغة إنجليزية (3)",
            //             'code' => "103 نجل",
            //             'level' => 3,
            //             'credit_hours' => 3,
            //             'contact_hours' => 4
            //         ],
            //     ]
            // ],
            [
                'name' => "دعم فني",
                'courses' => [
                    [
                        'name' => "مهارات التعلم",
                        'code' => "ماهر101",
                        'level' => 3,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "الكتابة الفنية",
                        'code' => "عربي101",
                        'level' => 1,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "اللغة الأنجليزية العامة",
                        'code' => "انجل103",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                ]
            ],
        ],


        "تقنية كهربائية"  =>  [
            [
                'name' => "قوى كهربائية",
                'courses' => [
                    [
                        'name' => "شبكات النقل الكهربائي",
                        'code' => "كهرق262",
                        'level' => 3,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "دوائر كهربائية-2",
                        'code' => "كهرب122",
                        'level' => 2,
                        'credit_hours' => 2,
                        'contact_hours' => 3
                    ],
                    [
                        'name' => "التحكم المبرمج",
                        'code' => "كهرب141",
                        'level' => 2,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "ورشة التركيبات الخاصة الوقاية",
                        'code' => "كهرق253",
                        'level' => 3,
                        'credit_hours' => 2,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "مشروع انتاجي-1",
                        'code' => "كهرق293",
                        'level' => 5,
                        'credit_hours' => 4,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "الكتابة الفنية",
                        'code' => "عربي101",
                        'level' => 2,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "مهارات التعلم",
                        'code' => "ماهر101",
                        'level' => 4,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "اللغة الأنجليزية العامة",
                        'code' => "انجل103",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                ]
            ],
        ],


        "تقنية ادارية"  =>  [
            [
                'name' => "إدارة مكتبية",
                'courses' => [

                    [
                        'name' => "موضوعات مختارة",
                        'code' => "ادار285",
                        'level' => 4,
                        'credit_hours' => 4,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "ادارة الجودة الشاملة",
                        'code' => "ادار271",
                        'level' => 4,
                        'credit_hours' => 6,
                        'contact_hours' => 6
                    ],
                    [
                        'name' => "مهارات التعلم",
                        'code' => "ماهر101",
                        'level' => 2,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],

                    /*   [
                       'name' => "الكتابة الفنية",
                       'code' => "عربي101",
                       'level' => 2,
                       'credit_hours' => 2,
                       'contact_hours' => 2
                   ],*/
                ]
            ],
            [
                'name' => "تسويق",
                'courses' => [

                    // [
                    //     'name' => "",
                    //     'code' => "",
                    //     'level' => ,
                    //     'credit_hours' => 3,
                    //     'contact_hours' => 
                    // ],
                ]
            ],
            [
                'name' => "محاسبة",
                'courses' => [
                    // [
                    //     'name' => "",
                    //     'code' => "",
                    //     'level' => ,
                    //     'credit_hours' => 3,
                    //     'contact_hours' => 
                    // ],
                ]
            ],
        ],


        "التقنية الالكترونية"  =>  [
            [
                'name' => "صناعية وتحكم",
                'courses' => [
                    // [
                    //     'name' => "",
                    //     'code' => "",
                    //     'level' => ,
                    //     'credit_hours' => 3,
                    //     'contact_hours' => 
                    // ],
                ]
            ],
            [
                'name' => "أجهزة طبية",
                'courses' => [
                    // [
                    //     'name' => "",
                    //     'code' => "",
                    //     'level' => ,
                    //     'credit_hours' => 3,
                    //     'contact_hours' => 
                    // ],
                ]
            ],
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $id = 1;
        // foreach ($this::$majors as $major) {
        //     foreach ($major as $course) {
        //         Course::create([
        //             'name'          => $course['name'],
        //             'code'          => $course['code'],
        //             'hours'         => $course['hours'],
        //             'major_id'      => $id,
        //         ]);
        //     }

        //     $id++;
        // }

        $id = 1;
        foreach ($this::$baccDepts as $department) {
            foreach ($department as $major) {
                foreach ($major['courses'] as $course) {
                    Course::create([
                        'name'     => $course['name'],
                        'code'     => $course['code'],
                        'level'    => $course['level'],
                        'suggested_level' => 0,
                        'credit_hours' => $course['credit_hours'],
                        'contact_hours' => $course['contact_hours'],
                        'major_id' => $id,
                    ]);
                }
                $id++;
            }
        }
        $id = 8;
        foreach ($this::$diplomDepts as $department) {
            foreach ($department as $major) {
                foreach ($major['courses'] as $course) {
                    Course::create([
                        'name'     => $course['name'],
                        'code'     => $course['code'],
                        'level'    => $course['level'],
                        'suggested_level' => 0,
                        'credit_hours' => $course['credit_hours'],
                        'contact_hours' => $course['contact_hours'],
                        'major_id' => $id,
                    ]);
                }
                $id++;
            }
        }
    }
}
