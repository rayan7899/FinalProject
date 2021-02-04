<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'national_id'   => $row[0],
            'name'          => $row[1],
            'email'         => $row[0]."@tvtc.edu.sa",
            'phone'         => $row[2],
            'password' => Hash::make("btc12345")
        ]);
    }
}
