<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $categories = Category::all();
        return view('admin.product.index', compact('products','categories'));
    }
    public function store(Request $request)
    {
        $product = Product::count();
        $validatedData = $request->validate([
            'product_code' => 'required|unique:product'
        ]);
        Product::create($request->all());
        toast('Data produk berhasil ditambah')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'product_code' => 'required|unique:product,product_code,'. $request->id,
        ]);
        $product = Product::find($request->id);
        $product->update($request->all());
        toast('Data produk berhasil diubah')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }
    public function delete(Request $request)
    {
        $product = Product::find($request->id);
        toast('Data produk berhasil dihapus')->autoClose(2000)->hideCloseButton();
        $product->delete();
        return redirect()->back();
    }
    public function print()
    {
        $product = Product::all();
        return view('admin.product.printproduct', compact('product'));
    }
    public function totalCost()
    {
        $product = Product::all();
        $total = [];
        foreach($product as $product){
            $total [] = $product->product->price * $product->quantity;
        }
        $totalCost = array_sum($total);
        return response()->json([
            'message' => 'success',
            'data' => $totalCost
        ]);
    }
}
