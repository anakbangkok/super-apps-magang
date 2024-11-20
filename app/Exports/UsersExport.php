<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;

class UsersExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $users;

    // Constructor to accept the users collection
    public function __construct($users)
    {
        $this->users = $users;
    }

    public function collection()
    {
        // Map the collection to include the status badge
        return $this->users->map(function ($user) {
            return [
                'Nama' => $user->name,
                'Email' => $user->email,
                'Penugasan' => optional($user->penugasan)->nama_unit_bisnis,
                'Mentor' => optional($user->mentor)->name,
                'Tanggal Mulai' => $user->start_date,
                'Tanggal Selesai' => $user->end_date,
                'Status' => $this->getStatusBadge($user), // Get status badge
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Penugasan',
            'Mentor',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Status'
        ];
    }

    public function styles($sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Bold header
        ];
    }

    public function title(): string
    {
        return 'Daftar Pengguna';
    }

    private function getStatusBadge($user)
    {
        $now = Carbon::now()->toDateString();
        if (!$user->start_date || !$user->end_date) {
            return 'Data tidak ditemukan';
        } elseif ($now < $user->start_date) {
            return 'Belum Masuk';
        } elseif ($now >= $user->start_date && $now <= $user->end_date) {
            return 'Aktif';
        } else {
            return 'Selesai';
        }
    }
}
