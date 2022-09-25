<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Driver;
use App\Order;
use App\Panic;
use App\User;
use App\Transaction;

use App\Exports\DriversExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['getImage', 'startPage', 'testExport']);
    }

    public function startPage(){
        return redirect()->route('login');
    }

    public function testExport(){
        // return view('exports.drivers', ['drivers' => Driver::all()]);;
        return Excel::download(new DriversExport, 'drivers.xlsx');
    }

    public function index(){
        $driver = Driver::all();
        $order = Order::limit(7)->orderBy('created_at', 'desc')->get();
        $countpanic = Panic::count();
        $countorder = Order::count();
        $countdriver = Driver::count(); 
        $countactivedriver = Driver::where('order_status', Driver::AVAILABLE_STATUS)->count(); 
        $countnonactivedriver = $countdriver - $countactivedriver;
        $countuser = User::count() - $countdriver;

        $earnings_per_month = Transaction::whereYear('created_at', now()->format('Y'))->where('status', Transaction::SUDAH_DIBAYAR)->selectRaw("SUM(total_price - fee) as amount, MONTH(created_at) AS month")->groupBy('month')->get();
        $earnings_per_month = $earnings_per_month->groupBy('month');
        $arrMonths = [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];

        // return dd($order[0]->user->name);
        // return compact('order');
        return view('admin.index', compact('driver', 'order', 'countpanic', 'countorder', 'countdriver', 'countactivedriver', 'countnonactivedriver', 'countuser', 'earnings_per_month', 'arrMonths'));
    }

    public function getImage(Request $request) {
        // return view('welcome');
        // return dd(auth()->check());
        // $driver = Driver::find($request->input('id'));
        $path = storage_path('app/public/'.$request->input('img_path'));
        // return dd(File::exists($path));
        // return dd($path);
        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

}
