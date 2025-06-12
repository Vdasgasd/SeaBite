<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ikan;
use Illuminate\Http\Request;

class IkanController extends Controller
{
    public function index()
    {
        $ikan = Ikan::all();
        return view('admin.ikan.index', compact('ikan'));
    }

    public function create()
    {
        return view('admin.ikan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ikan' => 'required|string|max:100|unique:ikan,nama_ikan',
            'stok_gram' => 'required|numeric|min:0'
        ]);

        Ikan::create($validated);

        return redirect()->route('admin.ikan.index')->with('success', 'Data ikan berhasil ditambahkan.');
    }

    public function show(Ikan $ikan)
    {
        return view('admin.ikan.show', compact('ikan'));
    }

    public function edit(Ikan $ikan)
    {
        return view('admin.ikan.edit', compact('ikan'));
    }

    public function update(Request $request, Ikan $ikan)
    {
        $validated = $request->validate([
            'nama_ikan' => 'required|string|max:100|unique:ikan,nama_ikan,' . $ikan->ikan_id . ',ikan_id',
            'stok_gram' => 'required|numeric|min:0'
        ]);

        $ikan->update($validated);

        return redirect()->route('admin.ikan.index')->with('success', 'Ikan berhasil diperbarui.');
    }

    public function destroy(Ikan $ikan)
    {
        $ikan->delete();

        return redirect()->route('admin.ikan.index')->with('success', 'Ikan berhasil dihapus.');
    }
}
