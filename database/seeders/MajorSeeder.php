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
    "تقنية الحاسب الالي"  =>  [
      [
        'name' => "برمجيات",
        'cost' => 8800
      ],
      [
        'name' => "دعم نظم الشبكات",
        'cost' => 8800
      ],
    ],

    "تقنية كهربائية"  =>  [
      [
        'name' => "قوى والات كهربائية",
        'cost' => 9350
      ],
    ],

    "تقنية ميكانيكية"  =>  [
      [
        'name' => "ميكانيكا سيارات",
        'cost' => 9900
      ],
    ],

    "تقنية ادارية"  =>  [
      [
        'name' => "الإدارة العامة",
        'cost' => 8800
      ],
      [
        'name' => "محاسبة",
        'cost' => 8800
      ],
    ]
  ];



  static $diplomDepts = [
    "تقنية الحاسب الالي"  =>  [
      [
        'name' => "برمجيات",
        'cost' => 8800
      ],
      [
        'name' => "دعم نظم الشبكات",
        'cost' => 8800
      ],
      [
        'name' => "دعم فني",
        'cost' => 8800
      ],
    ],

    "تقنية كهربائية"  =>  [
      [
        'name' => "قوى كهربائية",
        'cost' => 9350
      ],
    ],

    "تقنية ميكانيكية"  =>  [
      [
        'name' => "ميكانيكا سيارات - مرنة",
        'cost' => 9900
      ],
      [
        'name' => "إنتاج",
        'cost' => 9900
      ],
      [
        'name' => "تبريد و تكييف",
        'cost' => 9900
      ],
    ],

    "تقنية ادارية"  =>  [
      [
        'name' => "إدارة مكتبية",
        'cost' => 8800
      ],
      [
        'name' => "تسويق",
        'cost' => 8800
      ],
      [
        'name' => "محاسبة",
        'cost' => 8800
      ],
    ],

    "التقنية الالكترونية"  =>  [
      [
        'name' => "الإلكترونيات الصناعية والتحكم",
        'cost' => 8800
      ],
      [
        'name' => "تقنية الأجهزة الطبية",
        'cost' => 8800
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
          'cost'          => $major['cost'],
          'department_id' => $id,

        ]);
      }

      $id++;
    }
    
    foreach ($this::$diplomDepts as $department) {
      foreach ($department as $major) {
        Major::create([
          'name'          => $major['name'],
          'cost'          => $major['cost'],
          'department_id' => $id,

        ]);
      }

      $id++;
    }
  }
}
