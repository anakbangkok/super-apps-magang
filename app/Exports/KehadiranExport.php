<?php

namespace App\Exports;

use App\Models\Kehadiran;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KehadiranExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;
    protected $userId;
    protected $shift;

    public function __construct($startDate = null, $endDate = null, $userId = null, $shift = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->userId = $userId;
        $this->shift = $shift;
    }

    public function collection()
    {
        // Build the query with filters
        $query = Kehadiran::query();
        
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        }
    
        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }
    
        if ($this->shift) {
            $query->where('shift', $this->shift);
        }
    
        // Fetch the data with the user relation
        $data = $query->with('user')->get()->map(function ($kehadiran) {
            // Ganti user_id dengan nama user
            $kehadiran->user_id = $kehadiran->user ? $kehadiran->user->name : null;
            
            $kehadiran->date = Carbon::parse($kehadiran->date)->format('d F Y');
            $kehadiran->check_in = $kehadiran->check_in ? Carbon::parse($kehadiran->check_in)->format('H:i:s') : null;
            $kehadiran->check_out = $kehadiran->check_out ? Carbon::parse($kehadiran->check_out)->format('H:i:s') : null;
            
            return $kehadiran;
        });
    
        return $data;
    }
    

    public function headings(): array
    {
        return [
            'ID', 'Nama', 'Shift', 'Tanggal', 'Check In', 'Check Out', 'Lokasi'
        ];
    }
    
}
