<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CashierController extends Controller
{
    public function index()
    {
        $cashiers = User::where('role','cashier')->get();
        return view('admin.cashier.index', compact('cashiers'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|unique:users',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'cashier',
        ]);
        toast('Data Kasir berhasil ditambahkan')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|unique:users,email,'. $request->id,
        ]);
        $user = User::find($request->id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        toast('Data Kasir berhasil diubah')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }
    public function changePassword(Request $request)
    {
        $user = User::find($request->id);
        if (!(Hash::check($request->current_password, $user->password))) {
            return redirect()->back()->with("error","Kata sandi Anda saat ini tidak cocok dengan kata sandi yang Anda berikan. Silakan coba lagi.");
        }

        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|confirmed',
        ]);
        $user->password = bcrypt($request->new_password);
        $user->save();

        toast('Password berhasil diubah')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }
    public function delete(Request $request)
    {
        $user = User::find($request->id);
        $user->delete();
        
        toast('Data Kasir berhasil dihapus')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }
}
