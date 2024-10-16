@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
    <h2 class="font-weight-bold text-dark">
        {{ __('Dashboard') }}
    </h2>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <p class="card-text">
                            Selamat datang di dashboard Anda!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

