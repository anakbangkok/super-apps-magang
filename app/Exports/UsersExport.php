<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    // Menambahkan header pada file excel
    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Email',
            'Instansi',
            'Penugasan',
            'Mentor',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Status'
        ];
    }

    public function collection()
    {
        return $this->users->map(function($user) {
            return [
                $user->id,                               // ID
                $user->name,                             // Nama
                $user->email,                            // Email
                $user->instansi->nama_instansi ?? 'N/A', // Instansi
                $user->penugasan->nama_unit_bisnis ?? 'N/A', // Penugasan
                $user->mentor->name ?? 'N/A',            // Mentor
                $user->start_date ? \Carbon\Carbon::parse($user->start_date)->translatedFormat('d F Y') : 'N/A', // Tanggal Mulai
                $user->end_date ? \Carbon\Carbon::parse($user->end_date)->translatedFormat('d F Y') : 'N/A', // Tanggal Selesai
                $this->getStatusBadge($user)             // Status
            ];
        });
    }

    // Method untuk mendapatkan status
    private function getStatusBadge($user)
    {
        $now = now()->toDateString();
        
        if (!$user->start_date || !$user->end_date) {
            return 'Data tidak ditemukan';
        } elseif ($now < $user->start_date) {
            return 'Belum masuk';
        } elseif ($now >= $user->start_date && $now <= $user->end_date) {
            return 'Aktif';
        } else {
            return 'Selesai';
        }
    }
}
