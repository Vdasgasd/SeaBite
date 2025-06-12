<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetodeMasak;
use Illuminate\Http\Request;

class MetodeMasakController extends Controller
{
    public function index()
    {
        $metode = MetodeMasak::all();
        // Mengirim data ke view 'admin.metode-masak.index'
        return view('admin.metode-masak.index', compact('metode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_metode' => 'required|string|max:50',
            'biaya_tambahan' => 'required|numeric|min:0'
        ]);

        MetodeMasak::create($validated);
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.metode-masak.index')->with('success', 'Metode masak berhasil ditambahkan.');
    }

    public function show(MetodeMasak $metode)
    {
        // Menampilkan detail di view 'admin.metode-masak.show'
        return view('admin.metode-masak.show', compact('metode'));
    }

    public function edit(MetodeMasak $metode){
        return view('admin.metode-masak', compact('metode'));
    }
    public function update(Request $request, MetodeMasak $metode)
    {
        $validated = $request->validate([
            'nama_metode' => 'required|string|max:50',
            'biaya_tambahan' => 'required|numeric|min:0'
        ]);

        $metode->update($validated);
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.metode-masak.index')->with('success', 'Metode masak berhasil diperbarui.');
    }

      public function create(Request $request){
        return view('admin.metode-masak.create');
    }

    public function destroy(MetodeMasak $metode)
    {
        $metode->delete();
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.metode-masak.index')->with('success', 'Metode masak berhasil dihapus.');
    }
}
