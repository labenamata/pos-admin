<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class ProdukExport implements FromCollection, WithHeadings, ShouldAutoSize,WithTitle,WithStyles,WithCustomStartCell,WithMapping
{
    protected $penjualanProduk;
    protected $tanggal_mulai;
    protected $tanggal_akhir;
    protected $nama_kategori;
    protected $totalQty;
    protected $totalPenjualan;

    public function __construct($penjualanProduk, $tanggal_mulai, $tanggal_akhir, $nama_kategori, $totalQty, $totalPenjualan)
    {
        $this->penjualanProduk = $penjualanProduk;
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->nama_kategori = $nama_kategori;
        $this->totalQty = $totalQty;
        $this->totalPenjualan = $totalPenjualan;
    }

    public function collection()
    {
        return $this->penjualanProduk;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Produk',
            'Kategori',
            'Satuan',
            'Harga Pokok (Rp)',
            'Harga Jual (Rp)',
            'Jumlah Terjual',
            'Total Harga Pokok (Rp)',
            'Total Penjualan (Rp)',
            'Keuntungan (Rp)'
        ];
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;
        
        $harga_pokok = (int)$item->harga_pokok;
        $harga_jual = (int)$item->harga_jual;
        $total_qty = (int)$item->total_qty;
        $total_penjualan = (int)$item->total_penjualan;
        
        $total_harga_pokok = $harga_pokok * $total_qty;
        $keuntungan = $total_penjualan - $total_harga_pokok;
        
        return [
            $no,
            $item->nama_produk,
            $item->nama_kategori,
            $item->nama_satuan,
            $harga_pokok,
            $harga_jual,
            $total_qty,
            $total_harga_pokok,
            $total_penjualan,
            $keuntungan
        ];
    }

    public function title(): string
    {
        return 'Laporan Penjualan Produk';
    }

    public function styles(Worksheet $sheet)
    {
        // Tambahkan judul laporan
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'LAPORAN PENJUALAN PRODUK');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Tambahkan periode laporan
        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', 'Periode: ' . \Carbon\Carbon::parse($this->tanggal_mulai)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($this->tanggal_akhir)->format('d/m/Y'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Tambahkan kategori
        $sheet->mergeCells('A3:J3');
        $sheet->setCellValue('A3', 'Kategori: ' . $this->nama_kategori);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Beri jarak sebelum header
        $sheet->mergeCells('A4:J4');
        
        // Style untuk header
        $sheet->getStyle('A5:J5')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'CCCCCC',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);
        
        // Hitung jumlah baris data
        $dataCount = count($this->penjualanProduk) + 5; // +5 karena header dimulai dari baris ke-5
        
        // Style untuk data
        $sheet->getStyle('A6:J' . $dataCount)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);
        
        // Tambahkan total di baris terakhir
        $lastRow = $dataCount + 1;
        $sheet->mergeCells('A' . $lastRow . ':F' . $lastRow);
        $sheet->setCellValue('A' . $lastRow, 'TOTAL');
        
        // Hitung total harga pokok dan keuntungan
        $totalHargaPokok = 0;
        $totalKeuntungan = 0;
        
        foreach ($this->penjualanProduk as $item) {
            $totalHargaPokok += (int)$item->harga_pokok * (int)$item->total_qty;
            $totalKeuntungan += (int)$item->total_penjualan - ((int)$item->harga_pokok * (int)$item->total_qty);
        }
        
        $sheet->setCellValue('G' . $lastRow, $this->totalQty);
        $sheet->setCellValue('H' . $lastRow, $totalHargaPokok);
        $sheet->setCellValue('I' . $lastRow, $this->totalPenjualan);
        $sheet->setCellValue('J' . $lastRow, $totalKeuntungan);
        $sheet->getStyle('A' . $lastRow . ':J' . $lastRow)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'EEEEEE',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);
        
        return [
            5 => ['font' => ['bold' => true]],
        ];
    }
}
