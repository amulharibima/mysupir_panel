<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\EmailGoogleSmtp;
use App\Helpers\NexmoSms;
use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function requestLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = $this->getUserByEmail($request->email);

        $otp = rand(100000,999999);

        $data = [
            'name' => $user->name,
            'otp' => $otp,
        ];

        EmailGoogleSmtp::send($data, $request->email, 'Login');

        $user->update([
            'password' => bcrypt($otp)
        ]);

        return response()->json([
            'message' => 'ok'
        ]);
    }

    public function verifyLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|numeric|digits:6'
        ]);

        $user = $this->getUserByEmail($request->email);

        $isVerified = EmailGoogleSmtp::verify($request->otp_code, $user->password);
        if (!$isVerified) {
            throw ValidationException::withMessages([
                'otp_code' => ['Kode OTP tidak ditemukan atau telah dipakai.']
            ]);
        }

        $user->update([
            'password' => null
        ]);

        return response()->json([
            'access_token' => $this->generateToken($user),
            'user' => $user
        ]);
    }

    public function resendLogin(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|numeric'
        ]);

        $user = $this->getUserByPhoneNumber($request->phone_number);

        $resendOTP = NexmoSms::resendAuthRequest($user->verification_id);
        if ($resendOTP instanceof Exception) {
            if ($resendOTP->getCode() == 19) {
                $user->update([
                    'verification_id' => null
                ]);

                throw ValidationException::withMessages([
                    'otp_code' => ['Percobaan habis. Silahkan login ulang.']
                ]);
            }
        }

        return response()->json([
            'message' => 'ok'
        ]);
    }

    protected function generateToken(User $user)
    {
        $token = $user->createToken('Access Token');

        return $token->plainTextToken;
    }

    protected function getUserByEmail($email)
    {
      $user = User::where('email', $email)->first();
      if (empty($user)) {
        throw ValidationException::withMessages([
            'email' => ['E-mail tidak ditemukan atau salah.']
        ]);
      }

      abort_if(!$user->hasRole('user'), 403, 'Access denied');

      return $user;
    }

    protected function getUserByPhoneNumber($phone_number)
    {
        $user = User::where('phone_number', $phone_number)->first();
        if (empty($user)) {
            throw ValidationException::withMessages([
                'phone_number' => ['Nomor tidak ditemukan atau salah.']
            ]);
        }

        abort_if(!$user->hasRole('user'), 403, 'Access denied');

        return $user;
    }

    // development purpose only. TODO: remove this in production
    public function developerLogin(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|numeric'
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();
        // $user = User::where('phone_number', '81381577297')->first();
        if (empty($user)) {
            throw ValidationException::withMessages([
                'phone_number' => ['Nomor tidak ditemukan atau salah.']
            ]);
        }

        return response()->json([
            'access_token' => $this->generateToken($user),
            'user' => $user,
            'roles' => $user->roles->pluck('name')->all()
        ]);
    }
}
