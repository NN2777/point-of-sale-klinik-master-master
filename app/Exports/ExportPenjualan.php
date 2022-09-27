<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportPenjualan implements FromArray, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }

    public function array(): array
    {
        return $this->invoices;
    }

    public function headings(): array
    {
        return [
            'No',
            'No Faktur',
            'Tanggal',
            'ID Member',
            'ID Dokter',
            'Total Harga',
            'Diskon',
            'PPN',
            'Total Bayar',
            'Cara Bayar',
            'Jatuh Tempo'
        ];
    }
}
