<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ikan;
use App\Models\Meja;
use App\Models\Menu;


use App\Models\User;
use App\Models\Invoice;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {

    public function index(Request $request) {
        $user = $request->user();

        $totalMenu = Menu::count();
        $totalIkan = Ikan::count();
        $totalMeja = Meja::count();
        $totalUser = User::count();
        $totalInvoice = Invoice::count();

        $totalReservasi = Reservasi::count();

        return view('admin.dashboard', [
            'user' => $user,
            'totalMenu' => $totalMenu,
            'totalIkan' => $totalIkan,
            'totalMeja' => $totalMeja,
            'totalUser' => $totalUser,
            'totalInvoice' => $totalInvoice,
            'totalReservasi' => $totalReservasi,
        ]);
    }
}
