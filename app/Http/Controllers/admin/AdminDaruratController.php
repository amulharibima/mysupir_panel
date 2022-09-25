<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Panic;

class AdminDaruratController extends Controller
{
    public function index(){
        $panics = Panic::orderBy('status', 'asc')->get();

        return view('admin.darurat.index', compact('panics'));
    }

    public function verify(Request $request) {
        $panic = Panic::findOrFail($request->input('panic_id'));
        $panic->status = true;
        $panic->save();

        return redirect()->route('admin.darurat');
    }
}
