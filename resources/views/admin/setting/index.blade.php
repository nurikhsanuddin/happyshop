@extends('layouts.template')
@section('content')
<style>
    td {
        padding-right: 40px;
    }
</style>
@if ($errors->any())
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button> 
    Konfirmasi password tidak sesuai
</div>
@endif
<div class="row">
    <div class="col-lg-4 col-md-12">
        <div class="card">
            <div class="card-header">
                <center>
                    <img src="{{ url('template/image/profile.png') }}" class="img-rounded" style="widtd:100%;height:200px;">
                </center>
            </div>
            <div class="card-body justify-content-center">
                <center>
                <h5>{{ $company->name }}</h5>
                <h5>{{ $company->address }}</h5>
                </center>
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-md-12">
        <div class="card">
            <div class="card-header">
                Ubah Deskripsi Usaha
            </div>
            <div class="card-body">
                <form action="{{ route('admin.setting.update') }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Nama Usaha</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <hr>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection