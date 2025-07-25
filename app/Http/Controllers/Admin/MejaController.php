<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use App\Models\User;
use Illuminate\Http\Request;

class MejaController extends Controller {

    public function index() {
        $meja = Meja::all();
        $users = User::all();
        return view('admin.meja.index', compact('meja', 'users'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'nomor_meja' => 'required|string|max:10|unique:meja,nomor_meja',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,terisi,direservasi'
        ]);

        Meja::create($validated);
        return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil ditambahkan.');
    }


    public function create(Request $request) {
        return view('admin.meja.create');
    }

    public function edit(Meja $meja)
    {
        return view('admin.meja.edit', compact('meja'));
    }

    public function update(Request $request, Meja $meja) {
        $validated = $request->validate([
            'nomor_meja' => 'required|string|max:10|unique:meja,nomor_meja,' . $meja->meja_id . ',meja_id',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,terisi,direservasi'
        ]);

        $meja->update($validated);
        return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(Meja $meja) {
        $meja->delete();
        return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil dihapus.');
    }
}
