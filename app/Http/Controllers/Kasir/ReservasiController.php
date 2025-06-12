<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function index()
    {
        $reservasi = Reservasi::with('meja')->get();
        return response()->json($reservasi);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'meja_id' => 'required|exists:meja,meja_id',
            'nama_pelanggan' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
            'waktu_reservasi' => 'required|date',
            'jumlah_tamu' => 'required|integer|min:1'
        ]);

        $reservasi = Reservasi::create($validated);
        return response()->json($reservasi->load('meja'), 201);
    }

    public function show(Reservasi $reservasi)
    {
        return response()->json($reservasi->load('meja'));
    }

    public function update(Request $request, Reservasi $reservasi)
    {
        $validated = $request->validate([
            'status' => 'required|in:dikonfirmasi,hadir,batal'
        ]);

        $reservasi->update($validated);
        return response()->json($reservasi);
    }

    public function destroy(Reservasi $reservasi)
    {
        $reservasi->delete();
        return response()->json(['message' => 'Reservasi deleted successfully']);
    }
}
