@extends('layouts.app')

@section('content')
<style>
    .card-header{
        background-color: #1D3C60;
        color: white !important;
    },
    .btn-primary {
        background-color: #1D3C60 !important;
        color: white !important;
    }
    
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card">
                <div class="card-header">Silahkan Masuk</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-5 col-sm-12">
                            <img src="{{ url('template/image/logohappy.png') }}" style="width:100%;height:100%;">
                        </div>
                        <div class="col-lg-7 col-sm-12 mt-2">
                            <div class="card-header">Masukkan Data Akun</div>
                            <hr>
                            <form action="{{ route('login') }}" method="POST" class="mt-2">
                                @csrf
                                <div class="form-group">
                                    <label for="email"><strong>Email</strong></label>
                                    <input type="email" class="form-control" required name="email" id="email">
                                </div>
                                <div class="form-group">
                                    <label for="password"><strong>Password</strong></label>
                                    <input type="password" class="form-control" required name="password" id="password">
                                </div>
                                <div class="form-group mt-2">
                                    <button type="submit" style="background-color: #1D3C60;color:white;padding:10px;">Masuk</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
