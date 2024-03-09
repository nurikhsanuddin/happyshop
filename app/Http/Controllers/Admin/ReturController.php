<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\product_retur;
use App\Models\retur;
use Illuminate\Support\Facades\DB;

class ReturController extends Controller
{
    //
    public function index()
    {
        $returs = retur::all();
        $products = Product::all();
        return view('admin.retur.index', compact('returs','products'));
    }
    public function show(Request $request)
    {
        $returs = retur::find($request->id);
        $product_retur = product_retur::where('returs_id', $returs->id)->get();
        return view('admin.retur.show', compact('returs','product_retur'));
    }
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $retur = retur::create([
                'user_id' => auth()->user()->id,
                'supplier_name' => $request->supplier_name,
                'supply_date' => $request->supply_date,
            ]);
            for($i = 0; $i < count($request->product_id); $i++){
                $produk = Product::find($request->product_id[$i]);
                $result = $produk->quantity - $request->quantity[$i];
                $produk->update(['quantity' => $result]);
                product_retur::create([
                    'returs_id' => $retur->id,
                    'product_id' => $request->product_id[$i],
                    'quantity' => $request->quantity[$i]
                ]);
            }
            DB::commit();
            toast('Data Retur berhasil ditambahkan')->autoClose(2000)->hideCloseButton();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            toast('Gagal menambah data retur')->autoClose(2000)->hideCloseButton();
            return redirect()->back();
        }
       
    }
}
