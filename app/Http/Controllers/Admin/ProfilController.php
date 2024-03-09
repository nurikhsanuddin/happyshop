<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $user = User::find(auth()->user()->id);
        return view('admin.profile.index',compact('user'));
    }
    public function update(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if (!(Hash::check($request->old_password, $user->password))) {
            toast('Kata sandi Anda saat ini tidak cocok dengan kata sandi yang Anda berikan. Silakan coba lagi.')->autoClose(2000)->hideCloseButton();
            return redirect()->back();
        }

        $validatedData = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|string',
            'confirmation_password' => ['same:new_password'],
        ]);
        $user->password = bcrypt($request->new_password);
        $user->save();

        toast('Password berhasil diubah')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }
}
