<?php

namespace App\Exports;

use App\Models\Semester;
use App\Models\User;
use Illuminate\View\View as View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


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