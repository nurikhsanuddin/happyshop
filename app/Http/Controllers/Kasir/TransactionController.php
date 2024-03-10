<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index()
    {
        $productTransactions = ProductTransaction::where('status', '0')->get();
        $products = Product::all();
        $pembeli = ProductTransaction::where('status', '0')->distinct()->pluck('nama_pembeli');
        return view('kasir.transaction.index', compact('productTransactions', 'products', 'pembeli'));
    }
    public function indexs()
    {
        $productTransactions = ProductTransaction::where('status', '0')->with('product')->get() ?? [];
        return response()->json([
            'message' => 'success',
            'data' => $productTransactions
        ]);
    }
    public function getPembeli(Request $request)
    {
        $nama_pembeli = $request->search; // Ambil nilai nama_pembeli dari request
        // Gunakan kondisi tambahan untuk memfilter berdasarkan nama_pembeli jika ada
        $productTransactions = ProductTransaction::where('status', '0')
            ->when($nama_pembeli, function ($query) use ($nama_pembeli) {
                return $query->where('nama_pembeli', $nama_pembeli);
            })
            ->with('product')
            ->get();

        return response()->json([
            'message' => 'success',
            'data' => $productTransactions
        ]);
    }
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $transaction = Transaction::create([
                'user_id' => auth()->user()->id,
                'transaction_code' => $request->transaction_code,
                'pay' => $request->pay,
            ]);
            for ($i = 0; $i < count($request->product); $i++) {
                ProductTransaction::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $request->product_id[$i],
                    'quantity' => $request->quantity[$i]
                ]);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Berhasil menambah transaksi');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('alert', 'Gagal melakukan transaksi');
        }
    }
    public function delete(Request $request)
    {
        $ProductTransaction = ProductTransaction::find($request->id);
        $ProductTransaction->delete();
        return response()->json([
            'message' => 'success',
            'data' => $ProductTransaction
        ], 200);
    }

    public function update(Request $request)
    {
        $transaction = Transaction::find($request->id);
        $transaction->update($request->all());
        return redirect()->back()->with('success', 'Berhasil mengubah data transaksi');
    }

    public function show($id)
    {
        $transaction = Transaction::find($id);
        $product_transaction = ProductTransaction::where('transaction_id', $transaction->id)->get();
        return view('kasir.transaction.show', compact('transaction', 'product_transaction'));
    }
    public function getProductCode(Request $request)
    {
        $product = Product::where('product_code', $request->search)->first() ?? '';
        return response()->json([
            'message' => 'success',
            'data' => $product
        ]);
    }
    public function addToCart(Request $request)
    {
        $product = Product::where('product_code', $request->product_code)->first();
        DB::beginTransaction();
        try {
            $productTransaction = new ProductTransaction();
            $productTransaction->user_id = auth()->user()->id;
            $productTransaction->product_id = $product->id;
            $productTransaction->quantity = $request->quantity;
            $productTransaction->status = '0';
            $productTransaction->save();
            $productTransaction = ProductTransaction::where('id', $productTransaction->id)->with('product')->first();

            DB::commit();
            return response()->json([
                'message' => 'success',
                'data' => $productTransaction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed',
            ], 500);
        }
    }
    public function deleteCart(Request $request)
    {
        $cart = ProductTransaction::find($request->id);
        $cart->delete();
        return response()->json([
            'message' => 'success',
            'data' => $cart
        ], 200);
    }
    public function totalBuy(Request $request)
    {
        $nama_pembeli = $request->nama_pembeli;
        $productTransactions = ProductTransaction::where('nama_pembeli', $nama_pembeli)
            ->where('status', '0')
            ->get();

        if ($productTransactions->isEmpty()) {
            return response()->json([
                'message' => 'No transactions found',
                'data' => null
            ], 404);
        }

        $totalBuy = 0;
        foreach ($productTransactions as $product) {
            // Pastikan price_sell ada dan memiliki nilai yang valid
            if (isset($product->hargajual) && is_numeric($product->hargajual)) {
                $totalBuy += $product->hargajual;
            }
        }

        return response()->json([
            'message' => 'success',
            'data' => $totalBuy
        ]);
    }
    public function pay(Request $request)
    {
        DB::beginTransaction();
        try {
            $customer_name = $request->customer_name;
            $productTransaction = ProductTransaction::where('nama_pembeli', $customer_name)->where('status', '0');
            if (count($productTransaction->get())) {

                $random = Str::random(10);

                $transaction = new Transaction;
                $transaction->user_id = auth()->user()->id;
                $transaction->transaction_code = auth()->user()->id . $random;
                $transaction->no_hp = $request->no_hp;
                $transaction->alamat = $request->alamat;
                $transaction->pay = $request->payment;
                $transaction->return = $request->return;
                $transaction->purchase_order = $request->subtotal;
                // $transaction->purchase_order = $totalPurchase;
                $transaction->customer_name = $customer_name ?? null;
                $transaction->save();
                // dd($transaction);
                $productTransaction->update([
                    'transaction_id' => $transaction->id,
                    'status' => '1',
                ]);
                DB::commit();
            }
            toast('Pembayaran berhasil')->autoClose(2000)->hideCloseButton();
            return redirect()->route('kasir.report.show', $transaction->id);
        } catch (\Exception $e) {
            $var = response()->json([
                'message' => 'failed',
                'data' => $e
            ], 500);
        }
        return $var;
    }
}
