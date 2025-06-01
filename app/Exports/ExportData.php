<?php

namespace App\Exports;

use App\Models\bayi;
use App\Models\User;
use App\Models\villages;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Carbon\Carbon;


class ExportData implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    protected $id;
    protected $tipe;
    protected $jenisExport; // 'gizi' atau 'stunting'

    public function __construct($id, $tipe = 'desa', $jenisExport = 'gizi')
    {
        $this->id = $id;
        $this->tipe = $tipe;
        $this->jenisExport = $jenisExport;
    }

    public function collection()
    {
        if ($this->jenisExport === 'gizi') {
            return $this->exportGizi();
        } elseif ($this->jenisExport === 'stunting') {
            return $this->exportStunting();
        }
    }

    protected function exportGizi()
    {
        $rekap = collect();
        $bulanList = DB::table('hist_gizi')
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
            ->orderByDesc('bulan')
            ->distinct()
            ->pluck('bulan');

        if ($this->tipe === 'kecamatan') {
            // RATA-RATA GIZI PER DESA DALAM SATU KECAMATAN
            $villages = Villages::where('district_id', $this->id)->get();
            $no = 1;

            foreach ($bulanList as $bulan) {
                foreach ($villages as $desa) {
                    $userIds = User::where('id_villages', $desa->id)->pluck('id');
                    $bayis = Bayi::whereIn('id_users', $userIds)->pluck('id');

                    $total = [0, 0, 0, 0, 0];
                    $count = 0;

                    foreach ($bayis as $idBayi) {
                        $record = DB::table('hist_gizi')
                            ->where('id_bayi', $idBayi)
                            ->where('tanggal', 'like', "$bulan%")
                            ->orderByDesc('tanggal')
                            ->first();

                        if ($record) {
                            $hist = json_decode($record->nilai_gizi, true);
                            if (is_array($hist) && count($hist) === 5) {
                                for ($i = 0; $i < 5; $i++) {
                                    $total[$i] += (int)$hist[$i];
                                }
                                $count++;
                            }
                        }
                    }

                    $rekap->push([
                        $no++,
                        $desa->name,
                        $count ? round($total[0] / $count, 2) : '-',
                        $count ? round($total[1] / $count, 2) : '-',
                        $count ? round($total[2] / $count, 2) : '-',
                        $count ? round($total[3] / $count, 2) : '-',
                        $count ? round($total[4] / $count, 2) : '-',
                        \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y')
                    ]);
                }
            }
        }

        elseif ($this->tipe === 'desa') {
            $no = 1;
            $userIds = User::where('id_villages', $this->id)->pluck('id');
            $bayis = Bayi::whereIn('id_users', $userIds)->get();

            // Ambil seluruh bulan yang tersedia dari hist_gizi, terbaru ke lama
            $bulanList = DB::table('hist_gizi')
                ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
                ->orderByDesc('bulan')
                ->distinct()
                ->pluck('bulan');

            foreach ($bulanList as $bulan) {
                foreach ($bayis as $bayi) {
                    $record = DB::table('hist_gizi')
                        ->where('id_bayi', $bayi->id)
                        ->where('tanggal', 'like', "$bulan%")
                        ->orderByDesc('tanggal')
                        ->first();

                    if ($record) {
                        $gizi = json_decode($record->nilai_gizi, true);
                        $rekap->push([
                            $no++,
                            $bayi->nama,
                            $bayi->kelamin,
                            optional($bayi->orangtua)->nama ?? '-',
                            $bayi->tanggalLahir,
                            $gizi[0] ?? '-',
                            $gizi[1] ?? '-',
                            $gizi[2] ?? '-',
                            $gizi[3] ?? '-',
                            $gizi[4] ?? '-',
                            \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y')
                        ]);
                    }
                }
            }
        }

        return $rekap;
    }

    protected function exportStunting()
    {
        $rekap = collect();
        $bulanList = DB::table('hist_stun')
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
            ->orderByDesc('bulan')
            ->distinct()
            ->pluck('bulan');

        if ($this->tipe === 'kecamatan') {
            // RATA-RATA STUNTING PER DESA DALAM SATU KECAMATAN
            $villages = Villages::where('district_id', $this->id)->get();
            $no = 1;

            foreach ($bulanList as $bulan) {
                foreach ($villages as $desa) {
                    $userIds = User::where('id_villages', $desa->id)->pluck('id');
                    $bayis = Bayi::whereIn('id_users', $userIds)->pluck('id');

                    $kategori = [0, 0, 0, 0]; // Index: 0=>1, 1=>2, 2=>3, 3=>4
                    $count = 0;

                    foreach ($bayis as $idBayi) {
                        $record = DB::table('hist_stun')
                            ->where('id_bayi', $idBayi)
                            ->where('tanggal', 'like', "$bulan%")
                            ->orderByDesc('tanggal')
                            ->first();

                        if ($record && in_array($record->jenis, [1, 2, 3, 4])) {
                            $kategori[$record->jenis - 1]++;
                            $count++;
                        }
                    }

                    $rekap->push([
                        $no++,
                        $desa->name,
                        $count,
                        $kategori[0],
                        $kategori[1],
                        $kategori[2],
                        $kategori[3],
                        \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y')
                    ]);
                }
            }
        }

        elseif ($this->tipe === 'desa') {
            $no = 1;
            $userIds = User::where('id_villages', $this->id)->pluck('id');
            $bayis = Bayi::whereIn('id_users', $userIds)->get();

            foreach ($bulanList as $bulan) {
                foreach ($bayis as $bayi) {
                    $record = DB::table('hist_stun')
                        ->where('id_bayi', $bayi->id)
                        ->where('tanggal', 'like', "$bulan%")
                        ->orderByDesc('tanggal')
                        ->first();

                    if ($record && in_array($record->jenis, [1, 2, 3, 4])) {
                        $rekap->push([
                            $no++,
                            $bayi->nama,
                            $bayi->kelamin,
                            optional($bayi->user)->namaIbu ?? '-',
                            $bayi->tanggalLahir,
                            $record->jenis,
                            \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y')
                        ]);
                    }
                }
            }
        }

        return $rekap;

    }

    public function headings(): array
    {
        if ($this->jenisExport === 'gizi') {
            if ($this->tipe === 'kecamatan') {
                return [
                    'No',
                    'Nama Desa',
                    'Makanan Pokok',
                    'Lauk Pauk',
                    'Sayur-sayuran',
                    'Buah-buahan',
                    'Minuman',
                    'Bulan'
                ];
            }
            return [
                'No',
                'Nama Anak',
                'Kelamin',
                'Nama Orangtua',
                'Tanggal Lahir',
                'Makanan Pokok',
                'Lauk Pauk',
                'Sayur-sayuran',
                'Buah-buahan',
                'Minuman',
                'Bulan'
            ];
        } else {
            if ($this->tipe === 'kecamatan') {
                return ['No', 'Nama Desa', 'Jumlah Anak', 'Sangat Pendek', 'Pendek', 'Normal', 'Tinggi', 'Bulan'];
            }
            return [
                'No',
                'Nama Anak',
                'Kelamin',
                'Nama Orangtua',
                'Tanggal Lahir',
                'Jenis Stunting',
                'Bulan'
            ];
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 25,
            'C' => 18,
            'D' => 18,
            'E' => 18,
            'F' => 18,
            'G' => 18,
            'H' => 18,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Sisipkan baris kosong sebelum heading
                $sheet->insertNewRowBefore(2, 1);

                if ($this->jenisExport === 'gizi') {
                    $sheet->setCellValue('A2', 'Keterangan Gizi: 1 = Kurang, 2 = Normal, 3 = Kelebihan');
                }else if ($this->tipe === 'desa' && $this->jenisExport === 'stunting') {
                    $sheet->setCellValue('A2', 'Keterangan Stunting: 1 = Sangat Pendek, 2 = Pendek, 3 = Normal, 4 = Tinggi');
                }

                // Keterangan di atas tabel
                // $sheet->setCellValue('A2', 'Keterangan Gizi: 1 = Kurang, 2 = Normal, 3 = Kelebihan');

                $highestRow = $sheet->getHighestRow();
                $highestColumn = match (true) {
                    $this->tipe === 'desa' && $this->jenisExport === 'gizi' => 'K',
                    $this->tipe === 'desa' && $this->jenisExport === 'stunting' => 'G',
                    $this->tipe === 'kecamatan' && $this->jenisExport === 'gizi' => 'H',
                    $this->tipe === 'kecamatan' && $this->jenisExport === 'stunting' => 'H', // asumsi
                    default => 'H' // fallback aman
                };

                if ($this->tipe === 'desa') {
                    $bulanKolom = $this->jenisExport === 'gizi' ? 'K' : 'G';
                } else {
                    $bulanKolom = 'H'; // untuk kecamatan (baik gizi maupun stunting, diasumsikan di kolom H)
                }

                $currentBulan = null;

                $colors = ['FFE699', 'D9EAD3', 'F4CCCC', 'D0E0E3', 'EAD1DC'];
                $colorIndex = 0;
                $fillColor = $colors[0];

                // Border style
                $borderStyle = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];

                // Mulai dari baris ke-3 (setelah keterangan dan heading)
                for ($row = 3; $row <= $highestRow; $row++) {
                    $bulanCell = $sheet->getCell($bulanKolom . $row)->getValue();

                    // Jika bulan berubah, ganti warna
                    if ($bulanCell !== $currentBulan) {
                        $currentBulan = $bulanCell;
                        $fillColor = $colors[$colorIndex % count($colors)];
                        $colorIndex++;
                    }

                    // Mewarnai latar belakang baris (kolom A sampai kolom terakhir)
                    $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB($fillColor);

                    // Menambahkan border ke seluruh baris
                    $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->applyFromArray($borderStyle);
                }

                // Tambahkan border juga ke heading (baris ke-2)
                $sheet->getStyle("A1:{$highestColumn}2")->applyFromArray($borderStyle);
            }
        ];
    }
}
