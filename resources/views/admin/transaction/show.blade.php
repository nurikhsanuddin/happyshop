@extends('layouts.template')
@section('content')

<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title"> Detail Transaksi</h4><hr>
          <div class="row justify-content-between d-inline d-flex">
              <div class="col-6">
                <table>
                  <tr>
                    <td>Kode Transaksi</td>
                    <td> : </td>
                    <td> {{ $transaction->transaction_code }}</td>
                  </tr>
                  <tr>
                    <td>Tanggal</td>
                    <td> : </td>
                    <td> {{ date('m-d-Y', strtotime($transaction->created_at)) }}</td>
                  </tr>
                </table>
              </div>
              <div class="col-6-offset-0">
                <div class="container-fluid">
                  <table>
                    <tr>
                      <td>Kasir</td>
                      <td> : </td>
                      <td> {{ $transaction->user->name }}</td>
                    </tr>
                    <tr>
                      <td>Nama Pelanggan</td>
                      <td> : </td>
                      <td>{{ $transaction->customer_name ?? '' }}</td>
                    </tr>
                  </table>
                </div>
              </div>
          </div>
        </div>
        <div class="card-body">
          <table class="table table-bordered">
            <thead>
              <th>
                No
              </th>
              <th>
                Nama Produk
              </th>
              <th>
                Jumlah
              </th>
              <th>
                Harga
              </th>
              <th>
                Total
              </th>
            </thead>
            <tbody>
                @foreach($product_transaction as $key => $product)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $product->product->name }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ $product->product->price }}</td>
                    <td>{{ $product->product->price * $product->quantity }}</td>
                </tr>
                @endforeach
                <tr>
                  <td colspan="4" align="right"><b>Total Pembelian</b></td>
                  <td>{{ format_uang($transaction->purchase_order) }}</td>
                </tr>
                <tr>
                  <td colspan="4" align="right"><b>Bayar</b></td>
                  <td>{{ format_uang($transaction->pay) }}</td>
                </tr>
                <tr>
                  <td colspan="4" align="right"><b>Kembalian</b></td>
                  <td>{{ format_uang($transaction->return) }}</td>
                </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
