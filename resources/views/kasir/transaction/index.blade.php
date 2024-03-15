@extends('layouts.template')
@section('content')
<style>
    .card-header {
        background-color: #1B3A5D !important;
        color: white !important;
    }

    .fa-eye:hover {
        cursor: pointer;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="pesan">

</div>
<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-header justify-content-between d-flex d-inline">
                <h5 class="card-title">Order Produk</h5>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <label for="get_costumer">Nama Costumer</label>
                        <select class="js-example-basic-single" name="get_costumer" id="get_costumer" required style="width: 100% !important;">
                            <option value="" selected disabled>Pilih Nama Costumer</option>
                            <!-- <option value="">All</option> -->
                            @foreach($pembeli as $pemb)
                            <option value="{{  $pemb }}">{{ $pemb }}</option>
                            @endforeach

                        </select>

                    </div>
                    <!-- <div class="col-4">
                        <label for="get_product_name">Nama Produk</label>
                        <input type="text" id="get_product_name" disabled placeholder="Nama" class="form-control">
                    </div>
                    <div class="col-4">
                        <label for="get_product_price">Harga</label>
                        <input type="text" id="get_product_price" disabled placeholder="Harga" class="form-control">
                    </div> -->
                </div>
                <hr>
                <!-- <div class="row">
                    <div class="col-4">
                        <label for="get_product_quantity">Jumlah</label>
                        <input type="number" id="get_product_quantity" disabled placeholder="Jumlah" class="form-control" min="0">
                    </div>
                    <div class="col-4">
                        <label for="get_product_total">Total Harga</label>
                        <input type="text" id="get_product_total" disabled placeholder="Total Harga" class="form-control">
                    </div>
                    <div class="col-4" style="margin-top: 10px;">
                        <input type="button" value="Tambahkan" id="addToCart" disabled class="btn btn-primary text-white">
                    </div>
                </div> -->
                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>Nama Produk</td>
                                <td>Jumlah</td>
                                <td>Harga</td>
                                <td>Total</td>
                                <td>Aksi</td>
                            </tr>
                        </thead>
                        <tbody id="posts-crud">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-header justify-content-between d-flex d-inline">
                <h5 class="card-title" id="totalBuy"></h5>
            </div>
            <div class="card-body">
                <form action="{{ route('kasir.transaction.pay') }}" method="post">
                    @csrf
                    <div class="form-group" id="containerCustomer">
                        <div class="justify-content-between d-flex d-inline">
                            <label for="customer_name" id="l_customer_name">Nama Pembeli</label><i class="fas fa-eye" id="anonym"></i>
                        </div>
                        <input type="text" class="form-control" id="customer_name" name="customer_name">
                    </div>
                    <div class="form-group">
                        <label for="payment">No HP</label>
                        <input type="number" class="form-control" name="no_hp">
                    </div>
                    <div class="form-group">
                        <label for="payment">Alamat</label>
                        <input type="text" class="form-control" name="alamat">
                    </div>
                    <div class="form-group">
                        <label for="payment">Sub Total</label>
                        <input type="number" class="form-control" id="subtotal" name="subtotal" readonly>
                    </div>
                    <div class="form-group">
                        <label for="payment">Bayar</label>
                        <input type="number" class="form-control" id="payment" name="payment">
                    </div>
                    <div class="form-group">
                        <label for="return">Kembalian</label>
                        <input type="number" class="form-control" id="return" readonly name="return">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="tPayment" disabled> Bayar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
        const totalBuy = document.getElementById('totalBuy');
        $('#get_costumer').on('change', function() {
            var selectedCustomer = $(this).val();
            $('#customer_name').val(selectedCustomer || ''); // Jika tidak ada opsi yang dipilih, nilai input akan kosong

            $.ajax({
                type: 'GET',
                url: "{{ route('kasir.transaction.totalBuy') }}",
                dataType: 'json',
                data: {
                    'nama_pembeli': selectedCustomer
                },
                success: function(data) {
                    totalBuy.innerHTML = "Total " + formatRupiah(data.data, 'Rp. ');
                },
                error: function(data) {
                    console.log('gagal');
                }
            });
        });

        $value = $('#get_costumer').val();
        $('.js-example-basic-single').on('change', function(e) {
            $value = $(e.currentTarget).val();
            $.ajax({
                type: 'GET',
                url: "{{ route('kasir.transaction.getPembeli') }}",
                dataType: 'json',
                data: {
                    'search': $value
                },
                success: function(response) {
                    $('tbody').html("");
                    $.each(response.data, function(key, item) {
                        let content = `<tr>\
                        <td>${item.product.name}</td>\
                        <td>${item.quantity}</td>\
                        <td>${item.product.price}</td>\
                        <td>${formatRupiah(item.hargajual)}</td>\
                        <td><button type="button" value="${item.id}" data-id="${item.id}" class="btn btn-danger delete-btn btn-sm"><i class="fas fa-times-circle"></i></button></td>\
                    \</tr>`;
                        $('tbody').append(content);
                    });
                },

            });
        })

        function formatRupiah(angka) {
            var number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        const customer_container = document.querySelector('.table');
        const thumbs = document.querySelectorAll('tombol');
        $('.table').on('click', '.delete-btn', function(e) {
            let id = $(this).data('id');
            $(this).prop('disabled', true);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'DELETE',
                dataType: 'json',
                data: {
                    'id': id
                },
                url: "{{ route('kasir.transaction.deleteCart') }}",
                success: function() {
                    // Hapus baris terkait dari tabel
                    e.target.closest('tr').remove();

                    // Periksa apakah keranjang kosong
                    if ($('tbody').find('tr').length === 0) {
                        $('#totalBuy').html(""); // Atur teks pada card-header "Total Belanja" menjadi kosong
                    } else {
                        // Ambil total belanja baru setelah menghapus item
                        $.ajax({
                            type: 'GET',
                            url: "{{ route('kasir.transaction.totalBuy') }}",
                            dataType: 'json',
                            success: function(data) {
                                totalBuy.innerHTML = "Total " + formatRupiah(data.data, 'Rp. ');
                            },
                            error: function(data) {
                                console.log('gagal');
                            }
                        });
                    }
                },
                error: function() {
                    // Handle error
                }
            });
        });



        function formatRupiah(angka, prefix) {
            var number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        function hitungKembalian() {
            var totalBuyValue = parseInt($('#totalBuy').text().replace(/[^0-9]/g, ''));

            var paymentValue = parseInt($('#payment').val());

            if (!isNaN(totalBuyValue) && !isNaN(paymentValue)) {
                var returnAmount = paymentValue - totalBuyValue;
                $('#return').val(returnAmount);
                $('#subtotal').val(totalBuyValue);
                $('#tPayment').prop('disabled', returnAmount < 0); // Aktifkan tombol Bayar hanya jika kembalian >= 0
            }
        }

        // Panggil fungsi hitungKembalian saat nilai pembayaran berubah
        $('#payment').on('input', function() {
            hitungKembalian();
        });

        // Panggil fungsi hitungKembalian saat total belanja berubah (misalnya saat memilih pelanggan baru)
        $('#totalBuy').bind("DOMSubtreeModified", function() {
            hitungKembalian();
        });

    })
</script>

@endpush