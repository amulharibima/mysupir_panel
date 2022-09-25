<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use Carbon\Carbon;

class AdminTransaksiController extends Controller
{
    public function index(){
        $orders = array();
        foreach (Order::orderBy('created_at', 'desc')->get() as $order) {
            if ($order->isHasDriver()) {
                array_push($orders, $order);
            }
        }
        return view('admin.transaksi.index', compact('orders'));
    }

    public function detail($id){
        $order = Order::findOrFail($id);
        return view('admin.transaksi.detail', compact('order'));
        // return dd(Carbon::now()->diffInDays(Carbon::parse($order->created_at), false));
        // return dd($order->created_at);
    }
}
