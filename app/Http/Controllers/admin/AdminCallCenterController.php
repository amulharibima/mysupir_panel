<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CallCenter;

class AdminCallCenterController extends Controller
{
    public function index(){
        $call_center = CallCenter::find(1);

        return view('admin.call_center.index', compact('call_center'));
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'call_center' => 'required',
        ], [
            'required' => ':attribute harus diisi'
        ], [
            'call_center' => 'Nomor Call Center',
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        CallCenter::find(1)->update($validator->validated());

        return redirect()->back()->withSuccess('Berhasil ubah nomor call center!');
    }
}
