<?php

namespace App\Http\Controllers\admin;

use App\Driver;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RequestIncome;
use App\EarningHistory;
use Carbon\Carbon;

class AdminDanaController extends Controller
{
    public function index(){
        $request = RequestIncome::all();

        // return dd($request);
        return view('admin.dana.index', compact('request'));
    }

    public function verify(Request $request) {
        $requestincome = RequestIncome::findOrFail($request->input('request_id'));
        $requestincome->update([
            'status' => RequestIncome::TRANSFERRED,
        ]);

        $earning_history = new EarningHistory();
        $earning_history->driver_id = $requestincome->driver_id;
        $earning_history->periode_start = Carbon::now()->format('Y-m-d');
        $earning_history->type = EarningHistory::OUTCOME_TYPE;
        $earning_history->amount = $requestincome->nominal;
        $earning_history->save();

        // return dd($earning_history);
        return redirect()->route('admin.dana');
    }

}
