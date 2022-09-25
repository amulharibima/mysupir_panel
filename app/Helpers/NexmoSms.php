<?php

namespace App\Helpers;

class NexmoSms
{
    public static function sendAuthRequest($phone_number, int $length = 6)
    {
        $credentials = new \Nexmo\Client\Credentials\Basic(config('services.nexmo.key'), config('services.nexmo.secret'));
        $client = new \Nexmo\Client($credentials);
        $workflow_id = 4; // See https://developer.nexmo.com/verify/guides/workflows-and-events

        $verification = $client->verify()->start([
            'number' => '62'.$phone_number,
            'brand'  => config('app.name'),
            'code_length'  => (string) $length,
            'workflow_id' => $workflow_id
        ]);

        return $verification->getRequestId();
    }

    public static function verifyAuthRequest($verification_id, $code)
    {
        $credentials = new \Nexmo\Client\Credentials\Basic(config('services.nexmo.key'), config('services.nexmo.secret'));
        $client = new \Nexmo\Client($credentials);

        try {
            $verification = new \Nexmo\Verify\Verification($verification_id);
            $result = $client->verify()->check($verification, $code);

            return $result->getRequestData();
        } catch (\Exception $e) {
            return $e;
        }

        return $result;
    }

    public static function resendAuthRequest($verification_id)
    {
        $credentials = new \Nexmo\Client\Credentials\Basic(config('services.nexmo.key'), config('services.nexmo.secret'));
        $client = new \Nexmo\Client($credentials);

        try {
            $result = $client->verify()->trigger($verification_id);
        } catch (\Exception $e) {
            return $e;
        }

        return $result;
    }
}
