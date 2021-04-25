<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    // static $majors = [
    //     "برمجات" => [
    //         [
    //             'name' => "Mathematics -1",
    //             'code' => "MATH 301",
    //             'hours' => 3
    //         ],
    //         [
    //             'name' => "Physics",
    //             'code' => "PHYS 301",
    //             'hours' => 3
    //         ],
    //         [
    //             'name' => "English Language -1",
    //             'code' => "ENGL 301",
    //             'hours' => 3
    //         ],
    //         [
    //             'name' => "Principles Of Accounting",
    //             'code' => "UACC 301",
    //             'hours' => 3
    //         ],
    //         [
    //             'name' => "Advanced computer Programming",
    //             'code' => "IPRG 313",
    //             'hours' => 4
    //         ],
    //     ],
    //     'دعم فني' => [
    //         [
    //             'name' => "Mathematics -1",
    //             'code' => "MATH 301",
    //             'hours' => 3
    //         ],
    //         [
    //             'name' => "Physics",
    //             'code' => "PHYS 301",
    //             'hours' => 3
    //         ],
    //         [
    //             'name' => "English Language -1",
    //             'code' => "ENGL 301",
    //             'hours' => 3
    //         ],
    //         [
    //             'name' => "Principles Of Accounting",
    //             'code' => "UACC 301",
    //             'hours' => 3
    //         ],
    //         [
    //             'name' => "Advanced computer Programming",
    //             'code' => "IPRG 313",
    //             'hours' => 4
    //         ],
    //     ]
    // ];


    static $baccDepts = [
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
                        'name' => "Elective Courses -3",
                        'code' => "لا يوجد",
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
                ]
            ],
        ],

        // "تقنية كهربائية"  =>  [
        //     [
        //         'name' => "قوى وآلات كهربائية",
        //         'hours' => 17
        //     ],
        // ],

        // "تقنية ميكانيكية"  =>  [
        //     [
        //         'name' => "ميكانيكا سيارات",
        //         'hours' => 18
        //     ],
        //     [
        //         'name' =>   "محركات ومركبات",
        //         'hours' => 18
        //     ],
        // ],

        // "تقنية ادارية"  =>  [
        //     [
        //         'name' => "ادارة عامة",
        //         'hours' => 16
        //     ],
        //     [
        //         'name' => "محاسبة",
        //         'hours' => 16
        //     ],
        // ]
    ];



    static $diplomDepts = [
        "الحاسب وتقنية المعلومات"  =>  [
            [

                ///  level 1
                ///////////////////////
                'name' => "برمجات",
                'courses' => [
                    [
                        'name' => "الدراسات الإسلامية",
                        'code' => "101 سلم",
                        'level' => 1,
                        'credit_hours' => 2,
                        'contact_hours' => 2

                    ],
                    [
                        'name' => "لغة إنجليزية (1)",
                        'code' => "101 نجل",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "الرياضيات",
                        'code' => "101 ريض",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "الفيزياء",
                        'code' => "101 فيزي",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 5
                    ],
                    [
                        'name' => "التوجيه المهني والتميز",
                        'code' => "101 مهني",
                        'level' => 1,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "تجميع الحاسب وتشغيله",
                        'code' => "121 حاسب",
                        'level' => 1,
                        'credit_hours' => 4,
                        'contact_hours' => 8
                    ],
                    [
                        'name' => "الخوارزميات والمنطق",
                        'code' => "101 برمج",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],







                      ///  level 2
                      ///////////////////////
                    [
                        'name' => "الكتابة الفنية",
                        'code' => "101 عرب",
                        'level' => 2,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "لغة إنجليزية (2)",
                        'code' => "102 نجل",
                        'level' => 2,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ], 
                      
                    [
                        'name' => "مقدمة تطبيقات الحاسب",
                        'code' => "101 حال",
                        'level' => 2,
                        'credit_hours' => 2,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "أساسيات برمجة الحاسب",
                        'code' => "111 برمج",
                        'level' => 2,
                        'credit_hours' => 4,
                        'contact_hours' => 6
                    ],
                    [
                        'name' => "مبادئ برمجة صفحات الانترنت",
                        'code' => "131 برمج",
                        'level' => 2,
                        'credit_hours' => 4,
                        'contact_hours' => 6
                    ],
                    [
                        'name' => "مبادئ قواعد البيانات",
                        'code' => "121 برمج",
                        'level' => 2,
                        'credit_hours' => 4,
                        'contact_hours' => 6
                    ],






                      ///  level 3
                      ///////////////////////
                    [
                        'name' => "لغة إنجليزية (3)",
                        'code' => "103 نجل",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "تطبيقات الحاسب المتقدمة",
                        'code' => "102 حال",
                        'level' => 3,
                        'credit_hours' => 2,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "برمجة الحاسب",
                        'code' => "212 برمج",
                        'level' => 3,
                        'credit_hours' => 4,
                        'contact_hours' => 6
                    ],
                    [
                        'name' => "برمجة الانترنت",
                        'code' => "232 برمج",
                        'level' => 3,
                        'credit_hours' => 4,
                        'contact_hours' => 6
                    ],
                    [
                        'name' => "برمجة قواعد البيانات",
                        'code' => "222 برمج",
                        'level' => 3,
                        'credit_hours' => 4,
                        'contact_hours' => 6
                    ],
                    [
                        'name' => "هندسة البرمجيات",
                        'code' => "241 برمج",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],







                      ///  level 4
                      ///////////////////////
                    [
                        'name' => "مهارات التعلم",
                        'code' => "101 ماهر",
                        'level' => 4,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "لغة إنجليزية (4)",
                        'code' => "104 انجل",
                        'level' => 4,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "السلوي الوظيفي ومهارات الاتصال",
                        'code' => "101 اسلك",
                        'level' => 4,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "تقنيات الانترنت المتقدمة",
                        'code' => "234 برمج",
                        'level' => 4,
                        'credit_hours' => 4,
                        'contact_hours' => 6
                    ],
                    [
                        'name' => "برمجة الأجهزة الذكية",
                        'code' => "251 برمج",
                        'level' => 4,
                        'credit_hours' => 4,
                        'contact_hours' => 6
                    ],
                    [
                        'name' => "التأهيل للشهادات الاحترافية",
                        'code' => "280 حاسب",
                        'level' => 4,
                        'credit_hours' => 1,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "مشروع",
                        'code' => "295 برمج",
                        'level' => 4,
                        'credit_hours' => 4,
                        'contact_hours' => 6
                    ],







                      ///  level 5
                      ///////////////////////
                    [
                        'name' => "التدريب التعاوني",
                        'code' => "299 برمج",
                        'level' => 5,
                        'credit_hours' => 4,
                        'contact_hours' => 0
                    ],
                ]
            ],
            [
                'name' => "إدارة أنظمة الشبكات",
                'courses' => [
                    [
                        'name' => "التوجيه المهني والتميز",
                        'code' => "101 مهن",
                        'level' => 1,
                        'credit_hours' => 2,
                        'contact_hours' => 2

                    ],
                    [
                        'name' => "لغة إنجليزية (1)",
                        'code' => "101 نجل",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "الرياضيات",
                        'code' => "101 ريض",
                        'level' => 1,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                    [
                        'name' => "مقدمة تطبيقات الحاسب",
                        'code' => "101 حال",
                        'level' => 1,
                        'credit_hours' => 2,
                        'contact_hours' => 4
                    ],





                    [
                        'name' => "الدراسات الإسلامية",
                        'code' => "101 سلم",
                        'level' => 1,
                        'credit_hours' => 2,
                        'contact_hours' => 2

                    ],
                    [
                        'name' => "لغة إنجليزية (2)",
                        'code' => "102 نجل",
                        'level' => 2,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ], 
                    [
                        'name' => "تطبيقات الحاسب المتقدمة",
                        'code' => "102 حال",
                        'level' => 2,
                        'credit_hours' => 2,
                        'contact_hours' => 4
                    ],
                    
                    
                    
                    
                    
                    [
                        'name' => "الكتابة الفنية",
                        'code' => "101 عرب",
                        'level' => 2,
                        'credit_hours' => 2,
                        'contact_hours' => 2
                    ],
                    [
                        'name' => "لغة إنجليزية (3)",
                        'code' => "103 نجل",
                        'level' => 3,
                        'credit_hours' => 3,
                        'contact_hours' => 4
                    ],
                ]
            ],
    //         [
    //             'name' => "دعم فني",
    //             'hours' => 16
    //         ],
        ],


    //     "تقنية كهربائية"  =>  [
    //         [
    //             'name' => "قوى كهربائية",
    //             'hours' => 19
    //         ],
    //     ],


    //     "تقنية ادارية"  =>  [
    //         [
    //             'name' => "إدارة مكتبية",
    //             'hours' => 16
    //         ],
    //         [
    //             'name' => "تسويق",
    //             'hours' => 16
    //         ],
    //         [
    //             'name' => "محاسبة",
    //             'hours' => 16
    //         ],
    //     ],


    //     "التقنية الالكترونية"  =>  [
    //         [
    //             'name' => "صناعية وتحكم",
    //             'hours' => 20
    //         ],
    //         [
    //             'name' => "أجهزة طبية",
    //             'hours' => 20
    //         ],
    //     ]
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
