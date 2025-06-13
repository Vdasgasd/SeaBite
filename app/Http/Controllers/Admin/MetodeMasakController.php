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

        return view('admin.metode-masak.index', compact('metode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_metode' => 'required|string|max:50',
            'biaya_tambahan' => 'required|numeric|min:0'
        ]);

        MetodeMasak::create($validated);

        return redirect()->route('admin.metode-masak.index')->with('success', 'Metode masak berhasil ditambahkan.');
    }

    public function show(MetodeMasak $metode)
    {

        return view('admin.metode-masak.show', compact('metode'));
    }

    public function edit(MetodeMasak $metode){
        return view('admin.metode-masak.edit', compact('metode'));
    }
    public function update(Request $request, MetodeMasak $metode)
    {
        $validated = $request->validate([
            'nama_metode' => 'required|string|max:50',
            'biaya_tambahan' => 'required|numeric|min:0'
        ]);

        $metode->update($validated);

        return redirect()->route('admin.metode-masak.index')->with('success', 'Metode masak berhasil diperbarui.');
    }

      public function create(Request $request){
        return view('admin.metode-masak.create');
    }

    public function destroy(MetodeMasak $metode)
    {
        $metode->delete();

        return redirect()->route('admin.metode-masak.index')->with('success', 'Metode masak berhasil dihapus.');
    }
}
