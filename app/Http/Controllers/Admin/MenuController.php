<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ikan;
use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with(['kategori', 'ikan'])->get();
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        $ikans = Ikan::all();
        return view('admin.menu.create', compact('kategoris', 'ikans'));
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
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_url' => 'nullable|url',
        ]);

        // Prioritaskan upload file
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('menu', 'public');
            $validated['gambar_url'] = $path;
        }

        Menu::create($validated);
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        $kategoris = Kategori::all();
        $ikans = Ikan::all();
        return view('admin.menu.edit', compact('menu', 'kategoris', 'ikans'));
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
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_url' => 'nullable|url',
        ]);

        // Cek apakah ada file gambar di-upload
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('menu', 'public');
            $validated['gambar_url'] = $path;
        }

        $menu->update($validated);
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus.');
    }
}
