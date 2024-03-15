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
    <div class="col-md-12">
        <div class="card">
            <div class="card-header justify-content-between d-flex d-inline">
                <!-- <h5 class="card-title">Order Produk dulu ya</h5> -->
                <h5 class="card-title">Marketing : {{ auth()->user()->name }}</h5>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <label for="get_product_code">Nama Produk</label>
                        <select class="js-example-basic-single" name="get_product_code" id="get_product_code" required style="width: 100% !important;">
                            <option value="" selected></option>
                            @foreach($products as $product)
                            <option value="{{ $product->product_code }}">{{ $product->name }} (stok {{ $product->quantity }})</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-6">
                        <label for="get_product_name">Nama Produk</label>
                        <input type="text" id="get_product_name" disabled placeholder="Nama" class="form-control">
                    </div>
                    <div class="col-6">
                        <label for="get_product_price">Harga</label>
                        <input type="text" id="get_product_price" placeholder="Harga" class="form-control">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <label for="get_product_quantity">Jumlah</label>
                        <input type="number" id="get_product_quantity" disabled placeholder="Jumlah" class="form-control" min="0">
                    </div>
                    <div class="col-6">
                        <label for="get_product_total">Total Harga</label>
                        <input type="text" id="get_product_total" disabled placeholder="Total Harga" class="form-control">
                    </div>
                    <div class="col-12 mt-3">
                        <label for="nama_pembeli">Nama Pembeli</label>
                        <input type="text" id="nama_pembeli" placeholder="Masukkan Nama Pembeli" class="form-control">
                    </div>
                    <div class="col-12 mt-1">
                        <span class="badge bg-warning">Nama pembeli harus sama</span>
                    </div>
                    <div class="col-12" style="margin-top: 10px;">
                        <input type="button" value="Tambahkan" id="addToCart" disabled class="btn btn-primary text-white">
                    </div>
                </div>


                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>Nama Pembeli</td>
                                <td>Nama Produk</td>
                                <td>Harga Asli</td>
                                <td>Jumlah</td>
                                <td>Total</td>
                                <td>Aksi</td>
                            </tr>
                        </thead>
                        <tbody id="keranjang">

                        </tbody>
                        <div class="card-header justify-content-between d-flex d-inline">
                            <h5 class="card-title" id="totalBuy"></h5>
                        </div>
                    </table>
                    <div class="form-group">
                        <button type="submit" value="simpan" class="btn btn-primary" id="simpan"> selesai</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--
    <div class="col-4">
        <div class="card">
            <div class="card-header justify-content-between d-flex d-inline">
                <h5 class="card-title" id="totalBuy"></h5>
            </div>
            <div class="card-body">
                <form action="{{ route('marketing.transaction.pay') }}" method="post">
                    @csrf
                    <div class="form-group" id="containerCustomer">
                        <div class="justify-content-between d-flex d-inline">
                            <label for="customer_name" id="l_customer_name">Nama Pembeli</label><i class="fas fa-eye" id="anonym"></i>
                        </div>
                        <input type="text" class="form-control" id="customer_name" name="customer_name">
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
    -->
</div>
@endsection
@push('scripts')

