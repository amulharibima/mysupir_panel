<?php

namespace App\Http\Controllers\admin;

use App\Driver;
use App\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RequestIncome;
use App\EarningHistory;
use Carbon\Carbon;

class AdminLaporanKerusakanController extends Controller
{
    public function index(){
        $orders = Order::whereHas('rating', function($query){
            $query->where([['rating', '<=', 3]]);
        })->whereHas('crash_report')
        ->get();

        // return dd($orders);
        return view('admin.laporan_kerusakan.index', compact('orders'));
    }

    public function solve(Request $request) {
        $order = Order::find($request->order_id);
        $order->crash_report->is_solved = true;
        $order->crash_report->save();
        return redirect()->route('admin.laporan-kerusakan');
    }

}
