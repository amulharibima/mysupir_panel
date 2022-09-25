<?php

namespace App\Http\Controllers\Api;

use App\EarningHistory;
use App\Http\Controllers\Controller;
use App\Order;
use App\RequestIncome;
use App\Traits\CurrentDriver;
use App\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class EarningController extends Controller
{
    use CurrentDriver;

    /**
     * Generate last month earning
     * periode = monthly
     */
    public function generateEarningByPeriode()
    {
        $firstOfMonth = now()->subMonth()->firstOfMonth();
        $endOfMonth = now()->subMonth()->endOfMonth();

        $driver = $this->getCurrentDriver();

        $lastMontEarnings = EarningHistory::where('driver_id', $driver->id)
            ->where('type', EarningHistory::INCOME_TYPE)
            ->where('periode_start', $firstOfMonth->toDateString())
            ->where('periode_end', $endOfMonth->toDateString())
            ->get();

        // generate if last month earnings not exists
        if (! count($lastMontEarnings)) {
            $totalEarning = Transaction::whereHas('order', function (Builder $query) use ($driver, $firstOfMonth, $endOfMonth) {
                $query->finished()
                    ->where('driver_id', $driver->id)
                    ->whereBetween('finish_datetime', [$firstOfMonth->toDateTimeString(), $endOfMonth->endOfDay()->toDateTimeString()]);
            })->sum('total_price');

            EarningHistory::create([
                'driver_id' => $driver->id,
                'type' => EarningHistory::INCOME_TYPE,
                'periode_start' => $firstOfMonth->toDateString(),
                'periode_end' => $endOfMonth->endOfMonth()->toDateString(),
                'amount' => $totalEarning
            ]);
        }

        return response()->json(['message' => 'ok'], 201);
    }
    
    public function getAllHistories() {
        $factory = new \Carbon\Factory([
                    'locale' => 'id_ID',
                    'timezone' => 'Asia/Jakarta',
                ]);
                
        $driver = $this->getCurrentDriver();
        
        $lastMontEarnings = EarningHistory::where('driver_id', $driver->id)
            // ->where('type', EarningHistory::INCOME_TYPE)
            ->orderBy('periode_end')
            ->get();
            
        foreach ($lastMontEarnings as $data) {
            $end = Carbon::parse($data->periode_end);
            $start = Carbon::parse($data->periode_start);
            $data->period = $data->type === EarningHistory::INCOME_TYPE 
                            ? $factory->make($start)->isoFormat('Do MMM').' - '.$factory->make($end)->isoFormat('Do MMM YYYY') 
                            : $factory->make($end)->isoFormat('Do MMM YYYY');
        }
        
        return response()->json(['earning_history' => $lastMontEarnings]);
    }

    public function requestIncome(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric',
            'bank' => 'required|string|max:255',
            'bank_account_number' => 'required|numeric',
            'bank_account_holder' => 'required|string|max:255',
        ]);

        $driver = $this->getCurrentDriver();
        $remainingBalance = $driver->earning->amount - $request->nominal;
        $todayRequest = RequestIncome::whereDate('created_at', '=', \Carbon\Carbon::now()->toDateString())->first();

        if ($remainingBalance < 0) {
            throw ValidationException::withMessages([
                'nominal' => 'Insufficient balance.'
            ]);
        }
        
         if ($todayRequest) {
            throw ValidationException::withMessages([
                'date' => 'Anda sudah menarik saldo hari ini.'
            ]);
        }

        $newRequest = RequestIncome::create([
            'status' => RequestIncome::REQUESTED_STATUS,
            'nominal' => $request->nominal,
            'driver_id' => $driver->id,
            'bank' => $request->bank,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_holder' => $request->bank_account_holder
        ]);

        if ($newRequest) {
            // update current balance
            $driver->earning->update([
                'amount' => $remainingBalance
            ]);

            EarningHistory::create([
                'driver_id' => $driver->id,
                'type' => EarningHistory::OUTCOME_TYPE,
                'periode_start' => today(),
                'periode_end' => today(),
                'amount' => $request->nominal
            ]);

            // TODO: notify admin, etc
        }

        return response()->json(['message' => 'ok'], 201);
    }
}
