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
        $productTransactions = ProductTransaction::where('user_id', auth()->user()->id)->where('status','0')->get();
        $products = Product::all();
        return view('kasir.transaction.index', compact('productTransactions','products'));
    }
    public function indexs()
    {
        $productTransactions = ProductTransaction::where('user_id', auth()->user()->id)->where('status','0')->with('product')->get() ?? [];
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
            for($i = 0; $i < count($request->product); $i++){
                ProductTransaction::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $request->product_id[$i],
                    'quantity' => $request->quantity[$i]
                ]);
            }
            DB::commit();
            return redirect()->back()->with('success','Berhasil menambah transaksi');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('alert','Gagal melakukan transaksi');
        }
       
    }
    public function delete(Request $request)
    {
        $transaction = Transaction::find($request->id);
        $transaction->delete();
        return redirect()->back()->with('success','Berhasil menghapus data transaksi');
    }
    public function update(Request $request)
    {
        $transaction = Transaction::find($request->id);
        $transaction->update($request->all());
        return redirect()->back()->with('success','Berhasil mengubah data transaksi');
    }
    
    public function show($id)
    {
        $transaction = Transaction::find($id);
        $product_transaction = ProductTransaction::where('transaction_id', $transaction->id)->get();
        return view('kasir.transaction.show', compact('transaction','product_transaction'));
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
        try{
            $productTransaction = New ProductTransaction();
            $productTransaction->user_id = auth()->user()->id;
            $productTransaction->product_id = $product->id;
            $productTransaction->quantity = $request->quantity;
            $productTransaction->status = '0';
            $productTransaction->save();
            $productTransaction = ProductTransaction::where('id',$productTransaction->id)->with('product')->first();

            DB::commit();
            return response()->json([
                'message' => 'success',
                'data' => $productTransaction
            ]);
        }catch(\Exception $e){
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
    public function totalBuy()
    {
        $productTransactions = ProductTransaction::where('user_id', auth()->user()->id)->where('status','0')->get() ?? [];
        $total = [];
        foreach($productTransactions as $product){
            $total [] = $product->product->price * $product->quantity;
        }
        $totalBuy = array_sum($total);
        return response()->json([
            'message' => 'success',
            'data' => $totalBuy
        ]);
    }
    public function pay(Request $request)
    {
        DB::beginTransaction();
        try {
            $productTransaction = ProductTransaction::where('user_id', auth()->user()->id)->where('status','0');
            if(count($productTransaction->get())){
                $purchaseOrder = [];
                foreach($productTransaction->get() as $product) {
                    $purchaseOrder [] = $product->product->price * $product->quantity;
                }
                $totalPurchase = array_sum($purchaseOrder);
                $random = Str::random(10);

                $transaction = new Transaction;
                $transaction->user_id = auth()->user()->id;
                $transaction->transaction_code = auth()->user()->id . $random;
                $transaction->pay = $request->payment;
                $transaction->return = $request->return;
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
            return redirect()->route('kasir.report.show', $transaction->id);
        }catch(\Exception $e ){
            $var = response()->json([
                'message' => 'failed',
                'data' => $e
            ],500);
        }
        return $var;
    }
}
