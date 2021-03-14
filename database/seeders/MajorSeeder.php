<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{


  // static $baccDepts = [
  //   "برمجيات",
  //   "دعم نظم الشبكات",
  //   "قوى وآلات كهربائية",
  //   "ميكانيكا سيارات",
  //    "الإدارة العامة ",
  //    "محاسبة",
  //   ];


  // static $diplomDepts = [
  //   "برمجيات",
  //   "تقنية الشبكات",
  //   "دعم فني",
  //   "قوى كهربائية",
  //   "ميكانيكا سيارات - مرنة",
  //   "إنتاج",
  //   "تبريد و تكييف",
  // "إدارة مكتبية",
  //  "تسويق",
  //  "محاسبة",
  //  "الإلكترونيات الصناعية والتحكم",
  //  "تقنية الأجهزة الطبية",


  // ];


  static $baccDepts = [
    "الحاسب وتقنية المعلومات"  =>  [
      [
        'name' => "برمجات",
        'hours' => 16
      ],
      [
        'name' => "دعم أنظمة شبكات",
        'hours' => 16
      ],
    ],

    "تقنية كهربائية"  =>  [
      [
        'name' => "قوى وآلات كهربائية",
        'hours' => 17
      ],
    ],
  
    "تقنية ميكانيكية"  =>  [
      [
        'name' => "ميكانيكا سيارات",
        'hours' => 18
      ],
      [
        'name' =>   "محركات ومركبات",
        'hours' => 18
      ],
    ],

    "تقنية ادارية"  =>  [
      [
        'name' => "ادارة عامة",
        'hours' => 16
      ],
      [
        'name' => "محاسبة",
        'hours' => 16
      ],
    ]
  ];



  static $diplomDepts = [
    "الحاسب وتقنية المعلومات"  =>  [
      [
        'name' => "برمجات",
        'hours' => 16
      ],
      [
        'name' => "دعم أنظمة شبكات",
        'hours' => 16
      ],
      [
        'name' => "دعم فني",
        'hours' => 16
      ],
    ],


    "تقنية كهربائية"  =>  [
      [
        'name' => "قوى كهربائية",
        'hours' => 19
      ],
    ],


    "تقنية ادارية"  =>  [
      [
        'name' => "إدارة مكتبية",
        'hours' => 16
      ],
      [
        'name' => "تسويق",
        'hours' => 16
      ],
      [
        'name' => "محاسبة",
        'hours' => 16
      ],
    ],


    "التقنية الالكترونية"  =>  [
      [
        'name' => "صناعية وتحكم",
        'hours' => 20
      ],
      [
        'name' => "أجهزة طبية",
        'hours' => 20
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
    $id = 1;
    foreach ($this::$baccDepts as $department) {
      foreach ($department as $major) {
        Major::create([
          'name'          => $major['name'],
          'hours'          => $major['hours'],
          'department_id' => $id,

        ]);
      }

      $id++;
    }
    
    foreach ($this::$diplomDepts as $department) {
      foreach ($department as $major) {
        Major::create([
          'name'          => $major['name'],
          'hours'          => $major['hours'],
          'department_id' => $id,

        ]);
      }

      $id++;
    }
  }
}
