<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:30|unique:users,username',
            'name' => 'required|string|max:100',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,kitchen,kasir,cust'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan.');
    }


    public function create(){
        return view('admin.user.create');
    }

    public function edit(User $user){
        return view('admin.user.edit', compact('user'));

    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:30|unique:users,username,' . $user->id,
            'name' => 'required|string|max:100',
            'role' => 'required|in:admin,kitchen,kasir,cust'
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);
        return redirect()->route('admin.user.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus.');
    }
}
