<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Supply;
use App\Models\Transaction;
use Illuminate\Http\Request;

use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $transactions = Transaction::count();
        $transactions = Transaction::count();
        $categories = Category::count();
        $products = Product::count();
        $hariIni = ProductTransaction::where('user_id', auth()->user()->id)
            ->whereDate('created_at', Carbon::today())
            ->whereNotNull('hargajual')
            ->sum('hargajual');
        $bulanIni = ProductTransaction::where('user_id', auth()->user()->id)
            ->whereMonth('created_at', Carbon::now()->month) // Memeriksa bulan ini
            ->whereYear('created_at', Carbon::now()->year) // Memeriksa tahun ini
            ->whereNotNull('hargajual')
            ->sum('hargajual');
        $supplies = Supply::count();
        $getProducts = Product::all();
        $transactionGet = Transaction::take(5)->latest()->get();
        $totalProduct = [];
        $nameProduct = [];
        $cek = Product::with('productTransactions')->get();
        foreach ($cek as $c) {
            $totalProduct[] = $c->productTransactions->sum('quantity');
            $nameProduct[] = $c->name;
        }
        $result = [
            'total' => $totalProduct,
            'product' => $nameProduct
        ];
        return view('marketing.dashboard.index', compact('transactions', 'categories', 'products', 'supplies', 'transactionGet', 'result', 'hariIni', 'bulanIni'));
    }
}
