<?php

namespace App\Imports;

use App\Models\Aktivitas;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JournalsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $tanggal = Carbon::createFromFormat('Y-m-d', Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal']))->format('Y-m-d'));

        $jamMulai = $this->convertExcelTimeToTimeString($row['jam_mulai']);
        $jamSelesai = $this->convertExcelTimeToTimeString($row['jam_selesai']);

        $user = User::where('name', $row['nama'])->first();

        if (!$user) {
            $userId = null;
        } else {
            $userId = $user->id;
        }

        return new Aktivitas([
            'date'       => $tanggal,
            'name'       => $row['nama'],
            'start_time' => $jamMulai,
            'end_time'   => $jamSelesai,
            'activity'   => $row['aktivitas'],
            'user_id'    => $userId,
        ]);
    }
    
    private function convertExcelTimeToTimeString($excelTime)
    {
        if ($excelTime == 0) {
            return '00:00';
        }

        $hours = floor($excelTime * 24);
        $minutes = round(($excelTime * 24 - $hours) * 60);
    
        return sprintf('%02d:%02d', $hours, $minutes);
    }
    
}
    
