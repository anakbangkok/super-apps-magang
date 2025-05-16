<?php

namespace App\Exports;

use App\Models\Kehadiran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KehadiranExport implements FromCollection, WithHeadings
{
    protected $kehadiran;

    public function __construct($kehadiran)
    {
        $this->kehadiran = $kehadiran;
    }

    public function collection()
    {

        return $this->kehadiran->map(function ($kehadiran) {
            return [
                $kehadiran->id,
                $kehadiran->user->name, 
                $kehadiran->shift,
                $kehadiran->date,
                $kehadiran->check_in,
                $kehadiran->check_out,
                $kehadiran->location,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Pengguna',
            'Shift',
            'Tanggal',
            'Check-in',
            'Check-out',
            'Location',
        ];
    }

}
