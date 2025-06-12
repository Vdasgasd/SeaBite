<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ikan;
use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with(['kategori', 'ikan'])->get();
        // Mengirim data ke view 'admin.menu.index'
        return view('admin.menu.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_menu' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'required|exists:kategori,kategori_id',
            'ikan_id' => 'nullable|exists:ikan,ikan_id',
            'tipe_harga' => 'required|in:satuan,berat',
            'harga' => 'nullable|numeric|min:0',
            'harga_per_100gr' => 'nullable|numeric|min:0',
            'gambar_url' => 'nullable|string|max:255'
        ]);

        Menu::create($validated);
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function show(Menu $menu)
    {
        // Memuat relasi dan mengirim data ke view 'admin.menu.show'
        $menu->load(['kategori', 'ikan']);
        return view('admin.menu.show', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'nama_menu' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'required|exists:kategori,kategori_id',
            'ikan_id' => 'nullable|exists:ikan,ikan_id',
            'tipe_harga' => 'required|in:satuan,berat',
            'harga' => 'nullable|numeric|min:0',
            'harga_per_100gr' => 'nullable|numeric|min:0',
            'gambar_url' => 'nullable|string|max:255'
        ]);

        $menu->update($validated);
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui.');
    }

      public function create()
    {
        $kategoris = Kategori::all();
        $ikans = Ikan::all();
        return view('admin.menu.create', compact('kategoris', 'ikans'));
    }

    public function edit(Menu $menu)
    {
        $kategoris = Kategori::all();
        $ikans = Ikan::all();
        return view('admin.menu.edit', compact('menu', 'kategoris', 'ikans'));
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus.');
    }
}
