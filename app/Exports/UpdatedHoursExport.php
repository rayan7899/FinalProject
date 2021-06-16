<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UpdatedHoursExport implements WithMultipleSheets
{
    public $restoreInfo;
    public $addInfo;

    public function __construct($restoreInfo,$addInfo)
    {
        $this->restoreInfo = $restoreInfo;
        $this->addInfo = $addInfo;

    }

       public function sheets(): array
    {
        $sheets = [
            new RestoreHours($this->restoreInfo),
            new addHours($this->addInfo),
        ];

        return $sheets;
    }
}
