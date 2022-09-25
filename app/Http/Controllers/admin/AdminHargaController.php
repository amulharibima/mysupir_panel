<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Price;

class AdminHargaController extends Controller
{
    public function index(){
        $price = Price::find(1);

        return view('admin.harga.index', compact('price'));
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'price_per_km' => 'required',
            'default_price' => 'required'
        ], [
            'required' => ':attribute harus diisi'
        ], [
            'price_per_km' => 'Harga per KM',
            'default_price' => 'Harga Default',
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        Price::find(1)->update($validator->validated());

        return redirect()->back()->withSuccess('Berhasil ubah harga!');
    }
}
