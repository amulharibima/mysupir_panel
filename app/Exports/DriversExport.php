<?php

namespace App\Exports;

use App\Driver;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;


class DriversExport implements FromView, ShouldAutoSize, WithStyles
{
    public function view(): View
    {
        return view('exports.drivers', [
            'drivers' => Driver::all()
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A2:C7')
            ->getBorders()
            ->getOutline()
            ->setBorderStyle(Border::BORDER_THICK);
    }
}
