<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotFoundController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'not found.'], 404);
        } else {
            return response()->view('errors.404',array(),404);
        }
        
    }
}
