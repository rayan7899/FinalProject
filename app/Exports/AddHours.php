<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AddHours implements FromArray, ShouldAutoSize, WithHeadings, WithTitle, WithEvents
{
    public $addInfo;
    public function __construct($addInfo)
    {
        $this->addInfo = $addInfo;
    }

    public function array(): array
    {
        $r = array_map(function ($addInfo) {
            return [
                $addInfo['national_id'],
                $addInfo['name'],
                __($addInfo['traineeState']),
                $addInfo['hours'],
                $addInfo['amount'],
                $addInfo['creditHours'],
            ];
        }, $this->addInfo);

        return $r;
    }

    public function headings(): array
    {
        return [
            'رقم الهوية',
            'الاسم',
            'الحالة',
            'الساعات المضافة ',
            'المبلغ المخصوم',
            'الساعات المعتمدة',
        ];
    }

    public function title(): string
    {
        return 'خصم';
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class    => function (AfterSheet $event) {
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => [
                            'argb' => 'c4eaff',
                        ],
                    ],
                ];
                $event->getDelegate()->getStyle('A1:F1')->applyFromArray($styleArray);
                $styleArray['fill']['color']['argb'] = 'ffffff';
                $styleArray['font']['bold'] = false;

                for ($i = 2; $i <= count($this->addInfo)+2; $i++) {
                    $event->getDelegate()->getStyle('A' . $i . ':F' . $i)->applyFromArray($styleArray);
                }
            },
        ];
    }
}
