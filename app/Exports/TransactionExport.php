<?php

namespace App\Exports;

use App\Transaction;
use App\RequestIncome;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class TransactionExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    public $data;

    public function __construct($data){
        $this->data = $data;
    }

    public function view(): View
    {
        $driver_id = $this->data['driver'];
        $start_date = $this->data['dari_tanggal'];
        $end_date = $this->data['sampai_tanggal'];

        $transactions = Transaction::whereHas('order', function($query) use($driver_id){
            $query->whereHas('driver', function($query) use($driver_id){
                if($driver_id)
                    $query->where('id', $driver_id);
            });
        })
            ->whereDate('created_at', '>=', $start_date)                                 
            ->whereDate('created_at', '<=', $end_date)
            ->get();
        

        $request_incomes = RequestIncome::where('status', RequestIncome::TRANSFERRED)
            ->where('driver_id', $driver_id)
            ->whereDate('created_at', '>=', $start_date)                                 
            ->whereDate('created_at', '<=', $end_date)
            ->get();;

        $transactions = $transactions->merge($request_incomes);
        // $transactions = $transactions->sort(function($a, $b){
        //     return strtotime($a->created_at) < strtotime($b->created_at);
        // })->values()->all();

        return view('exports.transaction', compact('transactions'));
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
            ],

        ];
    }
}
