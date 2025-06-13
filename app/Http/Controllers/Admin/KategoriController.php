<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller {
    public function index() {
        $kategori = Kategori::all();

        return view('admin.kategori.index', compact('kategori'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:kategori,nama_kategori'
        ]);

        Kategori::create($validated);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }
    public function create() {
        return view('admin.kategori.create');
    }

    public function show(Kategori $kategori) {

        return view('admin.kategori.show', compact('kategori'));
    }


    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }
    public function update(Request $request, Kategori $kategori) {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:kategori,nama_kategori,' . $kategori->kategori_id . ',kategori_id'
        ]);

        $kategori->update($validated);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori) {
        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
