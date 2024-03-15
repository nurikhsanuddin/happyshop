<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 10px;
      margin: 0;
      padding: 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
    }

    table,
    th,
    td {
      border: 1px solid black;
    }

    th,
    td {
      padding: 1px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    .center {
      text-align: center;
    }

    .right {
      text-align: right;
    }

    .invoice-container {
      width: 100%;
      /* padding: 10px; */
      box-sizing: border-box;

    }
  </style>
</head>

<body>
  <div class="invoice-container">
    <p>{{ App\Models\Company::take(1)->first()->name }}</p>
    <hr>

    <table style="border: none;">
      <tr style="border: none;">
        <td style="border: none;">Nama Pelanggan:</td>
        <td style="border: none;">{{ $transaction->customer_name ?? '' }}</td>
      </tr>
      <tr style="border: none;">
        <td style="border: none;">Nomor Telp:</td>
        <td style="border: none;">{{ $transaction->no_hp ?? '' }}</td>
      </tr>
      <tr style="border: none;">
        <td style="border: none;">Alamat:</td>
        <td style="border: none;">{{ $transaction->alamat ?? '' }}</td>
      </tr>
      <tr style="border: none;">
        <td style="border: none;">Nama Marketing:</td>
        <td style="border: none;">{{ $marketing->name ?? '' }}</td>
      </tr>
      <tr style="border: none;">
        <td style="border: none;">Tanggal:</td>
        <td style="border: none;">{{ date('m-d-Y', strtotime($transaction->created_at)) }}</td>
      </tr style="border: none;">
    </table>


    <hr>

    <table>
      <tr>
        <th>Nama Produk</th>
        <th>Jumlah</th>
        <th>Harga Satuan</th>
        <th>Total</th>
      </tr>
      @foreach ($productTransactions as $product)
      <tr>
        <td>{{ $product->product->name }}</td>
        <td>{{ $product->quantity }}</td>
        <td>{{ format_uang($product->hargajual) }}</td>
        <td>{{ format_uang($product->hargajual  * $product->quantity) }}</td>
      </tr>
      @endforeach
      <tr>
        <td colspan="3" class="right">Total Pembelian</td>
        <td>{{ format_uang($transaction->purchase_order) }}</td>
      </tr>
      <tr>
        <td colspan="3" class="right">Bayar</td>
        <td>{{ format_uang($transaction->pay) }}</td>
      </tr>
      <tr>
        <td colspan="3" class="right">Kembalian</td>
        <td>{{format_uang($transaction->return) }}</td>
      </tr>
    </table>

    <hr>

    <p class="center">Terimakasih telah berbelanja. Semoga harimu menyenangkan</p>
  </div>
</body>

</html>
<script>
  window.print();
</script>