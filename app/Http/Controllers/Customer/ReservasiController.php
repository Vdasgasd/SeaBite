<?php

namespace App\Http\Controllers\Customer;

use App\Models\Meja;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReservasiController extends Controller {
    public function index() {
         $reservasi = Reservasi::with('meja')
        ->where('user_id', Auth::id())
        ->get();
        return view('customer.reservasi.index', compact('reservasi'));
    }

    public function create() {
        $meja = Meja::where('status', 'tersedia')->get();
        return view('customer.reservasi.create', compact('meja'));
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'meja_id' => 'required|exists:meja,meja_id',
            'nama_pelanggan' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
            'waktu_reservasi' => 'required|date|after:now',
            'jumlah_tamu' => 'required|integer|min:1'
        ]);

        $meja = Meja::findOrFail($validated['meja_id']);
        if ($validated['jumlah_tamu'] > $meja->kapasitas) {
            return back()->withErrors(['jumlah_tamu' => 'Jumlah tamu melebihi kapasitas meja'])->withInput();
        }

        $conflictReservasi = Reservasi::where('meja_id', $validated['meja_id'])
            ->where('waktu_reservasi', $validated['waktu_reservasi'])
            ->where('status', '!=', 'batal')
            ->exists();

        $validated['user_id'] = Auth::id();

        if ($conflictReservasi) {
            return back()->withErrors(['meja_id' => 'Meja sudah direservasi pada waktu tersebut'])->withInput();
        }

        $reservasi = Reservasi::create($validated);
        return redirect()->route('customer.reservasi.index')->with('success', 'Reservasi berhasil dibuat');
    }

    public function show(Reservasi $reservasi) {
        return view('customer.reservasi.show', compact('reservasi'));
    }

    public function availableTables(Request $request) {
        $validated = $request->validate([
            'waktu_reservasi' => 'required|date',
            'jumlah_tamu' => 'required|integer|min:1'
        ]);

        $reservedTables = Reservasi::where('waktu_reservasi', $validated['waktu_reservasi'])
            ->where('status', '!=', 'batal')
            ->pluck('meja_id');

        $availableTables = Meja::where('kapasitas', '>=', $validated['jumlah_tamu'])
            ->whereNotIn('meja_id', $reservedTables)
            ->where('status', 'tersedia')
            ->get();

        return view('customer.reservasi.available', [
            'availableTables' => $availableTables,
            'waktu_reservasi' => $validated['waktu_reservasi'],
            'jumlah_tamu' => $validated['jumlah_tamu'],
        ]);
    }
}
