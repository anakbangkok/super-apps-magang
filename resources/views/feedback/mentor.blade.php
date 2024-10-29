@extends('mentor.layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-right mb-4 custom-title">Umpan Balik</h1>

    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="row mt-4">
        @foreach ($feedbacks as $feedback)
            <div class="col-12 col-sm-6 col-md-4 mb-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $feedback->name }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ $feedback->email }}</h6>
                        <p class="card-text">{{ $feedback->message }}</p>
                        <p class="card-text">
                            <small class="text-muted">Dikirim pada {{ $feedback->created_at->format('d M Y H:i') }}</small>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

<style>
.card {
    border: none;
    border-radius: 0.5rem;
    transition: transform 0.2s;
}

.card:hover {
    transform: scale(1.05);
}

.card-body {
    word-wrap: break-word; /* Memastikan teks panjang ter-wrap dengan baik */
}

.card-title {
    font-size: 1.5rem;
    font-weight: bold;
}

.card-subtitle {
    font-size: 0.9rem;
    color: #6c757d;
}

.card-text {
    margin: 0.5rem 0;
}

.alert {
    border-radius: 0.5rem; 
}

.custom-title {
    color: #566a7f; /* Contoh warna */
}
</style>
