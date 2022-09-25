<?php

namespace App\Http\Middleware;

use Closure;

class ValidateMidtransNotification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $content = json_decode($request->getContent());
        $signatureKey = $content->signature_key;
        abort_if(empty($signatureKey), 404);

        $statusCode = $content->status_code;
        $orderId = $content->order_id;
        $grossAmount = $content->gross_amount;

        // Validate webhook
        $hashedKey = openssl_digest($orderId.$statusCode.$grossAmount.config('services.midtrans.server_key'), 'sha512');
        abort_if($hashedKey != $signatureKey, 404);

        return $next($request);
    }
}
