<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{

     static $departments = [

    "mechanical"  =>  [
        "الآلات الزراعية",
        "إنتاج",
        "اللحام",
        "أنظمة هيدروليكية ونيوماتية",
        "الصيانة الميكانيكية",
        "تبريد وتكييف",
        "كهرباء السيارات",
        "محركات ومركبات",
        "معدات ثقيلة"
    ],

       "management"  =>  [
        "إدارة المستودعات",
        "تسويق",
        "محاسبة",
        "إدارة مكتبية"
       ],

        "electronic"  =>  [
        "الكترونيات صناعية",
        "الأجهزة الطبية"
        ],

        "electric"  =>  [
            "آلات ومعدات كهربائية",
            "قوى كهربائية",
            "مشغل لوحة تحكم"
        ],

        "chemical"  =>  [
            "إنتاج كيميائي",
            "مختبرات كيميائية"
        ],

        "ArchitecturalEng"  =>  [
            "عمارة",
            "مدني",
            "مساحة"
        ],
        "hotel"  =>  [
            "فندقة",
            "سفر وسياحة",
            "انتاج الطعام (الطهي)",
            "خدمة الطعام (الضيافة)",
        ],
        "foodProduction"  =>  [
             "انتاج الدواجن"
        ],
        "Communication"  =>  [
            "تقنية الاتصالات"
    ],
        "Environmental"  =>  [
            "حماية البيئة",
            "سلامة الأغذية",

        ],
        "Computer"  =>  [
            "برمجيات",
            "إدارة أنظمة الشبكات",
            "دعم فني",
            "تقنية شبكات الحاسب",
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
        foreach($this::$departments as $department ){
                foreach($department as $major){
                    Major::create([
                        'name'          => $major,
                        'department_id' => $id,

                    ]);
                }

             $id++;
        }
          
    }
}
