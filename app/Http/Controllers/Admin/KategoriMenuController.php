<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriMenu;
use Illuminate\Http\Request;

class KategoriMenuController extends Controller {
    public function index() {
        $kategori = KategoriMenu::all();
        // Mengirim data ke view 'admin.kategori-menu.index'
        return view('admin.kategori-menu.index', compact('kategori'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:kategori_menu,nama_kategori'
        ]);

        KategoriMenu::create($validated);
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.kategori-menu.index')->with('success', 'Kategori berhasil ditambahkan.');
    }
    public function create() {
        return view('admin.kategori-menu.create');
    }

    public function show(KategoriMenu $kategori) {
        // Menampilkan detail di view 'admin.kategori-menu.show'
        return view('admin.kategori-menu.show', compact('kategori'));
    }


    public function edit(KategoriMenu $kategori)
    {
        return view('admin.kategori-menu', compact('kategori'));
    }
    public function update(Request $request, KategoriMenu $kategori) {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:kategori_menu,nama_kategori,' . $kategori->kategori_id . ',kategori_id'
        ]);

        $kategori->update($validated);
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.kategori-menu.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriMenu $kategori) {
        $kategori->delete();
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.kategori-menu.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
