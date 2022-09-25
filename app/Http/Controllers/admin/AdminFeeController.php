<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Fee;

class AdminFeeController extends Controller
{
    public function index(){
        $fee = Fee::find(1);

        return view('admin.fee.index', compact('fee'));
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'percentage_amount' => 'required',
        ], [
            'required' => ':attribute harus diisi'
        ], [
            'percentage_amount' => 'Persentase (%) dari harga',
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        Fee::find(1)->update($validator->validated());

        return redirect()->back()->withSuccess('Berhasil ubah fee!');
    }
}
