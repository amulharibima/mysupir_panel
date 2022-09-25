<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminMasterDataController extends Controller
{
    //View Kebijakan Privasi
    public function kebijakan(){
        return view('admin.masterdata.kebijakan');
    }

    //Form Create Kebijakan Privasi
    public function showAddKebijakan(){
        return view('admin.masterdata.addkebijakan');
    }

    //Form Edit Kebijakan Privasi
    public function showEditKebijakan(){
        return view('admin.masterdata.editkebijakan');
    }

    //View Syarat Ketentuan
    public function syarat(){
        return view('admin.masterdata.syarat');
    }

    //Form Create Syarat Ketentuan
    public function showAddSyarat(){
        return view('admin.masterdata.addsyarat');
    }

    //Form Edit Syarat Ketentuan
    public function showEditSyarat(){
        return view('admin.masterdata.editsyarat');
    }

    //View Tarif
    public function tarif(){
        return view('admin.masterdata.tarif');
    }

    //Form Create Tarif
    public function showAddTarif(){
        return view('admin.masterdata.addtarif');
    }

    //Form Edit Tarif
    public function showEditTarif(){
        return view('admin.masterdata.edittarif');
    }
}
