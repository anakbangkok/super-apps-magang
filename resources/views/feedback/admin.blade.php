<!-- resources/views/feedback/admin.blade.php -->
@extends('admin.layouts.app')

@section('content')
<div class="container py-3"> <!-- Kurangi margin dan padding -->
    <h1 class="text-right mb-3">Manajemen Umpan Balik Admin</h1>

    @if (session('success'))
        <div class="alert alert-success text-right">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-2 mt-3"> <!-- Kurangi jarak antar elemen dengan g-2 -->
        @foreach ($feedbacks as $feedback)
            <div class="col-12 col-sm-6 col-md-4"> 
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text">{{ $feedback->name }}</h5> 
                        <h6 class="card-subtitle mb-1 text-muted">{{ $feedback->email }}</h6> <!-- Margin bawah dikurangi -->
                        <p class="card-text">{{ $feedback->message }}</p>
                        <p class="card-text mb-1"> <!-- Tambah margin bawah kecil -->
                            <small class="text-muted">Dikirim {{ $feedback->created_at->format('d M Y H:i') }}</small>
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <form action="{{ route('feedback.destroy', $feedback->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bx bx-trash"></i> Hapus
                            </button>
                        </form>
                    
                        <form action="{{ route('feedback.reply', $feedback->id) }}" method="POST" class="mt-2">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="reply" class="form-control" placeholder="Balas umpan balik...">
                                <button type="submit" class="btn btn-primary btn-sm">Kirim</button>
                            </div>
                        </form>
                        @if($feedback->reply)
                            <div class="mt-2">
                                <strong>Balasan:</strong> {{ $feedback->reply }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

<style>
/* Tampilan Kartu */
.card {
    border: none;
    border-radius: 0.5rem; /* Kurangi radius */
    transition: transform 0.15s, box-shadow 0.15s;
    margin-bottom: 0.5rem; /* Jarak antar kartu lebih kecil */
}

.card:hover {
    transform: scale(1.03); 
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); 
}

/* Header Kartu */
.card-title {
    font-size: 1.15rem; 
    font-weight: 600;
}

.card-subtitle {
    font-size: 0.85rem;
}

/* Konten Kartu */
.card-text {
    color: #495057;
}

</style>
