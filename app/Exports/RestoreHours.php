<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RestoreHours implements FromArray, ShouldAutoSize, WithHeadings, WithTitle, WithEvents
{
    public $restoreInfo;
    public function __construct($restoreInfo)
    {
        $this->restoreInfo = $restoreInfo;
    }

    public function array(): array
    {
        $r = array_map(function ($restoreInfo) {
            return [
                $restoreInfo['national_id'],
                $restoreInfo['name'],
                __($restoreInfo['traineeState']),
                $restoreInfo['hours'],
                $restoreInfo['amount'],
                $restoreInfo['creditHours'],
            ];
        }, $this->restoreInfo);

        return $r;
    }

    public function headings(): array
    {
        return [
            'رقم الهوية',
            'الاسم',
            'الحالة',
            'الساعات المستردة',
            'المبلغ المسترد',
            'الساعات المعتمدة',
        ];
    }

    public function title(): string
    {
        return 'اسنرداد';
    }



    public function registerEvents(): array
    {
        $color = 'ffffff';
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

                for ($i = 2; $i <= count($this->restoreInfo)+2; $i++) {
                    $event->getDelegate()->getStyle('A' . $i . ':F' . $i)->applyFromArray($styleArray);
                }
            },
        ];
    }
}
