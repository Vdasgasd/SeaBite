<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request){
        // return response()->json(['Mesages' => 'Dashboard Kasir']);
        return view('kasir.dashboard',[
             'user' => $request->user()]);
    }
}
