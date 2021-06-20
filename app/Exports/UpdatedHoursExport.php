<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\RestoreHours;
use App\Exports\AddHours;

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
            new AddHours($this->addInfo),
        ];

        return $sheets;
    }
}
