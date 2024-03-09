<?php

namespace App\Http\Controllers\Marketing;

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
        return view('marketing.product.index', compact('products','categories'));
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
