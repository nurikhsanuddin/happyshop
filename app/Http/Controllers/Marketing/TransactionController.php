<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Transaction;
use App\Models\Chart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index()
    {
        $productTransactions = Chart::where('user_id', auth()->user()->id)->where('status', '0')->get();
        $products = Product::all();
        return view('marketing.transaction.index', compact('productTransactions', 'products'));
    }
    public function indexs()
    {
        $productTransactions = Chart::where('user_id', auth()->user()->id)
            ->where('status', '0')
            ->with('product')
            ->get();

        if ($productTransactions->isEmpty()) {
            return response()->json([
                'message' => 'Keranjang kosong',
                'data' => []
            ], 404);
        }

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
        $transaction = Transaction::find($request->id);
        $transaction->delete();
        return redirect()->back()->with('success', 'Berhasil menghapus data transaksi');
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
        return view('marketing.transaction.show', compact('transaction', 'product_transaction'));
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
        $productPrice = $product->price;
        $productPrice = preg_replace('/[^0-9]/', '', $productPrice);
        DB::beginTransaction();
        // try {
        //     $productTransaction = new ProductTransaction();
        //     $productTransaction->user_id = auth()->user()->id;
        //     $productTransaction->product_id = $product->id;
        //     $productTransaction->quantity = $request->quantity;
        //     $productTransaction->status = '0';
        //     $productTransaction->save();
        //     $productTransaction = ProductTransaction::where('id', $productTransaction->id)->with('product')->first();

        //     DB::commit();
        //     return response()->json([
        //         'message' => 'success',
        //         'data' => $productTransaction
        //     ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'message' => 'gagal gais',
        //     ], 500);
        // }
        try {
            $randomNumber = random_int(1000000000, 9999999999); // Menghasilkan bilangan bulat acak antara 1 miliar hingga 9.999.999.999

            $productTransaction = new ProductTransaction();
            // $productTransaction->transaction_id = auth()->user()->id;
            $productTransaction->user_id = auth()->user()->id;
            $productTransaction->id = $randomNumber;
            $productTransaction->product_id = $product->id;
            $productTransaction->quantity = $request->quantity;
            $productTransaction->nama_pembeli = $request->nama_pembeli;
            $productTransaction->status = '0';
            $total =  $request->total;
            $total = preg_replace('/[^0-9]/', '', $total);
            $productTransaction->hargajual =  $total;
            $productTransaction->save();

            $chart = new Chart();
            $chart->user_id = auth()->user()->id;
            $chart->id = $randomNumber;
            $chart->product_id = $product->id;
            $chart->quantity = $request->quantity;
            $chart->nama_pembeli = $request->nama_pembeli;
            $chart->status = '0';
            $total =  $request->total;
            $total = preg_replace('/[^0-9]/', '', $total);
            $chart->price_sell =  $total;
            // $chart->status = '0';
            $chart->save();

            DB::commit();

            return response()->json([
                'message' => 'success',
                'data' => $chart
            ]);
        } catch (\Exception $e) {
            // Tangkap pesan kesalahan dari pengecualian
            $errorMessage = $e->getMessage();

            // Berikan respons dengan pesan kesalahan
            return response()->json([
                'message' => 'gagal gais',
                'error' => $errorMessage
            ], 500);
        }
    }
    public function updateChartStatus(Request $request)
    {
        try {
            // Update status di tabel Chart
            Chart::where('user_id', auth()->user()->id)->update(['status' => '1']);

            // Berikan respons berhasil
            return response()->json([
                'message' => 'success',
            ]);
        } catch (\Exception $e) {
            // Tangkap pesan kesalahan
            $errorMessage = $e->getMessage();

            // Berikan respons dengan pesan kesalahan
            return response()->json([
                'message' => 'gagal gais',
                'error' => $errorMessage
            ], 500);
        }
    }
    public function deleteCart(Request $request)
    {
        $cart = Chart::find($request->id);
        if ($cart) {
            $cart->delete();
        }
        $kasir = ProductTransaction::find($request->id);
        if ($kasir) {
            $kasir->delete();
        }

        return response()->json([
            'message' => 'success',
            'data' => $cart
        ], 200);
    }
    // public function deleteCart(Request $request)
    // {
    //     $chart = Chart::find($request->id);
    //     $productTransaction = $chart->productTransaction; // Ambil entri ProductTransaction yang terkait

    //     if($chart && $productTransaction) {
    //         $chart->delete();
    //         $productTransaction->delete();

    //         return response()->json([
    //             'message' => 'success',
    //             'data' => $chart // Anda mungkin ingin mengembalikan data lain yang relevan di sini
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'message' => 'error',
    //             'data' => null // Atau beri respon yang sesuai jika entri tidak ditemukan
    //         ], 404);
    //     }
    // }


    public function totalBuy()
    {
        $productTransactions = Chart::where('user_id', auth()->user()->id)
            ->where('status', '0')
            ->get();

        if ($productTransactions->isEmpty()) {
            return response()->json([
                'message' => 'kosong',
            ], 404);
        }

        $totalBuy = 0;
        foreach ($productTransactions as $product) {
            if ($product->price_sell !== null) {
                $totalBuy += $product->price_sell;
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
            $productTransaction = ProductTransaction::where('user_id', auth()->user()->id)->where('status', '0');
            if (count($productTransaction->get())) {
                $purchaseOrder = [];
                foreach ($productTransaction->get() as $product) {
                    $purchaseOrder[] = $product->product->price * $product->quantity;
                }
                $totalPurchase = array_sum($purchaseOrder);
                $random = Str::random(10);

                $transaction = new Transaction;
                $transaction->user_id = auth()->user()->id;
                $transaction->transaction_code = auth()->user()->id . $random;

                $transaction->purchase_order = $totalPurchase;
                $transaction->customer_name = $request->customer_name ?? null;
                $transaction->save();

                $productTransaction->update([
                    'transaction_id' => $transaction->id,
                    'status' => '1',
                ]);
                DB::commit();
            }
            toast('Pembayaran berhasil')->autoClose(2000)->hideCloseButton();
            return redirect()->route('marketing.report.show', $transaction->id);
        } catch (\Exception $e) {
            $var = response()->json([
                'message' => 'gagal',
                'data' => $e
            ], 500);
        }
        return $var;
    }
}
