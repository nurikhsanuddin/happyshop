<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Supply;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $transactions = Transaction::count();
        $transactions = Transaction::count();
        $categories = Category::count();
        $products = Product::count();
        $supplies = Supply::count();
        $getProducts = Product::all();
        $transactionGet = Transaction::take(5)->latest()->get();
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
        return view('admin.dashboard.index', compact('transactions','categories','products','supplies','transactionGet','result'));
    }
}
