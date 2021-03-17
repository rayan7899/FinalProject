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
                'hours' => 16,
                'courses' => [
                    [
                        'name' => "Mathematics -1",
                        'code' => "MATH 301",
                        'hours' => 3
                    ],
                    [
                        'name' => "Physics",
                        'code' => "PHYS 301",
                        'hours' => 3
                    ],
                    [
                        'name' => "English Language -1",
                        'code' => "ENGL 301",
                        'hours' => 3
                    ],
                    [
                        'name' => "Principles Of Accounting",
                        'code' => "UACC 301",
                        'hours' => 3
                    ],
                    [
                        'name' => "Advanced computer Programming",
                        'code' => "IPRG 313",
                        'hours' => 4
                    ],
                ]
            ],
            // [
            //     'name' => "دعم أنظمة شبكات",
            //     'hours' => 16
            // ],
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



    // static $diplomDepts = [
    //     "الحاسب وتقنية المعلومات"  =>  [
    //         [
    //             'name' => "برمجات",
    //             'hours' => 16
    //         ],
    //         [
    //             'name' => "دعم أنظمة شبكات",
    //             'hours' => 16
    //         ],
    //         [
    //             'name' => "دعم فني",
    //             'hours' => 16
    //         ],
    //     ],


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
    // ];

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
                        'hours'    => $course['hours'],
                        'major_id' => $id,
                    ]);
                }
            }

            $id++;
        }

        // foreach ($this::$diplomDepts as $department) {
        //     foreach ($department as $major) {
        //         foreach ($major as $course) {
        //             Course::create([
        //                 'name'     => $course['name'],
        //                 'code'     => $course['code'],
        //                 'hours'    => $course['hours'],
        //                 'major_id' => $id,
        //             ]);
        //         }
        //     }

        //     $id++;
        // }
    }
}
