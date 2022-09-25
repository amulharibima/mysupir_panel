<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\DriverLicense;
use App\CarType;

class AdminTipeController extends Controller
{
    public function index() {
        $cartype = CarType::orderBy('created_at', 'desc')->get();
        return view('admin.tipe_mobil.index', compact('cartype'));
    }
    public function showAddView() {
        return view('admin.tipe_mobil.add');
    }

    public function add(Request $request) {
        $messages = [
            'sim_type.required' => 'Minimal satu tipe SIM harus dipilih.',
            'image' => 'Berkas yang dimasukkan harus berupa gambar.',
        ];

        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:255',
            'sim_type' => 'required',
            'foto' => 'nullable|image|max:15120'
        ], $messages);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $license = DriverLicense::where('name', $request->sim_type[0])->first();
        $cartype = new CarType();
        $cartype->name = $request->nama;
        $cartype->driver_license_id = $license->id;
        $cartype->save();
        if ($request->file('foto')) {
            $foto_path = $request->file('foto')->store('cartype/foto');
            if ($foto_path) {
                $old_foto_path = $cartype->foto;
    
                $cartype->update([
                    'foto' => $foto_path
                ]);
    
                if ($cartype->wasChanged('foto')) {
                    if (Storage::exists($old_foto_path)) {
                        Storage::delete($old_foto_path);
                    }
                }
            }
        }

        return redirect()->route('admin.tipe');
    }

    public function destroy($id){
        $cartype = CarType::findOrFail($id);
        Storage::delete($cartype->foto);
        $cartype->delete();

        return redirect()->route('admin.tipe');
    }
}
