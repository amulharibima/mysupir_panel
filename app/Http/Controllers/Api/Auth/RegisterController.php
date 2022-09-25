<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\EmailGoogleSmtp;
use App\Helpers\NexmoSms;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function registerAsUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|unique:users|numeric'
        ]);

        $temp_user = DB::table('user_registrations')->where('phone_number', $request->phone_number);

        $otp = rand(100000,999999);

        $data = [
            'name' => $request->name,
            'otp' => $otp,
        ];

        EmailGoogleSmtp::send($data, $request->email, 'Register');

        if (empty($registered_user = $temp_user->first())) {
          DB::table('user_registrations')->insert([
              'phone_number' => $request->phone_number,
              'verification_id' => bcrypt($otp),
              'created_at' => now()
          ]);

        }
        else
          $temp_user->update(['verification_id' => bcrypt($otp)]);

        return response()->json(['message' => 'ok']);
    }

    public function verifyRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|unique:users|numeric',
            'otp_code' => 'required|numeric|digits:6'
        ]);

        $temp_user = DB::table('user_registrations')->where('phone_number', $request->phone_number);

        abort_if(empty($temp_user->first()), 404, 'User tidak ditemukan, registrasi ulang.');

        if (!empty($registered_user = $temp_user->first())) {
            $isVerified = EmailGoogleSmtp::verify($request->otp_code, $registered_user->verification_id);

            if (!$isVerified) {
                throw ValidationException::withMessages([
                    'otp_code' => ['Kode OTP tidak ditemukan atau telah dipakai.']
                ]);
            }

            $newUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number
            ]);

            if ($newUser) {
                $newUser->assignRole('user');

                $temp_user->delete();
            }
        }

        return response()->json([
            'access_token' => $this->generateToken($newUser),
            'user' => $newUser
        ]);
    }

    protected function generateToken(User $user)
    {
        $token = $user->createToken('Access Token');

        return $token->plainTextToken;
    }
}
