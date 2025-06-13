<?php

namespace App\Http\Controllers\Admin;

use App\Models\Reservasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReservasiController extends Controller {
    public function index() {
        $reservasi = Reservasi::with('meja')
            ->orderBy('waktu_reservasi', 'desc')
            ->paginate(15);

        return view('admin.reservasi.index', compact('reservasi'));
    }

    public function show(Reservasi $reservasi) {
        $reservasi->load('meja');
        return view('admin.reservasi.show', compact('reservasi'));
    }

    public function update(Request $request, Reservasi $reservasi) {
        $validated = $request->validate([
            'status' => 'required|in:pending,dikonfirmasi,hadir,batal'
        ]);

        $reservasi->update($validated);

        // Cek dan update status meja
        $meja = $reservasi->meja;

        if ($meja) {
            if ($validated['status'] === 'dikonfirmasi') {
                $meja->status = 'direservasi';
                $meja->save();
            } elseif ($validated['status'] === 'batal') {
                $meja->status = 'tersedia';
                $meja->save();
            }
        }

        return redirect()->route('admin.reservasi.show', $reservasi)
            ->with('success', 'Status reservasi berhasil diupdate');
    }


    public function destroy(Reservasi $reservasi) {
        $reservasi->delete();

        return redirect()->route('admin.reservasi.index')
            ->with('success', 'Reservasi berhasil dihapus');
    }
}
