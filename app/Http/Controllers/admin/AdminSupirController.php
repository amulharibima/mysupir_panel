<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Driver;
use App\User;
use App\DriverLicense;
use App\Earning;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\DriverPhoto;
use App\Kota;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminSupirController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $driver = Driver::all();
        return view('admin.driver.index', compact('driver'));
    }

    public function locations(){
        $driver = Driver::where('order_status', Driver::AVAILABLE_STATUS)->with('location')->get();
        return view('admin.driver.locations', compact('driver'));
    }

    public function detail($id) {
        $driver = Driver::findOrFail($id);
        return view('admin.driver.detail', compact('driver'));
    }

    public function showAddView(){
        $kota = Kota::all();
        return view('admin.driver.add', compact('kota'));
    }

    public function suspend(Request $request, $id){
        $driver = Driver::find($id);
        
        if(!$driver->is_suspended)
            $driver->alasan_suspend = $request->alasan_suspend;
        else
            $driver->alasan_suspend = null;

        $driver->is_suspended = !$driver->is_suspended;
        
        $driver->save();

        return redirect()->back();
    }

    public function showEditView($id) {
        $driver = Driver::find($id);
        $kota = Kota::all();

        $licenses = array();
        foreach ($driver->licenses()->get() as $license) {
            if ($driver->licenses()->count() > 0) {
                array_push($licenses, $license->name);
            }
        }

        // return dd(in_array('sim a', $licenses));
        return view('admin.driver.edit', compact('driver', 'licenses', 'kota'));
    }

    public function add(Request $request) {
        //make phone number from (+62) 8XX-XXXX-XXXX to 8XXXXXXXXX
        $regex = explode(' ', $request->input('phone_number'));
        $phone_arr = explode('-', $regex[1]);
        $phone = implode("", $phone_arr);
        $request['phone_number'] = $phone;

        $messages = [
            'sim_type.required' => 'Minimal satu tipe SIM harus dipilih.',
            'phone_number.unique' => 'Nomor telepon sudah dipakai.',
            'email.unique' => 'Email sudah dipakai.',
            'kota.required' => 'Kota harus diisi.',
            'image' => 'Berkas yang dimasukkan harus berupa gambar.',
        ];

        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:255',
            'phone_number' => 'required|unique:users',
            'sim_type' => 'required',
            'email' => 'required|email|unique:users',
            'foto' => 'nullable|image|max:5120',
            'kota' => 'required'
        ], $messages);

        if ($validator->fails()) {
            // return dd($validator->messages()->get('*'));
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        //initialize driver_licenses from input field
        $licenses = array();
        foreach ($request->input('sim_type') as $sim) {
            $driver_license = DriverLicense::where('name', $sim)->first();
            array_push($licenses, $driver_license);
        }

        $kota = Kota::findOrFail($request->kota);

        $user = User::create([
            'phone_number' => $phone,
            'name' => $request->input('nama'),
            'email' => $request->input('email'),
        ]);

        $user->assignRole('driver');    

        $driver = Driver::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'address' => $request->input('alamat'),
            'user_id' => $user->id,
            'identifier' => $kota->kode.'-'.Str::random(4).'-'.Carbon::now()->format('Y')
        ]);

        if ($driver && $licenses) {
            foreach ($licenses as $license) {
                $driver->licenses()->sync($license, false);
            }

            // initialize driver's earning
            Earning::create([
                'driver_id' => $driver->id,
                'amount' => 0
            ]);
        }       

        if ($request->file('foto')) {
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
        }

        // return compact('license');
        // return dd($driver);
        return redirect()->route('admin.supir');
    }

    public function edit(Request $request, $id){
        //make phone number from (+62) 8XX-XXXX-XXXX to 8XXXXXXXXX
        $regex = explode(' ', $request->input('phone_number'));
        $phone_arr = explode('-', $regex[1]);
        $phone = implode("", $phone_arr);
        $request['phone_number'] = $phone;
        $driver = Driver::find($id);
        $user = User::find($driver->user_id);

        $messages = [
            'sim_type.required' => 'Minimal satu tipe SIM harus dipilih.',
            'phone_number.unique' => 'Nomor telepon sudah dipakai.',
            'email.unique' => 'Email sudah dipakai.',
            'image' => 'Berkas yang dimasukkan harus berupa gambar.',
        ];

        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:255',
            'phone_number' => ['required', Rule::unique('users')->ignore($user->id)],
            'sim_type' => 'required',
            'email' => ['required','email', Rule::unique('users')->ignore($user->id)],
            'foto' => 'nullable|image|max:5120'
        ], $messages);

        if ($validator->fails()) {
            // return dd($validator->messages()->get('*'));
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        //initialize driver_licenses from input field
        $kota = Kota::findOrFail($request->kota);
        $licenses = array();
        foreach ($request->input('sim_type') as $sim) {
            $driver_license = DriverLicense::where('name', $sim)->first();
            array_push($licenses, $driver_license);
        }
        $driver->licenses()->detach();
        foreach ($licenses as $license) {
            $driver->licenses()->sync($license, false);
        }

        $driver->update([
            'name' => $request->input('nama'),
            'email' => $request->input('email'),
            'phone_number' => $phone,
            'address' => $request->input('alamat'),
            'identifier' => $kota->kode.'-'.Str::random(4).'-'.Carbon::now()->format('Y')
        ]);

        $driver->user->update([
            'phone_number' => $driver->phone_number,
            'name' => $driver->name,
            'email' => $driver->email,
        ]);

        if ($request->file('foto')) {
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
        }

        // return dd('berhasil');
        return redirect()->route('admin.supir');
    }

    public function destroy($id){
        $driver = Driver::findOrFail($id);
        $driver->delete();

        return redirect()->route('admin.supir');
    }

    public function history() {
        $presences = DriverPhoto::orderBy('created_at', 'desc')->get();
        // return dd($presences);
        return view('admin.driver.histori', compact('presences'));
    }
}
