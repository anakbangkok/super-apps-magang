<?php

namespace App\Exports;

use App\Models\Journal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JournalsExport implements FromCollection, WithHeadings
{
    protected $journals;

    public function __construct($journals)
    {
        $this->journals = $journals;
    }

    public function collection()
    {
        return $this->journals->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'id',
            'Tanggal',
            'Nama',
            'Waktu Mulai',
            'Waktu Selesai',
            'Kegiatan',
        ];
    }
}
