@extends('mentor.layouts.app')

@section('content')
<div class="container mt-5">
    
    <h2 class="text-right mb-4 custom-title">Daftar Umpan Balik</h2>



    @if ($feedbacks->isEmpty())
        <div class="alert alert-info text-right">Belum ada umpan balik.</div>
    @else
        <div class="row mt-4"> 
            @foreach ($feedbacks as $feedback)
                <div class="col-12 col-sm-6 col-md-4 mb-3"> 
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center"> <!-- Membuat elemen menjadi fleksibel dan terpusat secara vertikal -->
                                <img src="{{ $feedback->user->profile_photo ? asset('storage/' . $feedback->user->profile_photo) : asset('assets/img/avatars/default.jpg') }}" alt="Foto Profil" class="rounded-circle me-3" width="50" height="50">
                                <div>
                                    <h5 class="card-title mb-0">{{ $feedback->name }}</h5>
                                    <h6 class="card-subtitle text-muted mt-1">{{ $feedback->email }}</h6> <!-- Tambahkan margin atas pada email -->
                                </div>
                            </div>
                            <p class="card-text mt-3">{{ $feedback->message }}</p>
                            <p class="card-text">
                                <small class="text-muted">Dikirim pada {{ $feedback->created_at->format('d M Y H:i') }}</small> 
                            </p>
                            
                            <!-- Tampilkan Balasan Hanya Jika Ada -->
                            @if ($feedback->reply)
                                <hr>
                                <h6 class="text-dark">Balasan Admin:</h6>
                                <p class="card-text">{{ $feedback->reply }}</p>
                            @endif
                        </div>                                               
                    </div>
                </div>
            @endforeach
        </div>
        {{ $feedbacks->links('vendor.pagination.bootstrap-4') }}
    @endif
</div>
@endsection

<style>
.card {
    border: none;
    border-radius: 0.5rem;
    transition: transform 0.2s;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Bayangan */
}

.card:hover {
    transform: scale(1.05);
}

.card-body {
    word-wrap: break-word;
}

.card-title {
    font-size: 1.25rem; /* Ukuran judul */
    font-weight: 600;
}

.card-subtitle {
    font-size: 0.85rem;
    color: #6c757d;
}

.card-text {
    margin: 0.5rem 0;
}

.text-dark {
    color: #343a40; /* Warna gelap untuk teks */
}

.alert {
    border-radius: 0.5rem; 
}

.custom-title {
    color: #566a7f; /* Contoh warna */
}
</style>
