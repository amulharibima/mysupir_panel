<?php

namespace App\Http\Controllers\Api;

use App\Driver;
use App\DriverPhoto;
use App\Events\PanicCreated;
use App\Http\Controllers\Controller;
use App\Panic;
use App\Traits\CurrentDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    use CurrentDriver;

    public function currentDriverProfile()
    {
        $driver = $this->getCurrentDriver();

        return response()->json([
            'driver' => $driver,
            'rating' => $driver->getAverageRating(),
            'licenses' => $driver->licenses->pluck('name'),
            'earnings' => $driver->earning->amount
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string'
        ]);

        $driver = $this->getCurrentDriver();

        $driver->update([
            'name' => $request->name,
            'address' => $request->address
        ]);

        $driver->user->update([
            'name' => $request->name
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

        $driver = $this->getCurrentDriver();

        $foto_path = $request->file('foto')->store('driver/foto');
        if ($foto_path) {
            $old_foto_path = $driver->foto;

            $driver->update([
                'foto' => $foto_path
            ]);

            $driver->user->update([
                'foto' => $foto_path
            ]);

            if ($driver->wasChanged('foto')) {
                if (Storage::exists($old_foto_path)) {
                    Storage::delete($old_foto_path);
                }
            }
        }

        return response()->json([
            'message' => 'ok'
        ]);
    }

    public function toggleOrder(Request $request)
    {
        $request->validate([
            'order_status' => 'required|boolean',
            'lat' => 'required_if:order_status,1|nullable|numeric',
            'long' => 'required_if:order_status,1|nullable|numeric',
        ]);

        $driver = $this->getCurrentDriver();

        if (($request->order_status == 1) || ($request->order_status)) {
            if($driver->is_suspended){
                return response()->json(['message' => 'ok']);
            }
            else{
                $driver->update([
                    'order_status' => Driver::AVAILABLE_STATUS
                ]);
            }
            
        } else {
            $driver->update([
                'order_status' => Driver::UNAVAILABLE_STATUS
            ]);
        }

        if ($driver->location) {
            $driver->location->update([
                'latitude' => $request->lat,
                'longitude' => $request->long,
            ]);
        } else {
            $driver->location()->create([
                'latitude' => $request->lat,
                'longitude' => $request->long,
            ]);
        }

        return response()->json(['message' => 'ok']);
    }

    public function notifyPanic(Request $request)
    {
        $request->validate([
            'location_latitude' => 'required|numeric', // location latitude
            'location_longitude' => 'required|numeric', // location longitude
            'location_name' => 'required|string|max:255',
        ]);

        $newUserPanic = Panic::create([
            'latitude' => $request->location_latitude,
            'longitude' => $request->location_longitude,
            'location_name' => $request->location_name,
            'user_id' => Auth::id()
        ]);

        if ($newUserPanic) {
            event(new PanicCreated($newUserPanic));
        }

        return response()->json(['message' => 'ok']);
    }

    public function uploadPhotoDriver(Request $request)
    {
        $request->validate([
            'location_latitude' => 'required|numeric', // location latitude
            'location_longitude' => 'required|numeric', // location longitude
            'location_name' => 'required|string|max:255',
            'photo' => 'required|image|max:15120'
        ]);

        $driver = $this->getCurrentDriver();

        DriverPhoto::create([
            'driver_id' => $driver->id,
            'photo' => $request->file('photo')->store('driver/'.$driver->id.'/presence'),
            'latitude' => $request->location_latitude,
            'longitude' => $request->location_longitude,
            'location_name' => $request->location_name
        ]);

        return response()->json(['message' => 'ok']);
    }
}
