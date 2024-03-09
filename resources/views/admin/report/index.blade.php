@extends('layouts.template')
@section('content')

<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header justify-content-between d-flex d-inline">
          <h4 class="card-title"> Laporan Transaksi</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
            <thead>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Total Pembelian</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </thead>
              <tbody>
                  @foreach($transactions as $key => $transaction)
                  <tr>
                      <td>{{ $key+1 }}</td>
                      <td>{{ $transaction->transaction_code }}</td>
                      <td>{{ $transaction->purchase_order }}</td>
                      <td>{{ date('m-d-Y', strtotime($transaction->created_at)) }}</td>
                      <td>
                          <a href="{{ route('admin.report.show', $transaction->id) }}"><i class="fas fa-eye"></i></a>
                          <a href="#" data-target="#delete" data-toggle="modal" data-id="{{ $transaction->id }}"><i class="fas fa-trash"></i></a>
                      </td>
                  </tr>
                  @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.report.delete') }}" method="POST">
                @csrf
                @method('delete')
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title"><span>Hapus</span> Laporan Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus Laporan Transaksi ini ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#delete').on('show.bs.modal', (e) => {
        var id = $(e.relatedTarget).data('id');
        console.log(id);
        $('#delete').find('input[name="id"]').val(id);
    });
</script>
@endpush