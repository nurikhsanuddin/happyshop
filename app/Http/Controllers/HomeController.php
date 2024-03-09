<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Supply;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $transactions = Transaction::count();
        $transactionGet = Transaction::take(3)->latest()->get();
        $transactions = Transaction::count();
        $categories = Category::count();
        $products = Product::count();
        $supplies = Supply::count();
        $getProducts = Product::all();
        $totalProduct = [];
        $nameProduct = [];
        $cek = Product::with('productTransactions')->get();
        foreach($cek as $c){
            $totalProduct [] = $c->productTransactions->sum('quantity');
            $nameProduct [] = $c->name;
        }
        $result = [
            'total' => $totalProduct,
            'product' => $nameProduct
        ];
        $result = [
            'total' => $totalProduct,
            'product' => $nameProduct
        ];
        if(auth()->user()->role == 'admin'){
            
            return view('admin.dashboard.index', compact('transactions','categories','products','supplies','transactionGet','result'));
        } 
        if(auth()->user()->role == 'marketing'){
            
            return view('marketing.dashboard.index', compact('transactions','categories','products','supplies','transactionGet','result'));
        } else {
            return view('kasir.dashboard.index', compact('transactions','categories','products','supplies','transactionGet','result'));
        }
    }
}
