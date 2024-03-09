<style>
  td{
    padding-right: 50px;
  }

</style>
<p>Laporan Stok Barang</p>
<p>{{ App\Models\Company::take(1)->first()->name }}</p>
<p>{{ App\Models\Company::take(1)->first()->address }}</p>

<p>Tanggal : {{ date('m-d-Y',) }}</p>
<table border="1">
<tr>
    <td>Nama</td>
    <td>Merk</td>
    <td>Stok</td>
    <td>Cost</td>
    <td>Total Cost</td>
  </tr>
  <br>
  @foreach ($product as $product)
  <tr>
    <td>{{ $product->name }}</td>
    <td>{{ $product->merk }}</td>
    <td>{{ $product->quantity }}</td>
    <td>{{ format_uang($product->price) }}</td>
    <td>{{ format_uang($product->price*$product->quantity) }}</td>
  </tr>
  @endforeach
  <tr>
    <td colspan="3" align="right">Total Modal</td>
    <td>{{ format_uang($product->totalCost)}}</td>
  </tr>
</table>
footer