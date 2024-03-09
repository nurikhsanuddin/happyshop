@extends('layouts.template')
@section('content')

<div class="row mb-4">
    <div class="col-lg-3">
        <div class="card card-chart">
            <div class="card-header">
                <h5 class="card-category">Total Kategori Produk</h5>
                <h4 class="card-title">{{ $categories }}</h4>
                <div class="dropdown">
                    <button type="button" class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret" data-toggle="dropdown">
                        <i class="now-ui-icons loader_gear"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('admin.category.index') }}">Lihat Detail</a>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <a href="{{ route('admin.category.index') }}"><i class="now-ui-icons ui-1_zoom-bold"></i> Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card card-chart">
            <div class="card-header">
                <h5 class="card-category">Total Produk</h5>
                <h4 class="card-title">{{ $products }}</h4>
                <div class="dropdown">
                    <button type="button" class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret" data-toggle="dropdown">
                        <i class="now-ui-icons loader_gear"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('admin.product.index') }}">Lihat Detail</a>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <a href="{{ route('admin.product.index') }}"><i class="now-ui-icons ui-1_zoom-bold"></i> Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card card-chart">
            <div class="card-header">
                <h5 class="card-category">Total Transaksi</h5>
                <h4 class="card-title">{{ $transactions }}</h4>
                <div class="dropdown">
                    <button type="button" class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret" data-toggle="dropdown">
                        <i class="now-ui-icons loader_gear"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('admin.transaction.index') }}">Lihat Detail</a>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <a href="{{ route('admin.transaction.index') }}"><i class="now-ui-icons ui-1_zoom-bold"></i> Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card card-chart">
            <div class="card-header">
                <h5 class="card-category">Total Pasok</h5>
                <h4 class="card-title">{{ $supplies }}</h4>
                <div class="dropdown">
                    <button type="button" class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret" data-toggle="dropdown">
                        <i class="now-ui-icons loader_gear"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('admin.supply.index') }}">Lihat Detail</a>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <a href="{{ route('admin.supply.index') }}"><i class="now-ui-icons ui-1_zoom-bold"></i> Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header ">
                <i class="now-ui-icons loader_refresh spin"></i> &nbsp; 5 transaksi terbaru
            </div>
            <div class="card-body ">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Total Pembelian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactionGet as $transaction)
                        <tr>
                            <td>{{ $transaction->transaction_code }}</td>
                            <td>{{ $transaction->purchase_order }}</td>
                            <td>
                                <a href="{{ route('admin.report.show', $transaction->id) }}"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="card">
            <div class="card-header">
                <i class="now-ui-icons loader_refresh spin"></i> &nbsp; 5 Produk Terlaris
            </div>
            <div class="card-body">        
                <canvas id="myChart" width="400" height="400"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($result['product']) !!},
            datasets: [{
                label: '# Total Penjualan',
                data: {!! json_encode($result['total']) !!},

                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>
     
@endpush
