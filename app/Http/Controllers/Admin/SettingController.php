<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $company = Company::take(1)->first();
        return view('admin.setting.index', compact('company'));
    }

    public function update(Request $request)
    {
        $company = Company::find(1);
        $company->update($request->all());
        toast('Data usaha berhasil diubah')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }
}
