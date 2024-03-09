<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductTransaction;
use App\Models\Transaction;
use Facade\Ignition\Tabs\Tab;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all();
        return view('admin.report.index', compact('transactions'));
    }
    public function show($id)
    {
        $transaction = Transaction::find($id);
        $productTransactions = ProductTransaction::where('transaction_id', $transaction->id)->get();
        return view('admin.report.show', compact('transaction','productTransactions'));
    }
    public function print($id)
    {
        $transaction = Transaction::find($id);
        $productTransactions = ProductTransaction::where('transaction_id', $transaction->id)->get();
        return view('admin.report.print', compact('transaction','productTransactions'));
    }
    public function delete(Request $request)
    {
        $transaction = Transaction::find($request->id);
        $transaction->delete();
        toast('Laporan transaksi berhasil dihapus')->autoClose(2000)->hideCloseButton();
        return redirect()->back();

    }
}
