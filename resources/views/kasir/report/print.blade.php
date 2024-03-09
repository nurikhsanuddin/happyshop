<style>
  td{
    padding-right: 50px;
  }

</style>
<p>{{ App\Models\Company::take(1)->first()->name }}</p>
<p>{{ App\Models\Company::take(1)->first()->address }}</p>
<p>Kasir : {{ $transaction->user->name }}</p>
<p>Tanggal : {{ date('m-d-Y', strtotime($transaction->created_at)) }}</p>
==========================================

<table>
  @foreach ($productTransactions as $product)
  <tr>
    <td>{{ $product->product->name }}</td>
    <td>{{ $product->quantity }}</td>
    <td>{{ $product->product->price }}</td>
    <td>{{ $product->product->price * $product->quantity }}</td>
  </tr>
  @endforeach
  <tr>
    <td colspan="3" align="right">Total Pembelian</td>
    <td>{{ $transaction->purchase_order }}</td>
  </tr>
  <tr>
    <td colspan="3" align="right">Bayar</td>
    <td>{{ $transaction->pay }}</td>
  </tr>
  <tr>
    <td colspan="3" align="right">Kembalian</td>
    <td>{{ $transaction->return }}</td>
  </tr>
</table>
==========================================<br><br>
Terimakasih telah berbelanja. Semoga harimu menyenangkan