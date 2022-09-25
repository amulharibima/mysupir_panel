<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Order;
use App\Panic;

class AdminPenggunaController extends Controller
{
    public function index(){
        $users = array();
        $user = User::all();
        foreach ($user as $user) {
            if ($user->hasRole('user')) {
                array_push($users, $user);
            }
        }

        return view('admin.pengguna.index', compact('users'));
    }

    public function detail($id){
        $user = User::findOrFail($id);
        $panic = Panic::where('user_id', $user->id)->count();
        $order = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $orders = array();
        foreach ($order as $order) {
            if ($order->isHasDriver()) {
                array_push($orders, $order);
            }
        }
        return view('admin.pengguna.detail', compact('user', 'orders', 'panic'));
    }
}
