<?php

namespace App\Http\Controllers\Api;

use App\DriverLocation;
use App\Events\PanicCreated;
use App\Helpers\NexmoSms;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderLocation;
use App\Panic;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $user = $request->user();
        $roles = $user->roles;

        return response()->json([
            'user' => $user,
            'roles' => $roles->pluck('name')
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$request->user()->id
        ]);

        $user = $request->user();

        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        return response()->json([
            'message' => 'ok'
        ]);
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|max:15120'
        ]);

        $user = $request->user();

        $foto_path = $request->file('foto')->store('user/foto');
        if ($foto_path) {
            $old_foto_path = $user->foto;

            $user->update([
                'foto' => $foto_path
            ]);

            if ($user->wasChanged('foto')) {
                if (Storage::exists($old_foto_path)) {
                    Storage::delete($old_foto_path);
                }
            }
        }

        return response()->json([
            'message' => 'ok'
        ]);
    }

    public function updatePhoneNumber(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|numeric|unique:users,phone_number,'.$request->user()->id
        ]);

        $temp_user = DB::table('user_change_numbers')->where('user_id', Auth::id());

        if (!empty($current_temp_user = $temp_user->first())) {
            $formatted  = Carbon::createFromFormat('Y-m-d H:i:s', $current_temp_user->created_at);

            // check if current user timestamp if greater than 5 min then resend sms
            if ($formatted->diffInMinutes(Carbon::now()) < 6) {
                throw ValidationException::withMessages(['phone_number' => ['Kode OTP telah dikirim.']]);
            } else {
                $verification_id = NexmoSms::sendAuthRequest($request->phone_number);

                $temp_user->update([
                    'phone_number' => $request->phone_number,
                    'verification_id' => $verification_id,
                    'created_at' => now()
                ]);
            }
        } else {
            $verification_id = NexmoSms::sendAuthRequest($request->phone_number);

            DB::table('user_change_numbers')->insert([
                'phone_number' => $request->phone_number,
                'verification_id' => $verification_id,
                'user_id' => Auth::id(),
                'created_at' => now()
            ]);
        }

        return response()->json(['message' => 'ok']);
    }

    public function verifyPhoneNumber(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|numeric|unique:users,phone_number,'.$request->user()->id,
            'otp_code' => 'required|numeric|digits:6'
        ]);

        $temp_user = DB::table('user_change_numbers')->where('user_id', Auth::id());

        abort_if(empty($temp_user->first()), 404, 'User tidak ditemukan, submit ulang.');

        if (!empty($current_temp_user = $temp_user->first())) {
            $isVerified = NexmoSms::verifyAuthRequest($current_temp_user->verification_id, $request->otp_code);
            if ($isVerified instanceof Exception) {
                throw ValidationException::withMessages([
                    'otp_code' => ['Kode OTP tidak ditemukan atau telah dipakai.']
                ]);
            }

            $user = $request->user();

            $user->update([
                'phone_number' => $request->phone_number
            ]);

            if ($user->wasChanged('phone_number')) {
                $temp_user->delete();
            }
        }

        return response()->json(['message' => 'ok']);
    }

    public function getDriverNearestLocation(Request $request)
    {
        $lat = $request->input('lat');
        $long = $request->input('long');
        $max_radius = 2; // in km

        if (empty($lat) || empty($long)) {
            abort(404, 'Latitude or Longitude cannot be null.');
        }

        $drivers = DriverLocation::select('*', DB::raw("6371 * acos(cos(radians(" . $lat . ")) * cos(radians(driver_locations.latitude))* cos(radians(driver_locations.longitude) - radians(" . $long . "))+ sin(radians(" .$lat. "))* sin(radians(driver_locations.latitude))) AS distance"))
        ->having('distance', '<=', $max_radius)
        ->whereHas('driver', function (Builder $query) {
            $query->where('order_status', 1);
        })->orderBy('distance')
        ->get();

        return response()->json([
            'driver_locations' => $drivers
        ]);
    }

    public function notifyPanic(Request $request, Order $order)
    {
        $request->validate([
            'location_latitude' => 'required|numeric', // location latitude
            'location_longitude' => 'required|numeric', // location longitude
            'location_name' => 'required|string|max:255',
        ]);

        abort_if(Auth::id() != $order->user_id, 403, 'Access denied.');

        $newUserPanic = Panic::create([
            'latitude' => $request->location_latitude,
            'longitude' => $request->location_longitude,
            'location_name' => $request->location_name,
            'order_id' => $order->id,
            'user_id' => Auth::id()
        ]);

        if ($newUserPanic) {
            event(new PanicCreated($newUserPanic));
        }

        return response()->json(['message' => 'ok']);
    }

    public function getFavouriteLocations(Request $request)
    {
        $type = $request->input('type');

        if ($type != 'time') {
            $type = 'trip';
        }

        $locations = OrderLocation::whereHas('order', function (Builder $query) use ($type) {
            $query->finished()
                ->where('user_id', Auth::id())
                ->where('type', $type);
        })
        ->where('start', true)
        ->orderBy('created_at', 'DESC')
        ->get();

        return response()->json([
            'locations' => count($locations) ? $locations->unique('name')->take(5) : []
        ]);
    }
}
