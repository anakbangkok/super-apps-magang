<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengajuanIzinExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                $item->user->name ?? 'Tidak Diketahui',
                $item->jenis_izin,
                $item->durasi,
                $item->tanggal_mulai,
                $item->tanggal_selesai,
                $item->keterangan,
                ucfirst($item->status),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Pengguna',
            'Jenis Izin',
            'Durasi',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Keterangan',
            'Status',
        ];
    }
}
