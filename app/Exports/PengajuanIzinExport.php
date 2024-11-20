<?php

// Di App/Exports/PengajuanIzinExport.php
// Di App/Exports/PengajuanIzinExport.php
namespace App\Exports;

use App\Models\PengajuanIzin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengajuanIzinExport implements FromCollection, WithHeadings
{
    protected $pengajuanIzin;

    public function __construct($pengajuanIzin)
    {
        $this->pengajuanIzin = $pengajuanIzin;
    }

    public function collection()
    {
        return $this->pengajuanIzin->map(function ($izin) {
            return [
                'no' => $izin->id,
                'nama_pengguna' => $izin->user ? $izin->user->name : 'Tidak Diketahui',
                'jenis_izin' => $izin->jenis_izin ?? 'Tidak Diketahui', 
                'durasi' => $izin->durasi,
                'tanggal_mulai' => $izin->tanggal_mulai instanceof \Carbon\Carbon ? $izin->tanggal_mulai->translatedFormat('d F Y') : $izin->tanggal_mulai,
                'tanggal_selesai' => $izin->tanggal_selesai instanceof \Carbon\Carbon ? $izin->tanggal_selesai->translatedFormat('d F Y') : '-',
                'keterangan' => $izin->keterangan,
                'status' => ucfirst($izin->status)
            ];
        });
    }


    public function headings(): array
    {
        return [
            'No',
            'Nama Pengguna',
            'Jenis Izin',
            'Durasi',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Keterangan',
            'Status'
        ];
    }
}
