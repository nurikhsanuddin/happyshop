<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $user = User::find(auth()->user()->id);
        return view('kasir.profile.index',compact('user'));
    }
    public function update(Request $request)
    {
        dd($request->all());
        $user = User::find(auth()->user()->id);
        if (!(Hash::check($request->old_password, $user->password))) {
            return redirect()->back()->with('failed','Password lama yang Anda berikan salah. Silakan coba lagi.');
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
