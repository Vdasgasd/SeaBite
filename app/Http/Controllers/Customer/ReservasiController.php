<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Models\Meja;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function index()
    {
        // Customer hanya bisa melihat reservasi mereka sendiri
        // Asumsi: ada field user_id di tabel reservasi atau identifikasi lainnya
        $reservasi = Reservasi::with('meja')->get(); // Sesuaikan dengan kebutuhan
        return response()->json($reservasi);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'meja_id' => 'required|exists:meja,meja_id',
            'nama_pelanggan' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
            'waktu_reservasi' => 'required|date|after:now',
            'jumlah_tamu' => 'required|integer|min:1'
        ]);

        // Cek apakah meja tersedia pada waktu yang diminta
        $meja = Meja::findOrFail($validated['meja_id']);
        if ($validated['jumlah_tamu'] > $meja->kapasitas) {
            return response()->json(['error' => 'Jumlah tamu melebihi kapasitas meja'], 400);
        }

        // Cek konflik reservasi (implementasi sederhana)
        $conflictReservasi = Reservasi::where('meja_id', $validated['meja_id'])
            ->where('waktu_reservasi', $validated['waktu_reservasi'])
            ->where('status', '!=', 'batal')
            ->exists();

        if ($conflictReservasi) {
            return response()->json(['error' => 'Meja sudah direservasi pada waktu tersebut'], 400);
        }

        $reservasi = Reservasi::create($validated);
        return response()->json($reservasi->load('meja'), 201);
    }

    public function show(Reservasi $reservasi)
    {
        return response()->json($reservasi->load('meja'));
    }

    public function availableTables(Request $request)
    {
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

        return response()->json($availableTables);
    }
}
