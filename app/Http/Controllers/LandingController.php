<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Http\Request;

class LandingController extends Controller
{
     public function landing()
    {
        $menus = Menu::with('kategori')->take(6)->get();
        $kategoris = Kategori::all();

        return view('landing', compact('menus', 'kategoris'));
    }
}
