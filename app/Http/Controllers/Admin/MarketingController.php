<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Chart;
use App\Models\Marketing;

class MarketingController extends Controller
{
    //
    public function index()
    {
        $marketings = Marketing::all();
        $products = Product::all();
        return view('admin.marketing.index', compact('marketings','products'));
    }
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $marketing = Marketing::create([
                'user_id' => auth()->user()->id,
                'customer_name' => $request->customer_name,
                'beli_date' => $request->beli_date,
                'total_price' => $request->total_price,
            ]);
            for($i = 0; $i < count($request->product_id); $i++){
                $produk = Product::find($request->product_id[$i]);
                $result = $produk->quantity + $request->quantity[$i];
                $produk->update(['quantity' => $result]);
                Marketing::create([
                    'marketing_id' => $marketing->id,
                    'product_id' => $request->product_id[$i],
                    'quantity' => $request->quantity[$i]
                ]);
            }
            DB::commit();
            toast('Data Pasok berhasil ditambahkan')->autoClose(2000)->hideCloseButton();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            toast('Gagal menambah data pasok')->autoClose(2000)->hideCloseButton();
            return redirect()->back();
        }
       
    }
    public function delete(Request $request)
    {
        $supply = Supply::find($request->id);
        $supply->delete();
        return redirect()->back()->with('success','Berhasil menghapus data pasok');
    }
    public function update(Request $request)
    {
        $supply = Supply::find($request->id);
        $supply->update($request->all());
        return redirect()->back()->with('success','Berhasil mengubah data pasok');
    }
    
    public function show(Request $request)
    {
        $supply = Supply::find($request->id);
        $product_supplies = ProductSupply::where('supply_id', $supply->id)->get();
        return view('admin.supply.show', compact('supply','product_supplies'));
    }
}
