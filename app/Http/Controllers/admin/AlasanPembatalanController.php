<?php

namespace App\Http\Controllers\admin;

use App\AlasanPembatalan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AlasanPembatalanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cancels = AlasanPembatalan::all();
        return view('admin.cancels.index', compact('cancels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cancels.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return dd($request);
        AlasanPembatalan::create(['alasan' => $request->nama],);
        return redirect()->route('admin.cancels');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AlasanPembatalan  $alasanPembatalan
     * @return \Illuminate\Http\Response
     */
    public function show(AlasanPembatalan $alasanPembatalan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AlasanPembatalan  $alasanPembatalan
     * @return \Illuminate\Http\Response
     */
    public function edit(AlasanPembatalan $alasanPembatalan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AlasanPembatalan  $alasanPembatalan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AlasanPembatalan $alasanPembatalan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AlasanPembatalan  $alasanPembatalan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $cancel = AlasanPembatalan::findOrFail($request->id);
        $cancel->delete();
        return redirect()->route('admin.cancels');
    }
}
