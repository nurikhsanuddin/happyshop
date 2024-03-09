@extends('layouts.template')
@section('content')

<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header justify-content-between d-flex d-inline">
          <h4 class="card-title"> Data Produk</h4>
          <!--<a href="{{ route('admin.product.printproduct') }}" target="_blank"> <i class="fas fa-print">Laporan Barang</i></a>-->
          <a href="#" data-toggle="modal" data-target="#tambah"><i class="fas fa-plus"> Tambah</i></a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
              <thead class=" text-primary">
                
                <th>
                  Kode Produk
                </th>
                <th>
                  Nama
                </th>
                <th>
                  Merk
                </th>
                <th>
                  Stok
                </th>
                <th>
                  Harga
                </th>
                <th>
                  Aksi
                </th>
              </thead>
              <tbody>
                  @foreach($products as $product)
                  <tr>
                      <td>{{ $product->product_code }}</td>
                      <td>{{ $product->name }}</td>
                      <td>{{ $product->merk }}</td>
                      <td>{{ $product->quantity }}</td>
                      <td>{{ $product->price }}</td>
                      <td>
                          <a href="#" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-merk="{{ $product->merk }}"
                            data-code="{{ $product->product_code }}" data-quantity="{{ $product->quantity }}" data-price="{{ $product->price }}" data-category="{{ $product->category_id }}"  data-toggle="modal" data-target="#edit"><i class="fas fa-edit"></i></a>
                          <a href="#" data-target="#delete" data-toggle="modal" data-id="{{ $product->id }}"><i class="fas fa-trash"></i></a>
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
  
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.product.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title"><span>Ubah</span> Data Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               
                <div class="modal-body">
                    <div class="form-group">
                        <label for="product_code">Kode Produk</label>
                        <input type="text" class="form-control @error('product_code') is-invalid @enderror" id="product_code" name="product_code" required>
                        @error('product_code')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
                        @error('name')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Merk</label>
                        <input type="text" class="form-control @error('merk') is-invalid @enderror" id="merk" name="merk" required>
                        @error('merk')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="quantity">Jumlah</label>
                        <input type="text" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" required>
                        @error('quantity')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" required>
                        @error('price')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="category_id">Kategori Produk</label>
                        <select name="category_id" id="category_id" class="custom-select @error('category_id') is-invalid @enderror">
                            <option value="">~ Pilih Kategori Produk ~</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
             
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.product.delete') }}" method="POST">
                @csrf
                @method('delete')
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title"><span>Hapus</span> Data Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus Data Produk ini ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.product.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title"><span>Tambah</span> Data Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="product_code">Kode Produk</label>
                        <input type="text" class="form-control @error('product_code') is-invalid @enderror" id="product_code" name="product_code" value="{{ old('product_code') }}" required>
                        @error('product_code')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Merk</label>
                        <input type="text" class="form-control @error('merk') is-invalid @enderror" id="merk" name="merk" value="{{ old('merk') }}" required>
                        @error('merk')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="quantity">Jumlah</label>
                        <input type="text" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                        @error('quantity')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                        @error('price')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="category_id">Kategori Produk</label>
                        <select name="category_id" id="category_id" class="custom-select @error('category_id') is-invalid @enderror">
                            <option value="">~ Pilih Kategori Produk ~</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $("#edit").on('show.bs.modal', (e) => {
        var id = $(e.relatedTarget).data('id');
        var code = $(e.relatedTarget).data('code');
        var name = $(e.relatedTarget).data('name');
        var merk = $(e.relatedTarget).data('merk');
        var quantity = $(e.relatedTarget).data('quantity');
        var price = $(e.relatedTarget).data('price');
        var category = $(e.relatedTarget).data('category');
        
        $('#edit').find('input[name="id"]').val(id);
        $('#edit').find('input[name="product_code"]').val(code);
        $('#edit').find('input[name="name"]').val(name);
        $('#edit').find('input[name="merk"]').val(merk);
        $('#edit').find('input[name="quantity"]').val(quantity);
        $('#edit').find('input[name="price"]').val(price);
        $('#edit').find('select[name="category_id"]').val(category);
    });
    
    $('#delete').on('show.bs.modal', (e) => {
        var id = $(e.relatedTarget).data('id');
        console.log(id);
        $('#delete').find('input[name="id"]').val(id);
    });
</script>
@endpush