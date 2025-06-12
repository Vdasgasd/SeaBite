<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request){
        // return response()->json(['Mesages' => 'Dashboard Customer']);
        return view('customer.dashboard',[
             'user' => $request->user()]);
    }
}
