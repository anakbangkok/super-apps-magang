<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Hash;


class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {

        if (empty($row['name'])) {
            \Log::warning('Nama kosong:', $row);
            return null; 
        }


        $start_date = Date::excelToDateTimeObject($row['start_date']);
        $end_date = Date::excelToDateTimeObject($row['end_date']);

        return new User([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
            'start_date' => Carbon::instance($start_date)->format('Y-m-d H:i:s'),
            'end_date' => Carbon::instance($end_date)->format('Y-m-d H:i:s'),
        ]);
    }
}

