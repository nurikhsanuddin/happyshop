@extends('layouts.template')
@section('content')

<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title"> Detail Pasok</h4><hr>
          <div class="card-title">
            <table>
                <tr>
                    <td>Nama Pemasok</td>
                    <td>:</td>
                    <td> &nbsp; {{ $supply->supplier_name }}</td>
                </tr>
                <tr>
                    <td>Total Produk</td>
                    <td>:</td>
                    <td> &nbsp; {{ $supply->productSupply()->count() }}</td>
                </tr>
                <tr>
                    <td>Tanggal Pasok</td>
                    <td>:</td>
                    <td> &nbsp; {{ $supply->supply_date }}</td>
                </tr>
                </table>
            </div>
        </div>
        <hr>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
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
              </thead>
              <tbody>
                  @foreach($product_supplies as $key => $product)
                  <tr>
                      <th>{{ $key+1 }}</th>
                      <th>{{ $product->product->name }}</th>
                      <th>{{ $product->quantity }}</th>
                  </tr>
                  @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
