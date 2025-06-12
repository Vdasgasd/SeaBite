<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\KategoriMenu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with(['kategori', 'ikan']);

        if ($request->has('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->has('search')) {
            $query->where('nama_menu', 'like', '%' . $request->search . '%');
        }

        $menus = $query->get();
        return response()->json($menus);
    }

    public function show(Menu $menu)
    {
        return response()->json($menu->load(['kategori', 'ikan', 'hargaBeratTiers']));
    }

    public function kategori()
    {
        $kategori = KategoriMenu::all();
        return response()->json($kategori);
    }
}
