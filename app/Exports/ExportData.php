<?php

namespace App\Exports;

use App\Models\bayi;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ExportData implements FromCollection, WithHeadings, WithStyles, WithMapping, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $villageId;

    public function __construct($villageId)
    {
        $this->villageId = $villageId;
    }

    public function collection()
    {
        // Ambil ID semua user yang tinggal di desa tertentu
        $userIds = User::where('id_villages', $this->villageId)->pluck('id');

        // Ambil semua bayi yang user_id-nya termasuk dari user desa tersebut
        return bayi::with('user')
            ->whereIn('id_users', $userIds)
            ->get();
    }


    // Definisikan header kolom Excel
    public function headings(): array
    {
        return [
            'ID Bayi',
            'Nama Bayi',
            'Kelamin',
            'Nama Orangtua',
            'Tanggal Lahir',
            'Stunting',
            'Gizi'
        ];
    }

    // Styling header & sheet
    public function styles(Worksheet $sheet)
    {
        return [
            // Bold untuk header baris pertama
            1 => ['font' => ['bold' => true]],
            // Contoh: rata tengah seluruh kolom A sampai E
            'A:E' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
        ];
    }

    // Mapping data tiap row untuk atur kolom dan urutan
    public function map($row): array
    {
        // dd($row);
        return [
            $row->id,
            $row->nama,
            $row->kelamin,
            $row->user->namaIbu, // pakai relasi user, jika null kasih '-'
            $row->tanggalLahir,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10, // ID Bayi
            'B' => 25, // Nama Bayi
            'C' => 10, // Kelamin
            'D' => 25, // Nama Orangtua
            'E' => 20, // Tanggal Lahir
            'F' => 10, // Stunting (kalau nanti kamu tambah di map())
            'G' => 10, // Gizi (kalau nanti kamu tambah di map())
        ];
    }

}