@extends('layouts.template')
@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="container-fluid">
          <div class="row justify-content-between d-flex d-inline">
            <h4 class="card-title"> Detail Transaksi</h4>
            <hr>
            <a href="{{ route('kasir.report.print', $transaction->id) }}" target="_blank" class="btn btn-primary">Cetak Nota</a>
          </div>
        </div>
        <hr>
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
          <div class="col-12-offset-0">
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
                <tr>
                  <td>Nama Marketing</td>
                  <td> : </td>
                  <td>{{ $marketing->name ?? '' }}</td>
                </tr>
                <tr>
                  <td>No HP</td>
                  <td> : </td>
                  <td>{{ $transaction->no_hp ?? '' }}</td>
                </tr>
                <tr>
                  <td>Alamat</td>
                  <td> : </td>
                  <td>{{ $transaction->alamat ?? '' }}</td>
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
            @foreach($productTransactions as $key => $product)
            <tr>
              <td>{{ $key+1 }}</td>
              <td>{{ $product->product->name }}</td>
              <td>{{ $product->quantity }}</td>
              <td>{{ $product->hargajual }}</td>
              <td>{{ $product->hargajual  * $product->quantity }}</td>
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