<script>
    $(document).ready(function() {
        // Menambahkan event listener untuk perubahan pada get_product_price
        $('#get_product_price').on('input', function() {
            // Mendapatkan nilai dari get_product_price
            var productPrice = parseFloat($(this).val());
            // Mendapatkan nilai dari get_product_quantity
            var productQuantity = parseInt($('#get_product_quantity').val());
            // Menghitung total harga
            var totalPrice = productPrice * productQuantity;
            // Mengupdate nilai get_product_total
            $('#get_product_total').val(formatRupiah(totalPrice));
        });

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
        $('.js-example-basic-single').select2();
        const totalBuy = document.getElementById('totalBuy');

        function fetchstudent() {
            $.ajax({
                type: "GET",
                url: "{{ route('marketing.transaction.indexs') }}",
                dataType: "json",
                success: function(response) {
                    $('tbody').html("");
                    if (response.data.length === 0) {
                        // Jika data kosong, tambahkan pesan "Keranjang Kosong" ke dalam tabel
                        let emptyCartMessage = '<tr><td colspan="6" class="text-center">Keranjang kosong</td></tr>';
                        $('tbody').append(emptyCartMessage);
                    } else {
                        // Jika ada data, tambahkan data ke dalam tabel seperti biasa
                        $.each(response.data, function(key, item) {
                            let content = `<tr>\
                    <td>${item.nama_pembeli}</td>\
                    <td>${item.product.name}</td>\
                    <td>${item.product.price}</td>\
                    <td>${item.quantity}</td>\
                    <td>${formatRupiah(item.price_sell)}</td>\
                    <td><button type="button" value="${item.id}" data-id="${item.id}" class="btn btn-danger delete-btn btn-sm"><i class="fas fa-times-circle"></i></button></td>\
                \</tr>`;
                            $('tbody').append(content);
                        });
                    }
                }
            });
        }

        fetchstudent();
        getTotalBuy();
        let productCode = document.getElementById('get_product_code');
        let productName = document.getElementById('get_product_name');
        $('.js-example-basic-single').on('change', function(e) {
            $value = $(e.currentTarget).val();
            $.ajax({
                type: 'GET',
                url: "{{ route('marketing.transaction.getProductCode') }}",
                dataType: 'json',
                data: {
                    'search': $value
                },
                success: function(data) {
                    if (data.data == '') {
                        $('#addToCart').prop('disabled', true);
                        $('#get_product_quantity').prop('disabled', true);
                        $('#get_product_quantity').val('');
                    } else {
                        $('#addToCart').prop('disabled', false);
                        $('#get_product_quantity').prop('disabled', false);
                        $('#get_product_quantity').val('1');
                        // Perbarui nilai get_product_total sesuai dengan get_product_price
                        $('#get_product_total').val(formatRupiah(data.data.price));
                    }
                    $('#get_product_name').val(data.data.name);
                    // Validasi dan pastikan format price yang dimasukkan hanya terdiri dari angka
                    var price = data.data.price.replace(/[^\d]/g, ''); // Hanya menyimpan angka
                    $('#get_product_price').val(price);
                },
                error: function(data) {
                    $('#addToCart').prop('disabled', true);
                    $('#get_product_name').val('');
                    $('#get_product_price').val('');
                    // Jika terjadi kesalahan, reset nilai get_product_total menjadi kosong
                    $('#get_product_total').val('');
                }
            });
        });


        // const addToCart = document.getElementById('addToCart');
        // addToCart.addEventListener('click', function() {
        //     $productCode = $('#get_product_code');
        //     $productQuantity = $('#get_product_quantity');
        //     $('#addToCart').prop('disabled', true);
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         type: 'POST',
        //         dataType: 'json',
        //         data: {
        //             'product_code': $productCode.val(),
        //             'quantity': $productQuantity.val()
        //         },
        //         url: "{{ route('marketing.transaction.addToCart') }}",
        //         success: function(data) {
        //             $('#get_product_code').val('');
        //             $('#get_product_name').val('');
        //             $('#get_product_price').val('');
        //             $('#get_product_quantity').val('');
        //             $('#get_product_total').val('');
        //             $('.js-example-basic-single').val(0).trigger('change.select2');
        //             fetchstudent();
        //             getTotalBuy();
        //         },
        //         error: function() {
        //             console.log('gagal');
        //         }
        //     })
        // })
        const simpan = document.getElementById('simpan');
        simpan.addEventListener('click', function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType: 'json',
                url: "{{ route('marketing.transaction.updateChartStatus') }}",
                success: function(data) {
                    // Tampilkan pesan sukses
                    alert('Status berhasil diperbarui.');

                    // Memuat ulang halaman setelah 1 detik
                    setTimeout(function() {
                        location.reload();
                    }, 100);
                },
                error: function() {
                    // Tampilkan pesan error
                    alert('Gagal memperbarui status.');
                }
            });
        });

        const addToCart = document.getElementById('addToCart');
        addToCart.addEventListener('click', function() {
            $productCode = $('#get_product_code');
            $productQuantity = $('#get_product_quantity');
            $productTotal = $('#get_product_total');
            $namaPembeli = $('#nama_pembeli');
            // $productTotal = $('#get_product_total');
            if ($namaPembeli.val() === '') {
                // Munculkan pesan jika nama_pembeli kosong
                $('.pesan').html('<div class="alert alert-danger" role="alert">Nama pembeli tidak boleh kosong!</div>');
                return; // Hentikan eksekusi lebih lanjut
            }
            $('#addToCart').prop('disabled', true);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType: 'json',
                data: {
                    'product_code': $productCode.val(),
                    'quantity': $productQuantity.val(),
                    'nama_pembeli': $namaPembeli.val(),
                    'total': $productTotal.val()
                },
                url: "{{ route('marketing.transaction.addToCart') }}",
                success: function(data) {
                    // Reset form values
                    $('#get_product_code').val('');
                    $('#get_product_name').val('');
                    $('#get_product_price').val('');
                    $('#get_product_quantity').val('');
                    $('#get_product_total').val('');
                    $('.js-example-basic-single').val(0).trigger('change.select2');
                    // Fetch updated data and total buy
                    fetchstudent();
                    getTotalBuy();
                    // Optional: Show success message
                    $('.pesan').html('<div class="alert alert-success" role="alert">Produk berhasil ditambahkan ke keranjang!</div>');
                },
                error: function() {
                    console.log('gagal');
                    // Optional: Show error message
                    $('.pesan').html('<div class="alert alert-danger" role="alert">Gagal menambahkan produk ke keranjang. Silakan coba lagi!</div>');
                }
            });
        })

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
                url: "{{ route('marketing.transaction.deleteCart') }}",
                success: function() {
                    // Hapus baris terkait dari tabel
                    e.target.closest('tr').remove();

                    // Periksa apakah keranjang kosong
                    if ($('tbody').find('tr').length === 0) {
                        $('#totalBuy').html(""); // Atur teks pada card-header "Total Belanja" menjadi kosong
                    } else {
                        getTotalBuy(); // Panggil kembali getTotalBuy() untuk memperbarui total belanja
                    }
                },
                error: function() {
                    // Handle error
                }
            });
        });


        const productQuantity = document.getElementById('get_product_quantity');

        productQuantity.addEventListener('keyup', function() {
            let productPrice = document.getElementById('get_product_price');
            let productTotal = document.getElementById('get_product_total');
            let total = productPrice.value * productQuantity.value;
            productTotal.value = formatRupiah(total);
            if ($(this).val() == 0) {
                $('#addToCart').prop('disabled', true);
            } else {
                $('#addToCart').prop('disabled', false);
            }
        })
        productQuantity.addEventListener('change', function() {
            let productPrice = document.getElementById('get_product_price');
            let productTotal = document.getElementById('get_product_total');
            let total = productPrice.value * productQuantity.value;
            productTotal.value = formatRupiah(total);
            if ($(this).val() == 0) {
                $('#addToCart').prop('disabled', true);
            } else {
                $('#addToCart').prop('disabled', false);
            }
        })



        function getTotalBuy() {
            $.ajax({
                type: 'GET',
                url: "{{ route('marketing.transaction.totalBuy') }}",
                dataType: 'json',
                success: function(data) {
                    let totalBuy = document.getElementById('totalBuy');
                    totalBuy.innerHTML = "Total " + formatRupiah(data.data, 'Rp. ');
                },
                error: function(data) {
                    console.log('gagal');
                }
            })
        }




        let anonym = document.getElementById('anonym');
        anonym.addEventListener('click', function(e) {
            let containerCustomer = document.getElementById('customer_name');
            let lContainerCustomer = document.getElementById('l_customer_name');
            if (this.classList.contains('fa-eye')) {
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
                containerCustomer.style.display = 'none';
                lContainerCustomer.style.display = 'none';
            } else {
                this.classList.add('fa-eye');
                this.classList.remove('fa-eye-slash');
                containerCustomer.style.display = 'block';
                lContainerCustomer.style.display = 'block';
            }
        })
        let payment = document.getElementById('payment');
        payment.addEventListener('keyup', function() {
            let tPayment = document.getElementById('tPayment');
            let vReturn = document.getElementById('return');
            let totalBuy = document.getElementById('totalBuy');
            let split = totalBuy.innerHTML.split(' ');
            if (split[2] == 0) {
                alert('Belum ada pesanan');
            }
            let result = parseInt(this.value) - split[2].replace('.', '');
            if (result >= 0) {
                tPayment.disabled = false;
            } else {
                tPayment.disabled = true;
            }
            vReturn.value = result;
        })


    })
</script>

@endpush