<?php

namespace App\Http\Controllers\Api;

use App\CrashReport;
use App\Events\CrashReportCreated;
use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CrashReportController extends Controller
{
    public function addReport(Request $request, Order $order)
    {
        $request->validate([
            'photos' => 'required|array|min:1|max:5',
            'photos.*' => 'nullable|image|max:5120',
            'notes' => 'nullable|string|max:255'
        ]);

        abort_if(!$order->isOrderFinished(), 403, 'Order has not finished yet.');
        abort_if(!empty($order->crash_report), 403, 'Report already exists.');
        abort_if($order->user_id != Auth::id(), 403, 'Access denied.');

        $photos = [];
        foreach ($request->file('photos') as $i => $photo) {
            if (!empty($photo)) {
                $path = $photo->store('order/'.$order->getOrderIdentifier().'/report');
                if ($path) {
                    $photos[$i] = $path;
                }
            }
        }

        $report = CrashReport::create([
            'order_id' => $order->id,
            'photos' => $photos,
            'notes' => $request->notes
        ]);

        if ($report) {
            event(new CrashReportCreated($report));
        }

        return response()->json(['message' => 'ok']);
    }
}
