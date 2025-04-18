<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;

class AttendanceExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Attendance::with('user')->orderBy('check_in', 'desc');

        if($this->request->has('user_id')) {
            $query->where('user_id', $this->request->user_id);
        }

        if($this->request->has('start_date') && $this->request->has('end_date')) {
            $query->whereBetween('check_in', [
                $this->request->start_date . ' 00:00:00',
                $this->request->end_date . ' 23:59:59'
            ]);
        }

        return $query->get()->map(function ($item) {
            return [
                'Nama' => $item->user->name,
                'Email' => $item->user->email,
                'Check In' => $item->check_in,
                'Check Out' => $item->check_out,
                'Latitude' => $item->latitude,
                'Longtitude' => $item->longitude,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Check In',
            'Check Out',
            'Latitude',
            'Longtitude',
        ];
    }
}
