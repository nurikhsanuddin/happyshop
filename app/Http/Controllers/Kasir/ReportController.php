<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\ProductTransaction;
use App\Models\Transaction;
use App\Models\Marketing;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all();
        return view('kasir.report.index', compact('transactions'));
    }
    public function show($id)
    {
        $transaction = Transaction::find($id);
        $productTransactions = ProductTransaction::where('transaction_id', $transaction->id)->get();
        $user_id = $productTransactions->first()->user_id;
        $marketing = User::where('id', $user_id)->first();

        return view('kasir.report.show', compact('transaction', 'productTransactions', 'marketing'));
    }

    public function print($id)
    {
        $transaction = Transaction::find($id);
        $productTransactions = ProductTransaction::where('transaction_id', $transaction->id)->get();
        $user_id = $productTransactions->first()->user_id;
        $marketing = User::where('id', $user_id)->first();
        return view('kasir.report.print', compact('transaction', 'productTransactions', 'marketing'));
    }
    public function delete(Request $request)
    {
        $transaction = Transaction::find($request->id);
        $transaction->delete();
        toast('Laporan transaksi berhasil dihapus')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }
}
