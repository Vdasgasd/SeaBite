<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Http\Request;

class MenuController extends Controller {
    public function index(Request $request) {
        $query = Menu::query();

        if ($request->has('search')) {
            $query->where('nama_menu', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('nama_kategori', $request->kategori);
            });
        }

        $menus = $query->paginate(9)->withQueryString(); // ⬅️ penting: paginate di-query, bukan di-collection
        $kategoris = Kategori::all();

        return view('menu', compact('menus', 'kategoris'));
    }
}
