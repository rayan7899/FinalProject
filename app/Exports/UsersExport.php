<?php

namespace App\Exports;

use App\Models\Semester;
use App\Models\User;
use Illuminate\View\View as View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class UsersExport implements FromView, ShouldAutoSize
{
    use Exportable;
    public function view():View
    {
        return view('excel.exportAllStudent', [
            'users' => User::whereHas("student")->get(),
        ]);
    }
}

// class UsersExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithEvents
// {
//     /**
//     * @return \Illuminate\Support\Collection
//     */
//     public function collection()
//     {
//         //returns Data with User data, all user data, not restricted to start/end dates
//         return User::whereHas("student")->get();
//     }
 
//     public function registerEvents(): array
//     {
//         return [
            
//             AfterSheet::class    => function(AfterSheet $event) {
//                 $styleArray = [
//                     'font' => [
//                         'bold' => true,
//                     ],
//                     'alignment' => [
//                         'horizontal' => Alignment::HORIZONTAL_CENTER,
//                     ],
//                     'borders' => [
//                         'top' => [
//                             'borderStyle' => Border::BORDER_THIN,
//                         ],
//                     ],
//                     'fill' => [
//                         'fillType' => Fill::FILL_GRADIENT_LINEAR,
//                         'rotation' => 90,
//                         'startColor' => [
//                             'argb' => 'FFA0A0A0',
//                         ],
//                         'endColor' => [
//                             'argb' => 'FFFFFFFF',
//                         ],
//                     ],
//                 ];
//                 $event->getDelegate()->getStyle('A1:K1')->applyFromArray($styleArray);              

//             },
//         ];
//     }
//     public function map($user) : array {
//         return [
//             $user->id,
//             $user->national_id,
//             $user->student->rayat_id,
//             $user->name,
//             $user->phone,
//             $user->student->program->name,
//             $user->student->department->name,
//             $user->student->major->name,
//             $user->student->traineeState,
//             $user->student->available_hours,
//             $user->student->wallet,
//         ] ;
 
 
//     }
 
//     public function headings() : array {
//         return [
//             'المعرف',
//             'رقم الهوية',
//             'الرقم التدريبي',
//             'الاسم',
//             'رقم الجوال',
//             'البرنامج',
//             'القسم',
//             'التخصص',
//             'الحالة',
//             'الساعات المعتمدة',
//             'رصيد المحفظة',
//         ] ;
//     }
// }
 