<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Hash;
use Mail;

class EmailGoogleSmtp
{
    public static function send(array $data, $to_email, $title){
      Mail::send('mail.mail_verify_account', $data, function($message) use ($to_email, $title) {
        $message->to($to_email)
            ->subject("My Supir - {$title}");
        $message->from('mysupir.haribima21@gmail.com','My Supir');
      });
    }

    public static function verify($otp, $hashedPassword){
      return Hash::check($otp, $hashedPassword);
    }
}
