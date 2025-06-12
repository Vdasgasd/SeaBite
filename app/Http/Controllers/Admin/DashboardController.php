<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


// Import model yang relevan
use App\Models\Invoice;
use App\Models\Menu;
use App\Models\Ikan;
use App\Models\Meja;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Ambil data statistik
        $totalMenu = Menu::count();
        $totalIkan = Ikan::count();
        $totalMeja = Meja::count();
        $totalUser = User::count();
        $totalInvoice = Invoice::count();

        return view('admin.dashboard', [
            'user'       => $user,
            'totalMenu'  => $totalMenu,
            'totalIkan'  => $totalIkan,
            'totalMeja'  => $totalMeja,
            'totalUser'  => $totalUser,
            'totalInvoice' => $totalInvoice,
        ]);
    }
}
