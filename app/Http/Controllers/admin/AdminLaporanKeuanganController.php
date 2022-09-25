<?php

namespace App\Http\Controllers\admin;

use App\Driver;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AdminLaporanKeuanganController extends Controller
{
    public function index(){
        $drivers = Driver::all();

        return view('admin.laporan_keuangan.index', compact('drivers'));
    }

    public function export(Request $request){
        $validator = Validator::make($request->all(), [
            'driver' => '',
            'dari_tanggal' => 'required',
            'sampai_tanggal' => 'required',
        ], [
            'required' => ':attribute harus diisi'
        ], [
            'dari_tanggal' => 'Dari tanggal',
            'sampai_tanggal' => 'Sampai Tanggal',
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');
        $tanggal = now()->formatLocalized('%d %B %Y');


        return Excel::download(new TransactionExport($validator->validated()), "Laporan Keuangan {$tanggal}.xlsx");
    }
}
