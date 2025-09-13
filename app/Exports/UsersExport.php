<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::where('role', 'participant')
            ->get(['nip', 'full_name', 'position', 'division', 'email', 'institution', 'phone_number']);
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama Lengkap',
            'Posisi',
            'Divisi',
            'Email',
            'Institusi',
            'Nomor Telepon',
        ];
    }
}
