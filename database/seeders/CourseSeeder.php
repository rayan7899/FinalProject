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
                        'code' => "9154 حاب",
                        'level' => 3,
                        'credit_hours' => 4,
                        'contact_hours' => 6
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
