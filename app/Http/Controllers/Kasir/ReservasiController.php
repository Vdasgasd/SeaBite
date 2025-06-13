<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function index()
    {
        $reservasi = Reservasi::with('meja')
            ->orderBy('waktu_reservasi', 'desc')
            ->paginate(15);

        return view('kasir.reservasi.index', compact('reservasi'));
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

        return redirect()->route('kasir.reservasi.show', $reservasi)
            ->with('success', 'Reservasi berhasil dibuat');
    }

    public function show(Reservasi $reservasi)
    {
        $reservasi->load('meja');
        return view('kasir.reservasi.show', compact('reservasi'));
    }

    public function update(Request $request, Reservasi $reservasi)
    {
        $validated = $request->validate([
            'status' => 'required|in:dikonfirmasi,hadir,batal'
        ]);

        $reservasi->update($validated);

        return redirect()->route('kasir.reservasi.show', $reservasi)
            ->with('success', 'Status reservasi berhasil diupdate');
    }

    public function destroy(Reservasi $reservasi)
    {
        $reservasi->delete();

        return redirect()->route('kasir.reservasi.index')
            ->with('success', 'Reservasi berhasil dihapus');
    }
}
